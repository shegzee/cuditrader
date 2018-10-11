<?php
defined('BASEPATH') OR exit('');

/**
 * Description of Settings
 *
 * @author Olu Segun <ojosamuelolusegun@gmail.com>
 * @date 3rd Oct, 2018
 */
class Settings extends CI_Controller{
    
    public function __construct(){
        parent::__construct();
        
        $this->genlib->checkLogin();
        
        //$this->genlib->superOnly();
        
        $this->load->model(['admin/setting']);
    }
    
    /*
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    */
    
    public function index(){
        $data['pageContent'] = $this->load->view('admin/settings/settings', '', TRUE);
        $data['pageTitle'] = "Settings";
        
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
     * lase_ = "Load all settings"
     */
    public function lase_(){
        //set the sort order
        $orderBy = $this->input->get('orderBy', TRUE) ? $this->input->get('orderBy', TRUE) : "setting";
        $orderFormat = $this->input->get('orderFormat', TRUE) ? $this->input->get('orderFormat', TRUE) : "ASC";
        
        //count the total Loan units in db.
        $totalSettings = count($this->setting->getAllSettings());
        
        $this->load->library('pagination');
        
        $pageNumber = $this->uri->segment(3, 0);//set page number to zero if the page number is not set in the third segment of uri
	
        $limit = $this->input->get('limit', TRUE) ? $this->input->get('limit', TRUE) : 10;//show $limit per page
        $start = $pageNumber == 0 ? 0 : ($pageNumber - 1) * $limit;//start from 0 if pageNumber is 0, else start from the next iteration
        
        //call setPaginationConfig($totalRows, $urlToCall, $limit, $attributes) in genlib to configure pagination
        $config = $this->genlib->setPaginationConfig($totalSettings, "settings/lase_", $limit, ['class'=>'lnp']);
        
        $this->pagination->initialize($config);//initialize the library class
        
        //get all Loan units from db
        $data['allSettings'] = $this->setting->getAllSettings($orderBy, $orderFormat, $start, $limit);
        //echo "<script> console.log('All Loan units: ',",$data['allSettings'],");</script>";
        $data['range'] = $totalSettings >= 0 ? ($start+1) . "-" . ($start + count($data['allSettings'])) . " of " . $totalSettings : "";
        $data['links'] = $this->pagination->create_links();//page links
        $data['sn'] = $start+1;
        
        $json['settingsTable'] = $this->load->view('admin/settings/settingslist', $data, TRUE);//get view with populated Loan units table

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
     * To add new setting
     */
    public function addSetting(){
        $this->genlib->ajaxOnly();
        
        $this->load->library('form_validation');

        $this->form_validation->set_error_delimiters('', '');
        
        $this->form_validation->set_rules('setting', 'Setting', ['required', 'trim', 'max_length[50]'], ['required'=>"required"]);
        $this->form_validation->set_rules('value', 'Value', ['required', 'trim', 'max_length[255]'], ['required'=>"required"]);
           
        if($this->form_validation->run() !== FALSE){
            /**
             * insert info into db
             * function header: add($f_name, $l_name, $email, $password, $role, $mobile, $addr)
             */
            
            $inserted = $this->setting->addSetting(set_value('setting'), set_value('value'));
            
            
            $json = $inserted ? 
                ['status'=>1, 'msg'=>"Setting successfully created"] 
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
    public function updateSetting(){
        $this->genlib->ajaxOnly();
        
        $this->load->library('form_validation');

        $this->form_validation->set_error_delimiters('', '');
        
        $this->form_validation->set_rules('name', 'Name', ['required', 'trim', 'max_length[50]'], ['required'=>"required"]);
        
        if($this->form_validation->run() !== FALSE){
            /**
             * update info in db
             */
				
            $id = $this->input->post('settingId', TRUE);

            $updated = $this->setting->updateSetting($id, set_value('name'), set_value('logo'));
            
            
            $json = $updated ? 
                    ['status'=>1, 'msg'=>"Unit info successfully updated"] 
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
    
    public function deleteSetting(){
        $this->genlib->ajaxOnly();
        
        $setting_id = $this->input->post('_luId');
        // $new_value = $this->genmod->gettablecol('loan_units', 'deleted', 'id', $setting_id) == 1 ? 0 : 1;
        
        $done = $this->setting->deleteSetting($setting_id);
        
        $json['status'] = $done ? 1 : 0;
        // $json['_nv'] = $new_value;
        $json['_nv'] = 0;
        $json['_luId'] = $setting_id;
        
        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }


 /* --------------------------------------------------------------------------------------- */
 // here's for tenors
 /**
     * late_ = "Load all tenors"
     */
    public function late_(){
        //set the sort order
        $orderBy = $this->input->get('orderBy', TRUE) ? $this->input->get('orderBy', TRUE) : "name";
        $orderFormat = $this->input->get('orderFormat', TRUE) ? $this->input->get('orderFormat', TRUE) : "ASC";
        
        //count the total tenors in db.
        $totalTenors = count($this->setting->getAllTenors());
        
        $this->load->library('pagination');
        
        $pageNumber = $this->uri->segment(3, 0);//set page number to zero if the page number is not set in the third segment of uri
    
        $limit = $this->input->get('limit', TRUE) ? $this->input->get('limit', TRUE) : 10;//show $limit per page
        $start = $pageNumber == 0 ? 0 : ($pageNumber - 1) * $limit;//start from 0 if pageNumber is 0, else start from the next iteration
        
        //call setPaginationConfig($totalRows, $urlToCall, $limit, $attributes) in genlib to configure pagination
        $config = $this->genlib->setPaginationConfig($totalTenors, "settings/late_", $limit, ['class'=>'lnp']);
        
        $this->pagination->initialize($config);//initialize the library class
        
        //get all tenors from db
        $data['allTenors'] = $this->setting->getAllTenors($orderBy, $orderFormat, $start, $limit);
        //echo "<script> console.log('All Units: ',",$data['allTenors'],");</script>";
        $data['range'] = $totalTenors >= 0 ? ($start+1) . "-" . ($start + count($data['allTenors'])) . " of " . $totalTenors : "";
        $data['links'] = $this->pagination->create_links();//page create_links
        $data['sn'] = $start+1;
        
        $json['tenorTable'] = $this->load->view('admin/settings/tenorlist', $data, TRUE);//get view with populated tenors table

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
     * To add new collateral unit
     */
    public function addTenor(){
        $this->genlib->ajaxOnly();
        
        $this->load->library('form_validation');

        $this->form_validation->set_error_delimiters('', '');
        
        $this->form_validation->set_rules('tenor', 'Tenor', ['required', 'trim', 'max_length[50]'], ['required'=>"required"]);
        $this->form_validation->set_rules('display', 'Display', ['required'], ['required'=>"required"]);
           
        if($this->form_validation->run() !== FALSE){
            /**
             * insert info into db
             * function header: add($f_name, $l_name, $email, $password, $role, $mobile, $addr)
             */
            
            $inserted = $this->setting->addTenor(set_value('tenor'), set_value('display'));
            
            
            $json = $inserted ? 
                ['status'=>1, 'msg'=>"Tenor successfully created"] 
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
    public function updateTenor(){
        $this->genlib->ajaxOnly();
        
        $this->load->library('form_validation');

        $this->form_validation->set_error_delimiters('', '');
        
        $this->form_validation->set_rules('tenor', 'Tenor', ['required'], ['required'=>"required"]);
        $this->form_validation->set_rules('display', 'Display', ['required', 'trim', 'max_length[50]'], ['required'=>"required"]);
        
        if($this->form_validation->run() !== FALSE){
            /**
             * update info in db
             */
                
            $id = $this->input->post('tenorId', TRUE);

            $updated = $this->setting->updateTenor($id, set_value('tenor'), set_value('display'));
            
            
            $json = $updated ? 
                    ['status'=>1, 'msg'=>"Tenor successfully updated"] 
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
    
    public function deleteTenor(){
        $this->genlib->ajaxOnly();
        
        $id = $this->input->post('_tId');
        // $new_value = $this->genmod->gettablecol('collateral_units', 'deleted', 'id', $id) == 1 ? 0 : 1;
        // $this->unit->deleteTenor($id);
        
        $done = $this->setting->deleteTenor($id);
        
        $json['status'] = $done ? 1 : 0;
        // $json['_nv'] = $new_value;
        $json['_nv'] = 0;
        $json['_tId'] = $id;
        
        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }
}