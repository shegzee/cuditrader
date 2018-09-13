<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Open_user extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->library('ion_auth');
		if ($this->ion_auth->logged_in()) {
			redirect("user/profile");
		}
		$this->load->helper('email_helper');
		$this->load->model('User_model');
	}

	public function index()
	{
		$this->render('pages/home');
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

			if (valid_email($username)) {
				$username = $this->User_model->get_user_info_from_email($username)->username;
			}

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
			$username = $this->input->post('username');
			$email = $this->input->post('email');
			$password = $this->input->post('password');

			$this->load->library('ion_auth');
			if ($this->User_model->add($username, $password, $email, $first_name, $last_name))
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

}
