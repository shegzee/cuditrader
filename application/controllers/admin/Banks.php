<?php
defined('BASEPATH') OR exit('');

/**
 * Description of Banks
 *
 * @author Olu Segun <ojosamuelolusegun@gmail.com>
 * @date 30th Aug, 2018
 */
class Banks extends CI_Controller{
    
    public function __construct(){
        parent::__construct();
        
        $this->genlib->checkLogin();
        
        //$this->genlib->superOnly();
        
        $this->load->model(['admin/bank']);
    }
    
    /*
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    */
    
    public function index(){
        $data['pageContent'] = $this->load->view('admin/banks/bank', '', TRUE);
        $data['pageTitle'] = "Banks";
        
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
     * laba_ = "Load all banks"
     */
    public function laba_(){
        //set the sort order
        $orderBy = $this->input->get('orderBy', TRUE) ? $this->input->get('orderBy', TRUE) : "name";
        $orderFormat = $this->input->get('orderFormat', TRUE) ? $this->input->get('orderFormat', TRUE) : "ASC";
        
        //count the total banks in db.
        $totalBanks = count($this->bank->getAll());
        
        $this->load->library('pagination');
        
        $pageNumber = $this->uri->segment(3, 0);//set page number to zero if the page number is not set in the third segment of uri
	
        $limit = $this->input->get('limit', TRUE) ? $this->input->get('limit', TRUE) : 10;//show $limit per page
        $start = $pageNumber == 0 ? 0 : ($pageNumber - 1) * $limit;//start from 0 if pageNumber is 0, else start from the next iteration
        
        //call setPaginationConfig($totalRows, $urlToCall, $limit, $attributes) in genlib to configure pagination
        $config = $this->genlib->setPaginationConfig($totalBanks, "banks/laba_", $limit, ['class'=>'lnp']);
        
        $this->pagination->initialize($config);//initialize the library class
        
        //get all banks from db
        $data['allBanks'] = $this->bank->getAll($orderBy, $orderFormat, $start, $limit);
        //echo "<script> console.log('All Banks: ',",$data['allBanks'],");</script>";
        $data['range'] = $totalBanks >= 0 ? ($start+1) . "-" . ($start + count($data['allBanks'])) . " of " . $totalBanks : "";
        $data['links'] = $this->pagination->create_links();//page links
        $data['sn'] = $start+1;
        
        $json['bankTable'] = $this->load->view('admin/banks/banklist', $data, TRUE);//get view with populated banks table

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
     * To add new bank
     */
    public function add(){
        $this->genlib->ajaxOnly();
        
        $this->load->library('form_validation');

        $this->form_validation->set_error_delimiters('', '');
        
        $this->form_validation->set_rules('name', 'Name', ['required', 'trim', 'max_length[50]'], ['required'=>"required"]);
        $this->form_validation->set_rules('description', 'Description', ['trim']);
           
        if($this->form_validation->run() !== FALSE){
            /**
             * insert info into db
             * function header: add($f_name, $l_name, $email, $password, $role, $mobile, $addr)
             */
            
            $inserted = $this->bank->add(set_value('name'), set_value('description'));
            
            
            $json = $inserted ? 
                ['status'=>1, 'msg'=>"Bank successfully created"] 
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
        
        $this->form_validation->set_rules('name', 'Name', ['required', 'trim', 'max_length[50]'], ['required'=>"required"]);
        $this->form_validation->set_rules('description', 'Description', ['trim']);
        
        if($this->form_validation->run() !== FALSE){
            /**
             * update info in db
             */
				
            $id = $this->input->post('bankId', TRUE);

            $updated = $this->bank->update($id, set_value('name'), set_value('description'));
            
            
            $json = $updated ? 
                    ['status'=>1, 'msg'=>"Bank info successfully updated"] 
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
    
    public function delete(){
        $this->genlib->ajaxOnly();
        
        $bank_id = $this->input->post('_bId');
        $new_value = $this->genmod->gettablecol('banks', 'deleted', 'id', $bank_id) == 1 ? 0 : 1;
        
        $done = $this->bank->delete($bank_id, $new_value);
        
        $json['status'] = $done ? 1 : 0;
        $json['_nv'] = $new_value;
        $json['_bId'] = $bank_id;
        
        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }


 /* --------------------------------------------------------------------------------------- */
 // here's for account types
 /**
     * laat_ = "Load all account types"
     */
    public function laat_(){
        //set the sort order
        $orderBy = $this->input->get('orderBy', TRUE) ? $this->input->get('orderBy', TRUE) : "name";
        $orderFormat = $this->input->get('orderFormat', TRUE) ? $this->input->get('orderFormat', TRUE) : "ASC";
        
        //count the total banks in db.
        $totalBankATs = count($this->bank->getAllAT());
        
        $this->load->library('pagination');
        
        $pageNumber = $this->uri->segment(3, 0);//set page number to zero if the page number is not set in the third segment of uri
    
        $limit = $this->input->get('limit', TRUE) ? $this->input->get('limit', TRUE) : 10;//show $limit per page
        $start = $pageNumber == 0 ? 0 : ($pageNumber - 1) * $limit;//start from 0 if pageNumber is 0, else start from the next iteration
        
        //call setPaginationConfig($totalRows, $urlToCall, $limit, $attributes) in genlib to configure pagination
        $config = $this->genlib->setPaginationConfig($totalBankATs, "banks/laat_", $limit, ['class'=>'lnp']);
        
        $this->pagination->initialize($config);//initialize the library class
        
        //get all banks from db
        $data['allBankATs'] = $this->bank->getAllAT($orderBy, $orderFormat, $start, $limit);
        //echo "<script> console.log('All Banks: ',",$data['allBankATs'],");</script>";
        $data['range'] = $totalBankATs >= 0 ? ($start+1) . "-" . ($start + count($data['allBankATs'])) . " of " . $totalBankATs : "";
        $data['links'] = $this->pagination->create_links();//page create_links
        $data['sn'] = $start+1;
        
        $json['bankATTable'] = $this->load->view('admin/banks/bankATlist', $data, TRUE);//get view with populated banks table

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
     * To add new bank
     */
    public function addAT(){
        $this->genlib->ajaxOnly();
        
        $this->load->library('form_validation');

        $this->form_validation->set_error_delimiters('', '');
        
        $this->form_validation->set_rules('name', 'Name', ['required', 'trim', 'max_length[50]'], ['required'=>"required"]);
        $this->form_validation->set_rules('description', 'Description', ['trim']);
           
        if($this->form_validation->run() !== FALSE){
            /**
             * insert info into db
             * function header: add($f_name, $l_name, $email, $password, $role, $mobile, $addr)
             */
            
            $inserted = $this->bank->addAT(set_value('name'), set_value('description'));
            
            
            $json = $inserted ? 
                ['status'=>1, 'msg'=>"Account type successfully created"] 
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
    public function updateAT(){
        $this->genlib->ajaxOnly();
        
        $this->load->library('form_validation');

        $this->form_validation->set_error_delimiters('', '');
        
        $this->form_validation->set_rules('name', 'Name', ['required', 'trim', 'max_length[50]'], ['required'=>"required"]);
        $this->form_validation->set_rules('description', 'Description', ['trim']);
        
        if($this->form_validation->run() !== FALSE){
            /**
             * update info in db
             */
                
            $id = $this->input->post('accountTypeId', TRUE);

            $updated = $this->bank->updateAT($id, set_value('name'), set_value('description'));
            
            
            $json = $updated ? 
                    ['status'=>1, 'msg'=>"Account type successfully updated"] 
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
    
    public function deleteAT(){
        $this->genlib->ajaxOnly();
        
        $id = $this->input->post('_bId');
        $new_value = $this->genmod->gettablecol('bank_account_types', 'deleted', 'id', $id) == 1 ? 0 : 1;
        
        $done = $this->bank->delete($bank_id, $new_value);
        
        $json['status'] = $done ? 1 : 0;
        $json['_nv'] = $new_value;
        $json['_bId'] = $id;
        
        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }
}