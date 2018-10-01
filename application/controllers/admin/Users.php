<?php
defined('BASEPATH') OR exit('');

/**
 * Description of Users
 *
 * @author Theophile <tolutheo@gmail.com>
 * @date 19th Jul, 2018
 */
class Users extends CI_Controller{
    
    public function __construct(){
        parent::__construct();
        
        $this->genlib->checkLogin();
        
        //$this->genlib->superOnly();
        
        $this->load->model(['admin/user']);
    }
    
    /*
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    */
    
    public function index(){
        $data['pageContent'] = $this->load->view('admin/users/user', '', TRUE);
        $data['pageTitle'] = "Users";
        
        $this->load->view('admin/main', $data);
    }
    
    
    /*
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    */
    
    /**
     * lau_ = "Load all users"
     */
    public function laus_(){
        //set the sort order
        $orderBy = $this->input->get('orderBy', TRUE) ? $this->input->get('orderBy', TRUE) : "first_name";
        $orderFormat = $this->input->get('orderFormat', TRUE) ? $this->input->get('orderFormat', TRUE) : "ASC";
        
        //count the total users in db.
        $totalUsers = count($this->user->getAll());
        
        $this->load->library('pagination');
        
        $pageNumber = $this->uri->segment(3, 0);//set page number to zero if the page number is not set in the third segment of uri
	
        $limit = $this->input->get('limit', TRUE) ? $this->input->get('limit', TRUE) : 10;//show $limit per page
        $start = $pageNumber == 0 ? 0 : ($pageNumber - 1) * $limit;//start from 0 if pageNumber is 0, else start from the next iteration
        
        //call setPaginationConfig($totalRows, $urlToCall, $limit, $attributes) in genlib to configure pagination
        $config = $this->genlib->setPaginationConfig($totalUsers, "users/laus_", $limit, ['class'=>'lnp']);
        
        $this->pagination->initialize($config);//initialize the library class
        
        //get all customers from db
        $data['allUsers'] = $this->user->getAll($orderBy, $orderFormat, $start, $limit);
        //echo "<script> console.log('All Users: ',",$data['allUsers'],");</script>";
        $data['range'] = $totalUsers >= 0 ? ($start+1) . "-" . ($start + count($data['allUsers'])) . " of " . $totalUsers : "";
        $data['links'] = $this->pagination->create_links();//page links
        $data['sn'] = $start+1;
        
        $json['userTable'] = $this->load->view('admin/users/userlist', $data, TRUE);//get view with populated customers table

        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }
    
    
    /*
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    */
    
    
    /**
     * To add new user
     */
    public function add(){
        $this->genlib->ajaxOnly();
        
        $this->load->library('form_validation');

        $this->form_validation->set_error_delimiters('', '');
        
        $this->form_validation->set_rules('firstName', 'First name', ['required', 'trim', 'max_length[20]', 'strtolower', 'ucfirst'], ['required'=>"required"]);
        $this->form_validation->set_rules('lastName', 'Last name', ['required', 'trim', 'max_length[20]', 'strtolower', 'ucfirst'], ['required'=>"required"]);
        $this->form_validation->set_rules('email', 'E-mail', ['trim', 'required', 'valid_email', 'is_unique[users.email]', 'strtolower'], 
                ['required'=>"required", 'is_unique'=>'E-mail exists']);
        $this->form_validation->set_rules('mobile', 'Phone number', ['trim', 'numeric', 'max_length[15]', 'min_length[11]', 'is_unique[users.mobile]'], 
                ['required'=>"required", 'is_unique'=>"This number is already attached to a user"]);
        $this->form_validation->set_rules('address', 'Address', ['trim']);
        $this->form_validation->set_rules('passwordOrig', 'Password', ['required', 'min_length[8]'], ['required'=>"Enter password"]);
        $this->form_validation->set_rules('passwordDup', 'Password Confirmation', ['required', 'matches[passwordOrig]'], ['required'=>"Please retype password"]);
        
        if($this->form_validation->run() !== FALSE){
            /**
             * insert info into db
             * function header: add($f_name, $l_name, $email, $password, $role, $mobile, $addr)
             */
            $hashedPassword = password_hash(set_value('passwordOrig'), PASSWORD_BCRYPT);
            
            $inserted = $this->user->add(set_value('firstName'), set_value('lastName'), set_value('email'), $hashedPassword, 
                set_value('mobile'), set_value('address'));
            
            
            $json = $inserted ? 
                ['status'=>1, 'msg'=>"User account successfully created"] 
                : 
                ['status'=>0, 'msg'=>"Oops! Unexpected server error! Pls contact administrator for help. Sorry for the embarrassment"];
        }
        
        else{
            //return all error messages
            $json = $this->form_validation->error_array();//get an array of all errors
            
            $json['msg'] = "One or more required fields are empty or not correctly filled";
            $json['status'] = 0;
        }
                    
        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }
    
