<?php
defined('BASEPATH') OR exit('');

/**
 * Description of Misc
 * Do not check login status in the constructor of this class and some functions are to be accessed even without logging in
 *
 * @author Amir <amirsanni@gmail.com>
 * date 17th Feb. 2016
 */
class Misc extends CI_Controller{
    protected $activity_group = "Password Management";
    
    public function __construct() {
        parent::__construct();
        
        $this->load->model(['admin']);
    }
    
    public function totalEarnedToday(){
        $this->genlib->checkLogin();
        
        $this->genlib->ajaxOnly();
        
        $this->load->model('admin/transaction');
        
        $total_earned_today = $this->transaction->totalEarnedToday();
        
        $json['totalEarnedToday'] = $total_earned_today ? number_format($total_earned_today, 2) : "0.00";
        
        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }
	
	
	
    /**
     * check if admin's session is still on
     */
    public function check_session_status(){
        if(isset($_SESSION['admin_id']) && ($_SESSION['admin_id'] !== false) && ($_SESSION['admin_id'] !== "")){
            $json['status'] = 1;
            
            //update user's last seen time
            //update_last_seen_time($id, $table_name)
            $this->genmod->update_last_seen_time($_SESSION['admin_id'], 'admin');
        }
        
        else{
            $json['status'] = 0;
        }
        
        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }
    
    
    
