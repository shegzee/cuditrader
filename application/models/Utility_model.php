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

    /*
    Gets the price, in dollars, of collateral unit
    */
    public function get_collateral_unit_price($collateral_unit_id) {
        // TODO: get value from collateral_unit.api_url later.
        // but, for now, from static value in database
        $collateral_unit_api_url = $this->get_collateral_unit_api_url($collateral_unit_id);
        // $json = $this->call_API('GET', $collateral_unit_api_url);

        // $data = json_decode($json);

        // return $data[0]->price_usd;
        // return $data;
        // return $json;
        // $prices = array(1=>6664.36, 2=>209.91);
        // return $prices[$collateral_unit_id];
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $collateral_unit_api_url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);  
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Accept: application/json',
            'Content-Type: application/json')
        );

        $result = curl_exec($curl);
        if ($result === false) {
            $result = curl_error($curl);
            $result = "connection error";
            return $result;
        }
        // $result = json_encode(curl_getinfo($curl));
        curl_close($curl);

        // return $result;

        $data = json_decode($result);

        return $data[0]->price_usd;
        // return $data;
        // return $collateral_unit_api_url;
    }

    /*
    Gets the exchange rate per dollar for loan unit
    */
    public function get_exchange_rate($loan_unit_id) {
        // $rates = array(1=>363);
        // return $rates[$loan_unit_id];

        $this->db->select("dollar_exchange_rate");
        $this->db->where("id", $loan_unit_id);
        $query = $this->db->get("loan_units");

        return $query->row()->dollar_exchange_rate;
    }

    public function get_collateral_unit($collateral_unit_id) {
        $this->db->where("id", $collateral_unit_id);
        $query = $this->db->get("collateral_units");

        return $query->row();
    }

    /*
    Gets markup for collateral (in percent)
    */  
    public function get_markup($collateral_unit_id) {
        $this->db->select("markup");
        $this->db->where("id", $collateral_unit_id);
        $query = $this->db->get("collateral_units");

        return $query->row()->markup;

        // $markup = array(1=>25, 2=>25);
        // return $markup[$collateral_unit_id];
    }

    public function get_collateral_unit_api_url($collateral_unit_id) {
        $this->db->select("api_url");
        $this->db->where("id", $collateral_unit_id);
        $query = $this->db->get("collateral_units");

        return $query->row()->api_url;

        // test code
        // $urls = array(1=>"https://api.coinmarketcap.com/v1/ticker/bitcoin/", 2=>"https://api.coinmarketcap.com/v1/ticker/ethereum/");
        // return $urls[$collateral_unit_id];

    }


    function call_API($method, $url, $data = false) {
        $curl = curl_init();

        switch ($method)
        {
            case "POST":
                curl_setopt($curl, CURLOPT_POST, 1);

                if ($data)
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                break;
            case "PUT":
                curl_setopt($curl, CURLOPT_PUT, 1);
                break;
            default:
                if ($data)
                    $url = sprintf("%s?%s", $url, http_build_query($data));
        }

        // Optional Authentication:
        // curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        // curl_setopt($curl, CURLOPT_USERPWD, "username:password");

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);  
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Accept: application/json',
            'Content-Type: application/json')
        );

        $result = curl_exec($curl);

        curl_close($curl);

        return $result;
    }

}