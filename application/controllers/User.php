<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->library('ion_auth');
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

	public function profile()
	{
		
	}
}
