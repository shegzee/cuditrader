<?php
defined('BASEPATH') OR exit('');

/**
 * Description of Loan
 *
 * @author Olusegun <ojosamuelolusegun@gmail.com>
 * @date 10th September, 2018
 */
class Loan extends CI_Model{
    public function __construct(){
        parent::__construct();
        $this->status_numbers = $this->load_status_numbers();
    }

    private function load_status_numbers() {
        // $this->db->like('status', $status);
        // $this->db->start_cache();
        // $this->db->select('status_number');
        // $this->db->stop_cache();
        $query = $this->db->get('loan_status');
        $result = $query->result();
        return $result;
    }

    
    /*
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    */
    
    /* ********************************************************************

    admin end functions

    
    will be implemented on admin end.
    */

    /*
    get status number for status given as string
    */
    public function get_status_number($status)
    {
        foreach ($this->status_numbers as $value) {
            # code...
            $this_status = $value->status;
            if (stristr($status, strtolower($this_status))) {
                return $value->status_number;
            }
        }
        return -1;
    }

    /*
    get status text for given status number. It's the inverse of $this->get_status_number() up there ^
    */
    public function get_status_text($status_number)
    {
        foreach ($this->status_numbers as $value) {
            # code...
            $this_status_number = $value->status_number;
            if ($status_number == $this_status_number) {
                return $value->status;
            }
        }
        return "";
    }


    /*
    this function assigns an admin as a manager for a loan.
    adds an entry (`loan_id`, `admin_id`) to table `loans_managers`, where such doesn't exist

    ALSO:
    set assigned_on
    set assigned_by_id => to current admin
    */
    public function assign_admin_to_loan($loan_id, $manager_id, $admin_id)
    {
        if ($this->is_assigned_to_loan($manager_id, $loan_id)) {
            return TRUE;
        }
        $this->db->set('loan_id',           $loan_id); // the loan
        $this->db->set('admin_id',          $manager_id); // the manager
        $this->db->set('assigned_by_id',    $admin_id); // whodunit

        $this->db->platform() == "sqlite3" 
                ? 
        $this->db->set('assigned_on', "datetime('now')", FALSE) 
                : 
        $this->db->set('assigned_on', "NOW()", FALSE);
        return $this->db->insert('loans_managers');
    }

    /*
    this will check for all admins assigned to the loan
    */
    public function assigned_admin_ids($loan_id)
    {
        $this->db->select('admin_id');
        $this->db->where('loan_id', $loan_id);
        $query = $this->db->get('loans_managers');

        return array_values($query->result_array());
    }

    /*
    check if the admin is assigned to this loan, to determine if admin can make changes
    this will not be done yet, though

    add a flag: enforce_managers in `settings`.
    */
    public function is_assigned_to_loan($admin_id, $loan_id)
    {
        // if (in_array($admin_id, assigned_admin_ids($loan_id))) {
        //  return TRUE;
        // }
        // return FALSE;
        return TRUE;
    }

    public function update_status($loan_id, $status, $data=[])
    {
        $status_number = $this->get_status_number($status);
        if ($status_number) {
            $this->db->where('id', $loan_id);
            $this->db->set('status_number', $status_number);
            return $this->db->update('loans');
        }
        else {
            return FALSE;
        }
    }
    
    /*
    admin approves loan
    *******************
    change STATUS
    set approve_date
    set approver_id
    */
    public function approve_loan($loan_id, $admin_id, $new_value)
    {
        if (!$this->is_assigned_to_loan($admin_id, $loan_id)) {
            return FALSE;
        }
        $this->db->set('approved_by_id', $admin_id);
        
        $this->db->platform() == "sqlite3" 
                ? 
        $this->db->set('approved_on', "datetime('now')", FALSE) 
                : 
        $this->db->set('approved_on', "NOW()", FALSE);
        return $this->update_status($loan_id, "APPROVED");
    }

    /*
    admin denies loan
    *******************
    change STATUS
    set approve_date: can use "approved_on", since it's at same stage
    set approver_id
    */
    public function deny_loan($loan_id, $admin_id)
    {
        if (!$this->is_assigned_to_loan($admin_id, $loan_id)) {
            return FALSE;
        }
        $this->db->set('approved_by_id', $admin_id);

        $this->db->platform() == "sqlite3" 
                ? 
        $this->db->set('approved_on', "datetime('now')", FALSE) 
                : 
        $this->db->set('approved_on', "NOW()", FALSE);
        return $this->update_status($loan_id, "DENIED");
    }

