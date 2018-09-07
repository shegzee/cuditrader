<?php
defined('BASEPATH') OR exit('');

/**
 * Description of Bank
 *
 * @author S. Olusegun Ojo <solusegunojo@gmail.com>
 * @date 1st September, 2018
 */
class Bank extends CI_Model{
    public function __construct(){
        parent::__construct();
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
     * @param type $f_name
     * @param type $l_name
     * @param type $email
     * @param type $password
     * @param type $mobile
     * @param type $addr
     * @return boolean
     */
    public function add($name, $description){
        $data = ['name'=>$name, 'description'=>$description];
        
        //set the datetime based on the db driver in use
        // $this->db->platform() == "sqlite3" 
        //         ? 
        // $this->db->set('created_on', "datetime('now')", FALSE) 
        //         : 
        // $this->db->set('created_on', "NOW()", FALSE);
        
        $this->db->insert('banks', $data);
        
        if($this->db->affected_rows() > 0){
            return $this->db->insert_id();
        }
        
        else{
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
     * Get details about a bank
     * @param type $id
     * @return boolean
     */
    public function get_bank_info($id){
        // $this->db->select('id, name, description');
        $this->db->where('id', $id);

        $run_q = $this->db->get('banks');

        if($run_q->num_rows() > 0){
            return $run_q->result();
        }

        else{
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
     * 
     * @param type $orderBy
     * @param type $orderFormat
     * @param type $start
     * @param type $limit
     * @return boolean
     */
    public function getAll($orderBy = "name", $orderFormat = "ASC", $start = 0, $limit = ""){
        // $this->db->select('id, first_name, last_name, email, phone, address, created_on, last_login, active, deleted');
        $this->db->limit($limit, $start);
        $this->db->order_by($orderBy, $orderFormat);
        
        $run_q = $this->db->get('banks');
        
        if($run_q->num_rows() > 0){
            return $run_q->result();
        }
        
        else{
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
    * 
    * @param type $user_id
    * @param type $new_value
    * @return boolean
    */
    public function delete($id, $new_value){       
        $this->db->where('id', $id);
        $this->db->update('banks', ['deleted'=>$new_value]);
       
        if($this->db->affected_rows()){
            return TRUE;
        }

        else{
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
     * 
     * @param type $value
     * @return boolean
     */
    public function bankSearch($value){
        $q = "SELECT * FROM banks WHERE 
                (
                name LIKE '%".$this->db->escape_like_str($value)."%'
                || description LIKE '%".$this->db->escape_like_str($value)."%' 
                )";

        $run_q = $this->db->query($q, [$value, $value]);

        if($run_q->num_rows() > 0){
            return $run_q->result();
        }

        else{
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
    
    public function update($id, $name, $description){
        $data = ['name'=>$name, 'description'=>$description];
        
        $this->db->where('id', $id);
        
        return $this->db->update('banks', $data);
        
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
    
    /**
     * 
     * @param type $f_name
     * @param type $l_name
     * @param type $email
     * @param type $password
     * @param type $mobile
     * @param type $addr
     * @return boolean
     */
    public function addAT($name, $description){
        $data = ['name'=>$name, 'description'=>$description];
        
        //set the datetime based on the db driver in use
        // $this->db->platform() == "sqlite3" 
        //         ? 
        // $this->db->set('created_on', "datetime('now')", FALSE) 
        //         : 
        // $this->db->set('created_on', "NOW()", FALSE);
        
        $this->db->insert('bank_account_types', $data);
        
        if($this->db->affected_rows() > 0){
            return $this->db->insert_id();
        }
        
        else{
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
     * Get details about a bank
     * @param type $id
     * @return boolean
     */
    public function get_bankAT_info($id){
        // $this->db->select('id, name, description');
        $this->db->where('id', $id);

        $run_q = $this->db->get('bank_account_types');

        if($run_q->num_rows() > 0){
            return $run_q->result();
        }

        else{
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
     * 
     * @param type $orderBy
     * @param type $orderFormat
     * @param type $start
     * @param type $limit
     * @return boolean
     */
    public function getAllAT($orderBy = "name", $orderFormat = "ASC", $start = 0, $limit = ""){
        // $this->db->select('id, first_name, last_name, email, phone, address, created_on, last_login, active, deleted');
        $this->db->limit($limit, $start);
        $this->db->order_by($orderBy, $orderFormat);
        
        $run_q = $this->db->get('bank_account_types');
        
        if($run_q->num_rows() > 0){
            return $run_q->result();
        }
        
        else{
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
    * 
    * @param type $user_id
    * @param type $new_value
    * @return boolean
    */
    public function deleteAT($id, $new_value){       
        $this->db->where('id', $id);
        $this->db->update('bank_account_types', ['deleted'=>$new_value]);
       
        if($this->db->affected_rows()){
            return TRUE;
        }

        else{
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
     * 
     * @param type $value
     * @return boolean
     */
    public function bankATSearch($value){
        $q = "SELECT * FROM bank_account_types WHERE 
                -- id != {$_SESSION['user_id']}
                --     AND
                (
                name LIKE '%".$this->db->escape_like_str($value)."%'
                || description LIKE '%".$this->db->escape_like_str($value)."%' 
                )";

        $run_q = $this->db->query($q, [$value, $value]);

        if($run_q->num_rows() > 0){
            return $run_q->result();
        }

        else{
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
    
    public function updateAT($id, $name, $description){
        $data = ['name'=>$name, 'description'=>$description];
        
        $this->db->where('id', $id);
        
        return $this->db->update('bank_account_types', $data);
        
    }
    
   
}
