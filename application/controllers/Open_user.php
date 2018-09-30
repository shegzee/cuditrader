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
		$this->form_validation->set_rules('email', 'Email Address', 'trim|required|valid_email');
		$this->form_validation->set_rules('password', 'Password', 'trim|required');
		// $this->data['message'] = "login form here";
		if ($this->form_validation->run() === FALSE)
		{
			$this->render('user/login');
		}
		else
		{
			// $remember = (bool) $this->input->post('remember');
			$remember = TRUE;
			$email = $this->input->post('email');
			$password = $this->input->post('password');

			if ($this->ion_auth->login($email, $password, $remember))
			{
				redirect('user/profile'); // decide
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
		$this->form_validation->set_rules('phone', 'Phone', 'trim|required');
		$this->form_validation->set_rules('email', 'Email', 'trim|valid_email|required|is_unique[users.email]');
		$this->form_validation->set_rules('password', 'Password', 'trim|min_length[8]|required|regex_match[/[A-Z]/]|regex_match[/\W/]'
			// , array('regex_match' => 'The password must contain at least one UPPERCASE letter and one special character; e.g. * ! , . ()')
			);
		 // regex_match[/([A-Z]\w*\W)/|/(\W\w*[A-Z])/]'
		$this->form_validation->set_message('regex_match', 'The password must contain at least one UPPERCASE letter and one special character (e.g. * ! , . ()');
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
			$phone = $this->input->post('phone');
			$email = $this->input->post('email');
			$password = $this->input->post('password');
			$address = $this->input->post('address');

			$this->load->library('ion_auth');
			if ($this->User_model->add($email, $password, $first_name, $last_name, $phone, $address))
			{
				$_SESSION['message'] = 'The account has been created. You may now login.';
				$this->session->mark_as_flash('message');
				redirect('user/login');
				//Send Registeration Email Codes Start
				//$activity = "Your Cuditrader account has been created: <b>".set_value('email')."</b> '";
                
                //$this->activity->add($this->session->admin_id, "User Account Creation", $activity, $this->activity_group);                
                
                //send email to user containing account details
                $e_info['msg_content'] = "<p>Dear ".set_value('first_name').", </p>"
                . "<p>An account has been created for you on Cuditrader. Click the link in this mail to verify your account </p>"
				. "<p>Below are your details:</p>"
				. "<b>Name: ".set_value('first_name') + .set_value('last_name')."</b><br>"
                . "<b>Email: ".set_value('email')."</b><br>"
                . "<p>Regards</p>";
                
                $e_info['btn_link'] = base_url();
                $e_info['btn_text'] = "Click here to verify your account";

                $u_msg = $this->load->view('email/default', $e_info, TRUE);
                
                //send_email($sname, $semail, $rname, $remail, $subject, $message, $cc='', $bcc='', $replyToEmail="", $files="")
                //$bcc = $this->genlib->getPortalAdminBcc();
				$this->genlib->send_email(DEFAULT_NAME, DEFAULT_EMAIL, set_value('first_name'), set_value('email'), "Cuditrader Account", $u_msg, '');
				
				//Send email end
			}
			else
			{
				$_SESSION['message'] = $this->ion_auth->errors();
				$this->session->mark_as_flash('message');
				redirect('user/register');
			}
		}
	}

}