    /*
    admin reverts loan to pending status
    *******************
    change STATUS
    set approve_date to 0
    set approver_id to ""
    */
    public function revert_loan($loan_id, $admin_id)
    {
        if (!$this->is_assigned_to_loan($admin_id, $loan_id)) {
            return FALSE;
        }
        $this->db->set('approved_by_id', "");
        $this->db->set('approved_on', 0);

        // $this->db->platform() == "sqlite3" 
        //         ? 
        // $this->db->set('approved_on', "datetime('now')", FALSE) 
        //         : 
        // $this->db->set('approved_on', "NOW()", FALSE);
        return $this->update_status($loan_id, "PENDING");
    }

    /*
    a granted loan is no different from an approved loan on the user end;
    only difference is that an approved loan shows to the admin that, though approved, money has not been sent

    This method is called after loan has been granted: that is, actual money has been sent
    possibly notifies user?
    *************************
    change STATUS
    // set grant_date
    // set granter_id

    */
    public function grant_loan($loan_id, $admin_id)
    {
        if (!$this->is_assigned_to_loan($admin_id, $loan_id)) {
            return FALSE;
        }
        $this->db->set('granted_by_id', $admin_id);

        $this->db->platform() == "sqlite3" 
                ? 
        $this->db->set('granted_on', "datetime('now')", FALSE) 
                : 
        $this->db->set('granted_on', "NOW()", FALSE);
        return $this->update_status($loan_id, "GRANTED");
    }

    /*
    change STATUS
    set clear_date
    set clearer_id
    */
    public function clear_loan($loan_id, $admin_id)
    {
        if (!$this->is_assigned_to_loan($admin_id, $loan_id)) {
            return FALSE;
        }
        $this->db->set('cleared_by_id', $admin_id);

        $this->db->platform() == "sqlite3" 
                ? 
        $this->db->set('cleared_on', "datetime('now')", FALSE) 
                : 
        $this->db->set('cleared_on', "NOW()", FALSE);
        return $this->update_status($loan_id, "CLEARED");
    }


    /*
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    */
    
    /**
     * Get some details about a loan
     * @param type $id
     * @return boolean
     */
    public function get_loan_info($loan_id){
        // $this->db->select('id, first_name, last_name, role');
        $this->db->where('id', $loan_id);

        $run_q = $this->db->get('loans');

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
    public function getAll($status_number = 0, $orderBy = "date_requested", $orderFormat = "ASC", $start = 0, $limit = ""){
        // if ($status_number) {
        //     $this->db->where('status_number', $status_number);
        // }
        // $this->db->select('id, first_name, last_name, email, role, mobile1, mobile2, created_on, last_login, account_status, deleted');
        $this->db->limit($limit, $start);
        $this->db->order_by($orderBy, $orderFormat);
        $query_text = "SELECT `loans`.*, `loan_status`.`status`, `users`.`first_name`, `users`.`last_name`, `users`.`email` FROM `loans` JOIN `users` ON `users`.`id`=`loans`.`user_id` JOIN `loan_status` ON `loan_status`.`status_number`=`loans`.`status_number`";
        if (!is_array($status_number)) {
            if ($status_number) {
                $query_text .= " WHERE `loans`.`status_number`=".$status_number.";";
            }
            else {
                $query_text .= ";";
            }
        } else {
            $query_text .= " WHERE `loans`.`status_number`=0 ";
            foreach ($status_number as $status) {
                $query_text .= " OR `loans`.`status_number`=".$status;
            }

            $query_text .= " ORDER BY ".$orderBy." ".$orderFormat.";";
        }
        // join with users table to get user names
        // $this->db->join('users', 'users.id = loans.user_id', 'left');
        // $this->db->join('loan_status', 'loan_status.status_number = loans.status_number', 'left');

        // $run_q = $this->db->get('loans');
        $run_q = $this->db->query($query_text);
        
        if($run_q->num_rows() > 0){
            return $run_q->result();
        }
        
        else{
            return FALSE;
        }
    }
    
    
    
    /**
     * 
     * @param type $value
     * @return boolean
     */
    public function loanSearch($value){
        $q = "SELECT * FROM loans WHERE 
                (
                MATCH(amount) AGAINST(?)
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
    
    public function update($loan_id, $user_id, $status_number, $loan_unit_id, $loan_amount, $collateral_unit_id, $collateral_amount, $duration){
        $data = ['user_id'=>$user_id, 'status_number'=>$status_number, 'loan_unit_id'=>$loan_unit_id, 'loan_amount'=>$loan_amount, 'collateral_unit_id'=>$collateral_unit_id, 'collateral_amount'=>$collateral_amount, 'loan_duration'=>$duration];
        
        $this->db->where('id', $loan_id);
        
        $this->db->update('loans', $data);
        
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
