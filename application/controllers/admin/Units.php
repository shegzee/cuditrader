<?php
defined('BASEPATH') OR exit('');

/**
 * Description of Units
 *
 * @author Olu Segun <ojosamuelolusegun@gmail.com>
 * @date 4th Sep, 2018
 */
class Units extends CI_Controller{
    
    public function __construct(){
        parent::__construct();
        
        $this->genlib->checkLogin();
        
        //$this->genlib->superOnly();
        
        $this->load->model(['admin/unit']);
    }
    
    /*
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    */
    
    public function index(){
        $data['pageContent'] = $this->load->view('admin/units/unit', '', TRUE);
        $data['pageTitle'] = "Currency Units";
        
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
     * lalu_ = "Load all loan units"
     */
    public function lalu_(){
        //set the sort order
        $orderBy = $this->input->get('orderBy', TRUE) ? $this->input->get('orderBy', TRUE) : "name";
        $orderFormat = $this->input->get('orderFormat', TRUE) ? $this->input->get('orderFormat', TRUE) : "ASC";
        
        //count the total loan units in db.
        $totalLUnits = count($this->unit->getAllLUnits());
        
        $this->load->library('pagination');
        
        $pageNumber = $this->uri->segment(3, 0);//set page number to zero if the page number is not set in the third segment of uri
	
        $limit = $this->input->get('limit', TRUE) ? $this->input->get('limit', TRUE) : 10;//show $limit per page
        $start = $pageNumber == 0 ? 0 : ($pageNumber - 1) * $limit;//start from 0 if pageNumber is 0, else start from the next iteration
        
        //call setPaginationConfig($totalRows, $urlToCall, $limit, $attributes) in genlib to configure pagination
        $config = $this->genlib->setPaginationConfig($totalLUnits, "units/lalu_", $limit, ['class'=>'lnp']);
        
        $this->pagination->initialize($config);//initialize the library class
        
        //get all loan units from db
        $data['allLUnits'] = $this->unit->getAllLUnits($orderBy, $orderFormat, $start, $limit);
        //echo "<script> console.log('All Loan Units: ',",$data['allLUnits'],");</script>";
        $data['range'] = $totalLUnits >= 0 ? ($start+1) . "-" . ($start + count($data['allLUnits'])) . " of " . $totalLUnits : "";
        $data['links'] = $this->pagination->create_links();//page links
        $data['sn'] = $start+1;
        
        $json['lUnitTable'] = $this->load->view('admin/units/lunitlist', $data, TRUE);//get view with populated loan units table

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
     * To add new loan unit
     */
    public function addLUnit(){
        $this->genlib->ajaxOnly();
        
        $this->load->library('form_validation');

        $this->form_validation->set_error_delimiters('', '');
        
        $this->form_validation->set_rules('name', 'Name', ['required', 'trim', 'max_length[50]'], ['required'=>"required"]);
        $this->form_validation->set_rules('dollar_exchange_rate', 'Dollar Exchange Rate', ['numeric'], ['numeric'=>"not a valid exchange rate value"]);

           
        if($this->form_validation->run() !== FALSE){
            /**
             * insert info into db
             * function header: add($f_name, $l_name, $email, $password, $role, $mobile, $addr)
             */
            
            $inserted = $this->unit->addLUnit(set_value('name'), set_value('logo'), set_value('dollar_exchange_rate'), set_value('api_url'));
            
            
            $json = $inserted ? 
                ['status'=>1, 'msg'=>"Unit successfully created"] 
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
    public function updateLUnit(){
        $this->genlib->ajaxOnly();
        
        $this->load->library('form_validation');

        $this->form_validation->set_error_delimiters('', '');
        
        $this->form_validation->set_rules('name', 'Name', ['required', 'trim', 'max_length[50]'], ['required'=>"required"]);
        $this->form_validation->set_rules('dollar_exchange_rate', 'Dollar Exchange Rate', ['numeric'], ['numeric'=>"not a valid exchange rate value"]);
        
        if($this->form_validation->run() !== FALSE){
            /**
             * update info in db
             */
				
            $id = $this->input->post('lUnitId', TRUE);

            $updated = $this->unit->updateLUnit($id, set_value('name'), set_value('logo'), set_value('dollar_exchange_rate'), set_value('api_url'));
            
            
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
    
    public function deleteLUnit(){
        $this->genlib->ajaxOnly();
        
        $unit_id = $this->input->post('_luId');
        // $new_value = $this->genmod->gettablecol('loan_units', 'deleted', 'id', $unit_id) == 1 ? 0 : 1;
        
        $done = $this->unit->deleteLUnit($unit_id);
        
        $json['status'] = $done ? 1 : 0;
        // $json['_nv'] = $new_value;
        $json['_nv'] = 0;
        $json['_luId'] = $unit_id;
        
        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }


 /* --------------------------------------------------------------------------------------- */
 // here's for collateral units
 /**
     * lacu_ = "Load all collateral units"
     */
    public function lacu_(){
        //set the sort order
        $orderBy = $this->input->get('orderBy', TRUE) ? $this->input->get('orderBy', TRUE) : "name";
        $orderFormat = $this->input->get('orderFormat', TRUE) ? $this->input->get('orderFormat', TRUE) : "ASC";
        
        //count the total collateral units in db.
        $totalCUnits = count($this->unit->getAllCUnits());
        
        $this->load->library('pagination');
        
        $pageNumber = $this->uri->segment(3, 0);//set page number to zero if the page number is not set in the third segment of uri
    
        $limit = $this->input->get('limit', TRUE) ? $this->input->get('limit', TRUE) : 10;//show $limit per page
        $start = $pageNumber == 0 ? 0 : ($pageNumber - 1) * $limit;//start from 0 if pageNumber is 0, else start from the next iteration
        
        //call setPaginationConfig($totalRows, $urlToCall, $limit, $attributes) in genlib to configure pagination
        $config = $this->genlib->setPaginationConfig($totalCUnits, "units/lacu_", $limit, ['class'=>'lnp']);
        
        $this->pagination->initialize($config);//initialize the library class
        
        //get all collateral units from db
        $data['allCUnits'] = $this->unit->getAllCUnits($orderBy, $orderFormat, $start, $limit);
        //echo "<script> console.log('All Units: ',",$data['allCUnits'],");</script>";
        $data['range'] = $totalCUnits >= 0 ? ($start+1) . "-" . ($start + count($data['allCUnits'])) . " of " . $totalCUnits : "";
        $data['links'] = $this->pagination->create_links();//page create_links
        $data['sn'] = $start+1;
        
        $json['cUnitTable'] = $this->load->view('admin/units/cunitlist', $data, TRUE);//get view with populated collateral units table

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
    public function addCUnit(){
        $this->genlib->ajaxOnly();
        
        $this->load->library('form_validation');

        $this->form_validation->set_error_delimiters('', '');
        
        $this->form_validation->set_rules('name', 'Name', ['required', 'trim', 'max_length[50]'], ['required'=>"required"]);
        $this->form_validation->set_rules('markup', 'Markup', ['numeric'], ['numeric'=>"not a valid markup value"]);
           
        if($this->form_validation->run() !== FALSE){
            /**
             * insert info into db
             * function header: add($f_name, $l_name, $email, $password, $role, $mobile, $addr)
             */
            
            $inserted = $this->unit->addCUnit(set_value('name'), set_value('logo'), set_value('api_url'), set_value('markup'));
            
            
            $json = $inserted ? 
                ['status'=>1, 'msg'=>"Collateral unit successfully created"] 
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
    public function updateCUnit(){
        $this->genlib->ajaxOnly();
        
        $this->load->library('form_validation');

        $this->form_validation->set_error_delimiters('', '');
        
        $this->form_validation->set_rules('name', 'Name', ['required', 'trim', 'max_length[50]'], ['required'=>"required"]);
        $this->form_validation->set_rules('markup', 'Markup', ['numeric'], ['numeric'=>"not a valid markup value"]);
        
        if($this->form_validation->run() !== FALSE){
            /**
             * update info in db
             */
                
            $id = $this->input->post('cUnitId', TRUE);

            $updated = $this->unit->updateCUnit($id, set_value('name'), set_value('logo'), set_value('api_url'), set_value('markup'));
            
            
            $json = $updated ? 
                    ['status'=>1, 'msg'=>"Collateral unit successfully updated"] 
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
    
    public function deleteCUnit(){
        $this->genlib->ajaxOnly();
        
        $id = $this->input->post('_cuId');
        // $new_value = $this->genmod->gettablecol('collateral_units', 'deleted', 'id', $id) == 1 ? 0 : 1;
        // $this->unit->deleteCUnit($id);
        
        $done = $this->unit->deleteCUnit($id);
        
        $json['status'] = $done ? 1 : 0;
        // $json['_nv'] = $new_value;
        $json['_nv'] = 0;
        $json['_cuId'] = $id;
        
        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }
}