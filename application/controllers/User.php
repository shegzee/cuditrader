<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends Auth_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->library('ion_auth');
		$this->load->model('User_model');
	}

	public function index()
	{
		$this->render('pages/home');
	}

	public function logout()
	{
		$this->ion_auth->logout();
		redirect('user/login');
	}

	public function profile()
	{
		$current_user = $this->ion_auth->user()->row();
		$this->data['user'] = $current_user;
		$this->data['user_profile'] = $this->User_model->get_profile($current_user->id);
		$this->render('user/profile');
		
	}

	public function edit_profile()
	{
		$current_user = $this->ion_auth->user()->row();
		$this->data['user'] = $current_user;
		$this->data['user_profile'] = $this->User_model->get_profile($current_user->id);

		$this->load->library('form_validation');
		$this->form_validation->set_rules('first_name', 'First name', 'trim|required');
		$this->form_validation->set_rules('last_name', 'Last name', 'trim|required');
		$this->form_validation->set_rules('username', 'Username', 'trim|required|is_unique[users.username]');
		$this->form_validation->set_rules('email', 'Email', 'trim|valid_email|required|is_unique[users.email]');

		if ($this->form_validation->run()===FALSE)
		{
			$this->load->helper('form');
			$this->render('user/edit_profile');
		}
		else
		{

		}
	}

	public function upload_profile_picture()
    {
        $config['upload_path']          = './uploads/profile_pictures/';
        $config['allowed_types']        = 'gif|jpg|png';
        $config['max_size']             = 100;
        $config['max_width']            = 1024;
        $config['max_height']           = 768;
        $config['encrypt_name']			= TRUE;

        $this->load->library('upload', $config);

        if ( ! $this->upload->do_upload('userfile'))
        {
            $this->data['error'] = array('error' => $this->upload->display_errors());
        }
        else
        {
            $data['upload_data'] = array('upload_data' => $this->upload->data());
            $this->User_model->set_profile_picture();
        }
        $this->render('user/edit_profile');
    }

}
