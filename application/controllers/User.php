<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->library('ion_auth');
		$this->load->model('User_model');
	}

	public function index()
	{
		$this->render('pages/home');
		// $this->load->view('pages/home');
	}

	public function login()
	{
		$this->data['page_title'] = "Login";

		$this->load->library('form_validation');
		$this->form_validation->set_rules('username', 'Username', 'trim|required');
		$this->form_validation->set_rules('password', 'Password', 'trim|required');
		// $this->data['message'] = "login form here";
		if ($this->form_validation->run() === FALSE)
		{
			$this->render('user/login');
		}
		else
		{
			$remember = (bool) $this->input->post('remember');
			$username = $this->input->post('username');
			$password = $this->input->post('password');

			if ($this->ion_auth->login($username, $password, $remember))
			{
				redirect('home'); // decide
			}
			else
			{
				$_SESSION['auth_message'] = $this->ion_auth->errors();
				$this->session->mark_as_flash('auth_message');
				redirect('user/login');
			}
		}
	}

	public function logout()
	{
		$this->ion_auth->logout();
		redirect('user/login');
	}

	public function register()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('first_name', 'First name', 'trim|required');
		$this->form_validation->set_rules('last_name', 'Last name', 'trim|required');
		$this->form_validation->set_rules('username', 'Username', 'trim|required|is_unique[users.username]');
		$this->form_validation->set_rules('email', 'Email', 'trim|valid_email|required|is_unique[users.email]');
		$this->form_validation->set_rules('password', 'Password', 'trim|min_length[8]|max_length[20]|required');
		$this->form_validation->set_rules('confirm_password', 'Confirm password', 'trim|matches[password]|required');

		if ($this->form_validation->run()===FALSE)
		{
			$this->load->helper('form');
			$this->render('user/register');
		}
		else
		{
			$first_name = $this->input->post('first_name');
			$last_name = $this->input->post('last_name');
			$username = $this->input->post('first_name');
			$email = $this->input->post('email');
			$password = $this->input->post('password');

			$additional_data = array(
				'first_name' => $first_name,
				'last_name' => $last_name
			);

			$this->load->library('ion_auth');
			if ($this->ion_auth->register($username, $password, $email, $additional_data))
			{
				$_SESSION['auth_message'] = 'The account has been created. You may now login.';
				$this->session->mark_as_flash('auth_message');
				redirect('user/login');
			}
			else
			{
				$_SESSION['auth_message'] = $this->ion_auth->errors();
				$this->session->mark_as_flash('auth_message');
				redirect('user/register');
			}
		}
	}

	public function profile()
	{
		$this->enforce_login();
		$current_user = $this->ion_auth->user()->row();
		$this->data['user'] = $current_user;
		$this->data['user_profile'] = $this->User_model->get_profile($current_user->id);
		$this->render('user/profile');
		
	}

	public function edit_profile()
	{
		$this->enforce_login();
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

	public function enforce_login()
	{
		if (!$this->ion_auth->logged_in()) {
			redirect('user/login');
		}
		else {
			$this->data['current_user'] = $this->ion_auth->user()->row();
		}
	}
}
