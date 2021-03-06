<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Description of Search
 *
 * @author Amir <amirsanni@gmail.com>
 * @date 26th Rab.Awwal, 1437A.H (Jan. 7th, 2016)
 */

class Search extends CI_Controller{
    protected $value;
    
    public function __construct() {
        parent::__construct();
        
        //$this->gen->checklogin();
        
        $this->genlib->ajaxOnly();
        
        $this->load->model(['admin/transaction', 'admin/item', 'admin/bank', 'admin/user']);
        
        $this->load->helper('text');
        
        $this->value = $this->input->get('v', TRUE);
    }
    
    /*
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    */
    
    
    public function index(){
        /**
         * function will call models to do all kinds of search just to check whether there is a match for the searched value
         * in the search criteria or not. This applies only to global search
         */
        
        
        
        //set final output
        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }
    
    
    /*
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    */
    
    
    /*
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    */
    
    
    /*
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    */
    
    
    public function itemSearch(){
        $data['allItems'] = $this->item->itemsearch($this->value);
        $data['sn'] = 1;
        
        $json['itemsListTable'] = $data['allItems'] ? $this->load->view('admin/items/itemslisttable', $data, TRUE) : "No match found";
        
        //set final output
        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }
    
    /*
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    */
    
    
    
    public function transSearch(){
        $data['allTransactions'] = $this->transaction->transsearch($this->value);
        $data['sn'] = 1;
        
        $json['transTable'] = $data['allTransactions'] ? $this->load->view('admin/transactions/transtable', $data, TRUE) : "No match found";
        
        //set final output
        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }
    
    
    /*
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    */
    
    public function otherSearch(){
        
        
        //set final output
        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }

    public function bankSearch(){
        $data['allBanks'] = $this->bank->banksearch($this->value);
        $data['sn'] = 1;
        
        $json['bankTable'] = $data['allBanks'] ? $this->load->view('admin/banks/banklist', $data, TRUE) : "No match found";
        
        //set final output
        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }
    
    public function accountTypeSearch(){
        $data['allBankATs'] = $this->bank->bankATSearch($this->value);
        $data['sn'] = 1;
        
        $json['bankATTable'] = $data['allBankATs'] ? $this->load->view('admin/banks/bankATlist', $data, TRUE) : "No match found";
        
        //set final output
        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }

    public function userSearch(){
        $data['allUsers'] = $this->user->usersearch($this->value);
        $data['sn'] = 1;
        
        $json['userTable'] = $data['allUsers'] ? $this->load->view('admin/users/userlist', $data, TRUE) : "No match found";
        
        //set final output
        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }
    

    
    /*
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    */
    
    /*
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    */
}
