<?php
defined('BASEPATH') OR exit('');

/**
 *
 * @author Olu Segun <ojosamuelolusegun@gmail.com>
 * @date 9th October, 2018
 */
class Utility_model extends CI_Model{
    public function __construct(){
        parent::__construct();
        // $this->load->library('ion_auth');
    }

    public function prep_select_data($table_name, $key_field, $value_field, $order_by="", $direction="ASC") {
    	$this->db->order_by($order_by, $direction);
        $this->db->select("$key_field, $value_field");
        $query = $this->db->get($table_name);
        
        $result_array = $query->result_array();

        return $result_array;
    }

    public function get_setting($setting) {
    	$this->db->select("value");
    	$this->db->where("setting", $setting);
        $query = $this->db->get("settings");
        
        return $query->row()->value;
    }

    public function get_all_settings() {
    	return $this->prep_select_data("settings", "setting", "value");
    }

}