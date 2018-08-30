<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
class MY_Controller extends CI_Controller {
 
    protected $data = array();
    function __construct()
    {
        parent::__construct();
        $this->load->library('ion_auth');

        if ($this->ion_auth->logged_in()) {
            $this->load->model('User_model');
            $this->user = $this->ion_auth->user()->row();
            $this->user->profile = $this->User_model->get_profile($this->user->id)->row();
            $this->data['user'] = $this->user;
        }
        else {
            $user = FALSE;
        }

        
        $this->data['page_title'] = 'Cudi Trader';
        $this->data['page_description'] = 'Cudi Trader';
        $this->data['before_closing_head'] = '';
        $this->data['before_closing_body'] = '';
    }
 
    protected function render($the_view = NULL, $template = 'pages_template')
    {
        if($template == 'json' || $this->input->is_ajax_request())
        {
            header('Content-Type: application/json');
            echo json_encode($this->data);
        }
        elseif(is_null($template))
        {
            $this->load->view($the_view, $this->data);
        }
        else
        {
            $this->data['page_content'] = (is_null($the_view)) ? '' : $this->load->view($the_view, $this->data, TRUE);
            $this->data['current_user'] = $this->ion_auth->user()->row();
            $this->load->view('templates/' . $template . '', $this->data);
        }
    }

    /* creates an array of all items in a table, indexed by a specified $key_field */

    protected function compose_array($table_name, $value_fields=FALSE, $key_field='id') {
        if ($value_fields !== FALSE) {
            $this->db->select($key_field.",".$value_fields);
        }
        $query = $this->db->get($table_name);
        
        $result_array = $query->result_array();

        foreach ($result_array as $item) {
            $result[$item[$key_field]] = $item;
        }

        return $result;
    }

    /*
    Creates items for use in a dropdown (to be used in form_dropdown())

    $table_name: the name of the table
    $key_field: the key
    $value_field: user friendly display data
    $blank_first: make the first item in the dropdown blank
    $order_by: order by
    $direction: direction for order by
    */
    protected function prep_select($table_name, $key_field, $value_field, $blank_first=FALSE, $order_by="", $direction="ASC") {
        // prepopulate select
        if ($blank_first) {
            $result = array(""=>"");
        }
        else {
            $result = array();
        }
        $this->db->order_by($order_by, $direction);
        $this->db->select("$key_field, $value_field");
        $query = $this->db->get($table_name);
        
        $result_array = $query->result_array();

        
        foreach ($result_array as $item) {
            $result[$item[$key_field]] = $item[$value_field];
        }

        return $result;
    }
}

class Auth_Controller extends MY_Controller {
    // var $user;
    function __construct() {
        parent::__construct();
        if ($this->ion_auth->logged_in() === FALSE) {
            redirect('user/login');
        }
        else {
            $this->load->model('User_model');
            $this->user = $this->ion_auth->user()->row();
            $this->user->profile = $this->User_model->get_profile($this->user->id)->row();
            $this->data['user'] = $this->user;
            if (!$this->user->active) {
                $this->ion_auth->logout();
                redirect('home');
            }
        }
    }

    protected function render($the_view = NULL, $template = 'pages_template') {
        parent::render($the_view, $template);
    }

    // protected function prep_select($table_name, $key_field, $value_field) {
    //     parent::prep_selects($table_name, $key_field, $value_field);
    // }
}