    public function dbmanagement(){
        $this->genlib->checkLogin();
        
        $this->genlib->superOnly();
        
        $data['pageContent'] = $this->load->view('admin/dbbackup', '', TRUE);
        $data['pageTitle'] = "Database";
        
        $this->load->view('admin/main', $data);
    }
    
    
    public function dldb(){
        $this->genlib->checkLogin();
        
        $this->genlib->superOnly();
        
        $file_path = BASEPATH . "sqlite/1410inventory.sqlite";//link to db file
        
        $this->output->set_content_type('')->set_output(file_get_contents($file_path));
    }
    
    
    
    
    /**
     * 
     */
    public function importdb(){
        $this->genlib->checkLogin();
        
        $this->genlib->superOnly();
        
        //create a copy of the db file currently in the sqlite dir for keep in case something go wrong
        if(file_exists(BASEPATH."sqlite/1410inventory.sqlite")){
            copy(BASEPATH."sqlite/1410inventory.sqlite", BASEPATH."sqlite/backups/".time().".sqlite");
        }
        
        $config['upload_path'] = BASEPATH . "sqlite/";//db files are stored in the basepath
        $config['allowed_types'] = 'sqlite';
        $config['file_ext_tolower'] = TRUE;
        $config['file_name'] = "1410inventory.sqlite";
        $config['max_size'] = 2000;//in kb
        $config['overwrite'] = TRUE;//overwrite the previous file

        $this->load->library('upload', $config);//load CI's 'upload' library

        $this->upload->initialize($config, TRUE);

        if($this->upload->do_upload('dbfile') == FALSE){
            $json['msg'] = $this->upload->display_errors();
            $json['status'] = 0;
        }

        else{
            $json['status'] = 1;
        }
        
        //set final output
        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }


//New codes start

    
    
   
    public function changePassword(){
        $this->load->library('form_validation');

        $this->form_validation->set_error_delimiters('', '');
        
        $this->form_validation->set_rules('cp', 'Current Password', ['trim', 'required']);
        $this->form_validation->set_rules('np', 'New Password', ['trim', 'required', 'min_length[8]']);
        $this->form_validation->set_rules('npc', 'Confirm New Password', ['trim', 'required', 'min_length[8]', 'matches[np]']);
        
        if($this->form_validation->run() !== FALSE){
            $current_pw_in_db = $this->genmod->getTableCol('admin', 'password', 'id', $this->session->admin_id);
            
            if(password_verify(set_value('cp'), $current_pw_in_db)){
                $encrypted_pw = password_hash(set_value('np'), PASSWORD_BCRYPT);

                $json['status'] = (int)$this->admin->update($this->session->admin_id, ['password'=>$encrypted_pw]);

                //add action to logs
                //add($admin_id, $title, $activity, $activity_group)
                $activity = $json['status'] ? "successfully changed password" : "attempted to change password but was unsuccessful";

                $this->activity->add($this->session->admin_id, "Password Update", $activity, $this->activity_group);

                //send email to admin concerning action
                $e_info['msg_content'] = "<p>Dear {$this->session->admin_name}, </p>"
                . "<p>Your Customer Portal account password was successfully changed.</p>"
                . "<p>Do not hesitate to contact IT support in case you need help.</p>"
                . "<p>Regards</p>";

                $u_msg = $this->load->view('email/default', $e_info, TRUE);

                //send_email($sname, $semail, $rname, $remail, $subject, $message, $cc='', $bcc='', $replyToEmail="", $files="")
                $this->genlib->send_email(DEFAULT_NAME, DEFAULT_EMAIL, $this->session->admin_name, $this->session->admin_email, "Password Update", $u_msg);
            }
            
            else{
                $json['status'] = 0;
                $json['msg'] = "Incorrect Password";
            }
        }
        
        else{
            $msg = form_error('cp') ? form_error('cp') : (form_error('np') ? form_error('np') : form_error('npc'));
            
            $json = ['msg'=>$msg, 'status'=>-1];
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
    
    public function resetPassword(){
        $email = $this->input->post('email', TRUE);
        
        $json = ['msg'=>"Email is required", 'status'=>0];
        
        /*
         * Ensure $email has a value and that it exists in the db (not deleted and not suspended)
         *    If it exist and not marked as deleted or suspended
         *       generate a random string
         *       encrypt the generated string and update password in db
         *       send generated (unencrypted) string to admin's email
         *    else if it exist but marked as deleted or suspended,
         *       inform admin that he is not allowed to perform the action
         *    else i.e. it does not exist,
         *       inform admin that email was not found
         */
        
        //getAdministratorInfo($fields_arr, $where_arr)
        $admin_info = $this->admin->getAdministratorInfo(
            ['id', 'first_name', 'deleted', 'acct_status'], 
            ['email'=>$email]
        );
        
        if($admin_info){
            if($admin_info->acct_status && !$admin_info->deleted){
                
                $this->load->helper('string');
                
                $new_pw = random_string('alnum', 10);
                
                $encrypted_pw = password_hash($new_pw, PASSWORD_BCRYPT);
                
                $json['status'] = (int)$this->admin->update($admin_info->id, ['password'=>$encrypted_pw]);

                //add action to logs
                //add($admin_id, $title, $activity, $activity_group)
                $activity = "requested for password reset";

                $this->activity->add($admin_info->id, "Reset Password", $activity, $this->activity_group);
                
                //send new password to admin's email
                $e_info['msg_content'] = "<p>Dear {$admin_info->first_name}, </p>"
                . "<p>Your Customer Portal account password has been reset.</p><br>"
                . "<p>Your new password is: <b>{$new_pw}</b></p><br>"
                . "<p>Do not hesitate to contact IT support in case you need help.</p>"
                . "<p>Regards</p>";
                
                $e_info['btn_link'] = base_url('admin');
                $e_info['btn_text'] = "Click here to log in";

                $u_msg = $this->load->view('email/default', $e_info, TRUE);

                //send_email($sname, $semail, $rname, $remail, $subject, $message, $cc='', $bcc='', $replyToEmail="", $files="")
                $this->genlib->send_email(DEFAULT_NAME, DEFAULT_EMAIL, $admin_info->first_name, $email, "Password Update", $u_msg);
            }
            
            else{
                $json['msg'] = "You are not allowed to perform this action";
            }
        }
        
        else{
            $json = ['msg'=>'This email does not exist'];
        }
        
        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }
}
