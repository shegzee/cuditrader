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
    public function add($email, $password, $first_name, $last_name, $phone="", $address=""){

        $additional_data = array (
                'first_name' => $first_name,
                'last_name' => $last_name,
                'phone' => $phone,
                'address' => $address
            );
        $user_id = $this->ion_auth->register($email, $password, $email, $additional_data);
        // create an entry in the 'user_profile' table for this user
        if ($user_id) {
            $this->db->insert('user_profile', array("user_id" => $user_id));
        }
        return $user_id;
    }

    public function login($email, $password, $remember=FALSE) {
        return $this->ion_auth->login($email, $password, $remember);
    }
    
    public function get_profile($user_id) {
        $this->db->where('user_id', $user_id);
        $run_q = $this->db->get('user_profile');

        if (!isset($run_q)) {
            return FALSE;
        }
        else {
            // return $run_q->row();
            return $run_q;
        }
    }

    public function update_user_details($user_id, $user_data, $user_profile_data) {
        // do edit all user details
        $this->update_user($user_id, $user_data);
        // $this->update_user_profile($user_id, $user_profile_data);

        if($this->db->affected_rows()){
            return TRUE;
        }

        else{
            return FALSE;
        }
    }

    public function update_user($user_id, $details) {
        // do edit main user table
        $this->db->where('id', $user_id);
        $this->db->update('users', $details);

        if($this->db->affected_rows()){
            return TRUE;
        }

        else{
            return FALSE;
        }
    }

    public function update_user_profile($user_id, $details) {
        // do edit user profile table
        $this->db->where('user_id', $user_id);
        $this->db->update('user_profile', $details);

        if($this->db->affected_rows()){
            return TRUE;
        }

        else{
            return FALSE;
        }
    }

    public function update_profile_picture($user_id, $file_name) {
        return $this->update_user_profile($user_id, array('picture' => $file_name));
    }

    public function bank_details($user_id) {
        $this->db->where('user_id', $user_id);
        $query = $this->db->get('bank_details');

        return $query->result_array();
    }

    /* *********************************
    METHODS FOR COLLATERAL PAGE
    
    collaterals in custody of cudi (type)
    collaterals returned (type)
    collaterals traded in all (type)
    */
    public function get_present_collaterals($user_id, $collateral_unit_id) {
        $this->db->where('status_number', $this->get_status_number("GRANTED"));
        // $this->db->orwhere('status_number', $this->get_status_number("APPROVED"));
        $this->db->where('user_id', $user_id);
        $this->db->where('collateral_unit_id', $collateral_unit_id);
        $this->db->select('id', 'collateral_amount');
        return $this->db->get('loans')->result();
    }

    /*
    get status number for status given as string
    */
    public function get_status_number($status)
    {
        $this->db->like('status', $status);
        // $this->db->start_cache();
        $this->db->select('status_number');
        // $this->db->stop_cache();
        $query = $this->db->get('loan_status');
        $result = $query->row();
        return $result ? $result->status_number : -1;
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
    public function get_user_info_from_email($email){
        $this->db->select('id, first_name, last_name');
        $this->db->where('email', $email);

        $run_q = $this->db->get('users');

        if($run_q->num_rows() > 0){
            return $run_q->row();
        }

        else{
            return FALSE;
        }
    }

    /**
     * Get some details about an user (stored in session)
     * @param type $email
     * @return boolean
     */
    public function get_user_info_from_username($username){
        $this->db->select('id, first_name, last_name, email');
        $this->db->where('username', $username);

        $run_q = $this->db->get('users');

        if($run_q->num_rows() > 0){
            return $run_q->row();
        }

        else{
            return FALSE;
        }
    }

    public function profile_picture_url($user_id) {
        $picture = $this->get_profile($user_id)->row()->picture;
        if ($picture === "" || ! file_exists(FCPATH.'uploads/profile_pictures/'.$picture)) {
            $picture = "no_pic.png";
            // $picture = FCPATH.'uploads/profile_pictures/'.$picture;
        }
        return base_url('uploads/profile_pictures/').$picture;
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
        $this->db->select('id, first_name, last_name, email, phone, address, created_on, last_login, active, deleted');
        $this->db->where("id != ", $_SESSION['user_id']);
        $this->db->where("email != ", "admin@cuditrader.com");//added to prevent people from removing the demo user account
        $this->db->limit($limit, $start);
        $this->db->order_by($orderBy, $orderFormat);
        
        $run_q = $this->db->get('users');
        
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
        $this->db->update('users', ['active'=>$new_status]);

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
        $this->db->update('users', ['deleted'=>$new_value]);
       
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
        $q = "SELECT * FROM users WHERE 
                id != {$_SESSION['user_id']}
                    AND
                (
                MATCH(first_name) AGAINST(?)
                || MATCH(last_name) AGAINST(?)
                || MATCH(first_name, last_name) AGAINST(?)
                || MATCH(email) AGAINST(?)
                || MATCH(phone) AGAINST(?)
                || MATCH(address) AGAINST(?)
                || first_name LIKE '%".$this->db->escape_like_str($value)."%'
                || last_name LIKE '%".$this->db->escape_like_str($value)."%' 
                || email LIKE '%".$this->db->escape_like_str($value)."%'
                || phone LIKE '%".$this->db->escape_like_str($value)."%'
                || address LIKE '%".$this->db->escape_like_str($value)."%'
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
    
    public function update($user_id, $first_name, $last_name, $email, $phone, $address){
        $data = ['first_name'=>$first_name, 'last_name'=>$last_name, 'phone'=>$phone, 'address'=>$address, 'email'=>$email];
        
        $this->db->where('id', $user_id);
        
        $this->db->update('users', $data);
        
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
