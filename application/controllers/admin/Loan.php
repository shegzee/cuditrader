<?php
defined('BASEPATH') OR exit('');

/**
 * Loan Model
 *
 * @author Olu Segun <ojosamuelolusegun@gmail.com>
 * @date 7th September, 2018
 */
class Loan extends CI_Model
{
    
    public function __construct() 
    {
    	parent::__construct();
    }

    // public function new_loan($data) 
    // {
    // 	if (has_loan_with_status($data['user_id'], "PENDING")) {
    // 		return FALSE;
    // 	}
    // 	$data['status_number'] = $this->get_status_number("PENDING");

    // 	// $data['requested_on'] = time();
    // 	$this->db->platform() == "sqlite3" 
    //             ? 
    //     $this->db->set('requested_on', "datetime('now')", FALSE) 
    //             : 
    //     $this->db->set('requested_on', "NOW()", FALSE);
    // 	return $this->db->insert('loans', $data);
    // }

    // public function cancel_loan($loan_id, $user_id)
    // {
    // 	$pending_status_number = $this->get_status_number("PENDING");
    // 	$cancelled_status_number = $this->get_status_number("CANCELLED");


    // 	$this->db->set('status_number', $cancelled_status_number);
    // 	$this->db->where('user_id', $user_id);
    // 	$this->db->where('id', $loan_id);
    // 	$this->db->where('status_number', $pending_status_number);
    	
    // 	$this->db->platform() == "sqlite3" 
    //             ? 
    //     $this->db->set('approved_on', "datetime('now')", FALSE) 
    //             : 
    //     $this->db->set('approved_on', "NOW()", FALSE);
    	
    // 	return $this->db->update('loans');
    // }

    // public function has_loan_with_status($user_id, $status)
    // {
    // 	if (get_loans($user_id, $status)) {
    // 		return TRUE;
    // 	}
    // 	return FALSE;
    // }

    // public function get_loans($user_id, $status="ALL")
    // {
    // 	if ($status != "ALL") {
    // 		$status_number = $this->get_status_number($status);
    // 		$this->db->where('status_number', $status_number);
    // 	}
    // 	$this->db->where('user_id', $user_id);
    // 	$query = $this->db->get('loans');
    // 	return $query->result();
    // }


    // public function get_loan($loan_id, $user_id)
    // {
    // 	// $this->db->where('user_id', $user_id);
    // 	// $this->db->where('loan_id', $loan_id);
    // 	$query = $this->db->get_where('loans', array('user_id'=>$user_id, 'id'=>$loan_id));
    // 	return $query->row();
    // }

    // public function update_loan($loan_id, $data)
    // {
    // 	$this->db->where('id', $loan_id);
    // 	$this->db->update('loans', $data);
    // }

    /* ********************************************************************

	admin end functions

	
	will be implemented on admin end.
	*/

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
	this function assigns an admin as a manager for a loan.
	adds an entry (`loan_id`, `admin_id`) to table `loans_managers`, where such doesn't exist

	ALSO:
	set assigned_on
	set assigned_by_id => to current admin
	*/
	public function assign_admin_to_loan($loan_id, $manager_id, $admin_id)
	{
		if (is_assigned_to_loan($manager_id, $loan_id)) {
			return TRUE;
		}
		$this->db->set('loan_id', 			$loan_id); // the loan
		$this->db->set('admin_id', 			$manager_id); // the manager
		$this->db->set('assigned_by_id', 	$admin_id); // whodunit

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
		// 	return TRUE;
		// }
		// return FALSE;
		return TRUE;
	}

	public function update_status($loan_id, $status, $data=[])
	{
		$status_number = $this->get_status_number($status);
		if ($status_number) {
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
	public function approve_loan($loan_id, $admin_id)
	{
		$this->db->set('approved_by_id', $admin_id);
		
		$this->db->platform() == "sqlite3" 
                ? 
        $this->db->set('approved_on', "datetime('now')", FALSE) 
                : 
        $this->db->set('approved_on', "NOW()", FALSE);

		if (is_assigned_to_loan($admin_id, $loan_id)) {
			return update_status($loan_id, "APPROVED");
		}
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
		$this->db->set('approved_by_id', $admin_id);

		$this->db->platform() == "sqlite3" 
                ? 
        $this->db->set('approved_on', "datetime('now')", FALSE) 
                : 
        $this->db->set('approved_on', "NOW()", FALSE);

		if (is_assigned_to_loan($admin_id, $loan_id)) {
			return update_status($loan_id, "DENIED");
		}
	}

	/*
	a granted loan is no different from an approved loan on the user end;
	only difference is that an approved loan shows to the admin that, though approved, money has not been sent

	This method is called after loan has been granted: that is, actual money has been sent
	*************************
	change STATUS
	// set grant_date
	// set granter_id

	*/
	public function grant_loan($loan_id, $admin_id)
	{
		$this->db->set('granted_by_id', $admin_id);

		$this->db->platform() == "sqlite3" 
                ? 
        $this->db->set('granted_on', "datetime('now')", FALSE) 
                : 
        $this->db->set('granted_on', "NOW()", FALSE);

		if (is_assigned_to_loan($admin_id, $loan_id)) {
			return update_status($loan_id, "GRANTED");
		}
	}

	/*
	change STATUS
	set clear_date
	set clearer_id
	*/
	public function clear_loan($loan_id, $admin_id)
	{
		$this->db->set('cleared_by_id', $admin_id);

		$this->db->platform() == "sqlite3" 
                ? 
        $this->db->set('cleared_on', "datetime('now')", FALSE) 
                : 
        $this->db->set('cleared_on', "NOW()", FALSE);

		if (is_assigned_to_loan($admin_id, $loan_id)) {
			return update_status($loan_id, "CLEARED");
		}
	}


}