    /*
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    */
    
    
    /**
     * 
     */
    public function update(){
        $this->genlib->ajaxOnly();
        
        $this->load->library('form_validation');

        $this->form_validation->set_error_delimiters('', '');
        
        $this->form_validation->set_rules('firstName', 'First name', ['required', 'trim', 'max_length[20]'], ['required'=>"required"]);
        $this->form_validation->set_rules('lastName', 'Last name', ['required', 'trim', 'max_length[20]'], ['required'=>"required"]);
        $this->form_validation->set_rules('phone', 'Phone number', ['trim', 'numeric', 'max_length[15]', 
            'min_length[11]', 'callback_crosscheckMobile['. $this->input->post('userId', TRUE).']']);
        $this->form_validation->set_rules('address', 'Home Address', ['trim']);
        $this->form_validation->set_rules('email', 'E-mail', ['required', 'trim', 'valid_email', 'callback_crosscheckEmail['. $this->input->post('userId', TRUE).']']);
        
        if($this->form_validation->run() !== FALSE){
            /**
             * update info in db
             * function header: update($user_id, $first_name, $last_name, $email, $mobile, $addr, $role)
             */
				
            $user_id = $this->input->post('userId', TRUE);

            $updated = $this->user->update($user_id, set_value('firstName'), set_value('lastName'), set_value('email'),
                    set_value('phone'), set_value('address'));
            
            
            $json = $updated ? 
                    ['status'=>1, 'msg'=>"User info successfully updated"] 
                    : 
                    ['status'=>0, 'msg'=>"Oops! Unexpected server error! Pls contact administrator for help. Sorry for the embarrassment"];
        }
        
        else{
            //return all error messages
            $json = $this->form_validation->error_array();//get an array of all errors
            
            $json['msg'] = "One or more required fields are empty or not correctly filled";
            $json['status'] = 0;
        }
                    
        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }
    
    /*
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    */
    
    
    public function suspend(){
        $this->genlib->ajaxOnly();
        
        $user_id = $this->input->post('_uId');
        $new_status = $this->genmod->gettablecol('users', 'active', 'id', $user_id) == 1 ? 0 : 1;
        
        $done = $this->user->suspend($user_id, $new_status);
        
        $json['status'] = $done ? 1 : 0;
        $json['_ns'] = $new_status;
        $json['_uId'] = $user_id;
        
        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }
    
    
    
    /*
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    */
    
    public function delete(){
        $this->genlib->ajaxOnly();
        
        $user_id = $this->input->post('_uId');
        $new_value = $this->genmod->gettablecol('users', 'deleted', 'id', $user_id) == 1 ? 0 : 1;
        
        $done = $this->user->delete($user_id, $new_value);
        
        $json['status'] = $done ? 1 : 0;
        $json['_nv'] = $new_value;
        $json['_uId'] = $user_id;
        
        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }
    
    
    /*
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    */
    
    /**
     * Used as a callback while updating user info to ensure 'mobile' field does not contain a number already used by another user
     * @param type $mobile_number
     * @param type $user_id
     */
    public function crosscheckMobile($mobile_number, $user_id){
        //check db to ensure number was previously used for user with $user_id i.e. the same user we're updating his details
        if ($mobile_number == "") {
            return TRUE;
        }
        $userWithNum = $this->genmod->getTableCol('users', 'id', 'phone', $mobile_number);
        if (!$userWithNum) return TRUE;
        if($userWithNum == $user_id){
            //used for same user. All is well.
            return TRUE;
        }
        
        else{
            $this->form_validation->set_message('crosscheckMobile', 'This number is already attached to a user');
                
            return FALSE;
        }
    }
    
    /*
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    */
    
    /**
     * Used as a callback while updating user info to ensure 'email' field does not contain an email already used by another user
     * @param type $email
     * @param type $user_id
     */
    public function crosscheckEmail($email, $user_id){
        //check db to ensure email was previously used for user with $user_id i.e. the same user we're updating his details
        $userWithEmail = $this->genmod->getTableCol('users', 'id', 'email', $email);
        if (!$userWithEmail) return TRUE;
        if($userWithEmail == $user_id){
            //used for same user. All is well.
            return TRUE;
        }
        
        else{
            $this->form_validation->set_message('crosscheckEmail', 'This email is already attached to a user');
                
            return FALSE;
        }
    }
    public function resetPassword(){
        $email = $this->input->post('email', TRUE);
        
        $json = $this->genlib->resetCustomerPassword($email, 'admin');
        
        if($json['status'] === 1){
            $user_info = $this->user->getUserInfoForLogin($email);            
            
            //log activity
            $activity = "reset the password of user <b>{$email}</b>";
            
            //add($user_id, $title, $activity, $activity_group)
            $this->activity->add($this->session->admin_id, "User Password Reset", $activity, $this->activity_group);
        }
        
        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }
    
}