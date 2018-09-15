<?php
defined('BASEPATH') OR exit('');

/**
 * Description of Bank_model
 *
 * @author Olu Segun <ojosamuelolusegun@gmail.com>
 * @date 30th August, 2018
 */
class Bank_model extends CI_Model
{
    
    public function __construct() 
    {
    	parent::__construct();
    }

    public function add_bank_account($data) 
    {
        $bank_details_id = $this->db->insert('bank_details', $data);
        if ($data['is_primary']) {
            $this->set_as_primary($bank_details_id);
        }
        return $bank_details_id;
    }

    public function set_as_primary($bank_details_id)
    {
        $bank_details = $this->get_bank_details($bank_details_id);
        $this->db->where('user_id', $bank_details->user_id);
        $this->db->update('bank_details', array('is_primary'=>0));

        $this->db->where('id', $bank_details_id);
        return $this->db->update('bank_details', array('is_primary'=>1));

    }

    public function delete($bank_details_id)
    {
        $this->db->where('id', $bank_details_id);
        return $this->db->update('bank_details', array('deleted'=>1));
    }

    public function get_bank_details($id)
    {
        $this->db->where('id', $id);
        $query = $this->db->get('bank_details');

        if($query->num_rows() > 0){
            return $query->row();
        }

        else{
            return FALSE;
        }
    }

    public function get_all_banks()
    {
    	return $this->db->get('banks')->result_array();
    }

    public function get_bank($id)
    {
    	$this->db->where('id', $id);
    	$query = $this->db->get('banks');

    	if($query->num_rows() > 0){
            return $query->result();
        }

        else{
            return FALSE;
        }
    }

    public function get_all_account_types()
    {
    	return $this->db->get('bank_account_types')->result_array();
    }

    public function get_account_type($id)
    {
    	$this->db->where('id', $id);
    	$query = $this->db->get('bank_account_types');

    	if($query->num_rows() > 0){
            return $query->result();
        }

        else{
            return FALSE;
        }
    }

    public function bank_details($user_id) {
    	// $this->db->order_by('account_number', 'DESC');
        $this->db->where('deleted', 0); // this should be everywhere

        $this->db->where('user_id', $user_id);
        $query = $this->db->get('bank_details');

        return $query->result_array();
    }

}