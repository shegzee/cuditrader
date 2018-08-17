<?php
defined('BASEPATH') OR exit('');

/**
 * Description of User
 *
 * @author Olu Segun <ojosamuelolusegun@gmail.com>
 * @date 13th August, 2018
 */
class User_model extends CI_Model{
    public function __construct(){
        parent::__construct();
        $this->load->library('ion_auth');
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
     * @param type $role
     * @param type $mobile1
     * @param type $mobile2
     * @return boolean
     */
    public function add($username, $password, $email, $first_name, $last_name){

        $additional_data = array (
                'first_name' => $first_name,
                'last_name' => $last_name
            );
        // $this->load->library('ion_auth');
        return $this->ion_auth->register($username, $password, $email, $additional_data);
    }

    public function login($username, $password, $remember=FALSE) {
        return $this->ion_auth->login($username, $password, $remember);
    }
    
    public function get_profile($user_id) {
        $this->db->where('user_id', $user_id);
        $run_q = $this->db->get('user_profile');

        if ($run_q->num_rows == 0) {
            return FALSE;
        }
        else {
            return $run_q->result();
        }
    }

    public function edit_user_profile($user_id, $details) {
        if ($this->user_profile($user_id)) {
            // do edit
        }
        else {
            // de create
        }
    }

    public function set_profile_picture($user_id, $file_name) {
        $this->db->where('user_id', $user_id);
        $this->db->update('user_profile', ['picture'=>$file_name]);
       
        if($this->db->affected_rows()){
            return TRUE;
        }

        else{
            return FALSE;
        }
    }

    public function bank_accounts($user_id) {

    }

    /*
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    */

    /**
     * Get some details about an user (stored in session)
     * @param type $email
     * @return boolean
     */
    public function get_user_info($email){
        $this->db->select('id, first_name, last_name, role');
        $this->db->where('email', $email);

        $run_q = $this->db->get('user');

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
    public function getAll($orderBy = "first_name", $orderFormat = "ASC", $start = 0, $limit = ""){
        $this->db->select('id, first_name, last_name, email, mobile1, mobile2, created_on, last_login, account_status, deleted');
        $this->db->where("id != ", $_SESSION['user_id']);
        $this->db->where("email != ", "admin@cuditrader.com");//added to prevent people from removing the demo user account
        $this->db->limit($limit, $start);
        $this->db->order_by($orderBy, $orderFormat);
        
        $run_q = $this->db->get('user');
        
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
    * @param type $new_status New account status
    * @return boolean
    */ 
    public function suspend($user_id, $new_status){       
        $this->db->where('id', $user_id);
        $this->db->update('user', ['account_status'=>$new_status]);

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
    * @param type $user_id
    * @param type $new_value
    * @return boolean
    */
    public function delete($user_id, $new_value){       
        $this->db->where('id', $user_id);
        $this->db->update('user', ['deleted'=>$new_value]);
       
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
    public function userSearch($value){
        $q = "SELECT * FROM user WHERE 
                id != {$_SESSION['user_id']}
                    AND
                (
                MATCH(first_name) AGAINST(?)
                || MATCH(last_name) AGAINST(?)
                || MATCH(first_name, last_name) AGAINST(?)
                || MATCH(email) AGAINST(?)
                || MATCH(mobile1) AGAINST(?)
                || MATCH(mobile2) AGAINST(?)
                || first_name LIKE '%".$this->db->escape_like_str($value)."%'
                || last_name LIKE '%".$this->db->escape_like_str($value)."%' 
                || email LIKE '%".$this->db->escape_like_str($value)."%'
                || mobile1 LIKE '%".$this->db->escape_like_str($value)."%'
                || mobile2 LIKE '%".$this->db->escape_like_str($value)."%'
                )";

        $run_q = $this->db->query($q, [$value, $value, $value, $value, $value, $value]);

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
    
    public function update($user_id, $first_name, $last_name, $email, $mobile1, $mobile2, $role){
        $data = ['first_name'=>$first_name, 'last_name'=>$last_name, 'mobile1'=>$mobile1, 'mobile2'=>$mobile2, 'email'=>$email, 
            'role'=>$role];
        
        $this->db->where('id', $user_id);
        
        $this->db->update('user', $data);
        
        return TRUE;
    }
    
    
    /*
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    */
    
    
   
}
