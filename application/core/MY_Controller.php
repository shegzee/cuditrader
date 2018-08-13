<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
class MY_Controller extends CI_Controller {
 
    protected $data = array();
    function __construct()
    {
        parent::__construct();
        $this->load->library('ion_auth');

        $this->data['page_title'] = 'Cudi Trader';
        $this->data['page_description'] = 'Cudi Trader';
        $this->data['before_closing_head'] = '';
        $this->data['before_closing_body'] = '';
    }
 
    protected function render($the_view = NULL, $template = 'main')
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
}

class Auth_Controller extends MY_Controller {
    function __construct() {
        parent::__construct();
        if ($this->ion_auth->logged_in() === FALSE) {
            redirect('user/login');
        }
    }

    protected function render($the_view = NULL, $template = 'main') {
        parent::render($the_view, $template);
    }
}