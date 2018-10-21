<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->library('ion_auth');
		// if ($this->ion_auth->logged_in()) {
		// 	redirect("user/profile");
		// }
		$this->load->helper('email_helper');
		$this->load->model('User_model');
		$this->load->library('form_validation');
	}

	public function index()
	{
		// $this->render('pages/home');
		redirect('auth/login');
	}

	public function login()
	{
		if ($this->ion_auth->logged_in()) {
			redirect("user/profile");
		}
		$this->data['page_title'] = "Login";

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
				$_SESSION['message'] = $this->ion_auth->errors();
				$this->session->mark_as_flash('message');
				redirect('auth/login');
			}
		}
	}

	public function register()
	{
		if ($this->ion_auth->logged_in()) {
			redirect("user/profile");
		}
		$this->form_validation->set_rules('first_name', 'First name', 'trim|required');
		$this->form_validation->set_rules('last_name', 'Last name', 'trim|required');
		$this->form_validation->set_rules('phone', 'Phone', 'trim|required');
		$this->form_validation->set_rules('email', 'Email', 'trim|valid_email|required|is_unique[users.email]'
		, array('is_unique' => 'This email is in use by another user!'));
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
			$full_name = $first_name." ".$last_name;

			$this->load->library('ion_auth');
			if ($this->User_model->add($email, $password, $first_name, $last_name, $phone, $address))
			{
				$this->session->set_flashdata('message', $this->ion_auth->messages());
				$_SESSION['message'] = 'Welcome! Please verify your email';
				$this->session->mark_as_flash('message');
				//Send Registeration Email Codes Start                
                /*
                //send verification email to user
                $e_info['msg_content'] = "<p>Dear ".set_value('first_name').", </p>"
                . "<h1>Welcome to Cuditrader</h1></br>"
                . "<p>Thanks for choosing Cudirader. Please verify your email address to start borrowing loans using your Bitcoin/Ethereum as collateral.</p>";

                // compose verification link with generated activation code.
                // $activation_link = base_url('user/verify_email/'.$activation_code);
                
                $e_info['btn_link'] = base_url();
                $e_info['btn_text'] = "Click here to verify your account";

                $u_msg = $this->load->view('email/default', $e_info, TRUE);
                
				//send_email($sname, $semail, $rname, $remail, $subject, $message, $replyToEmail="", $files="")
				$this->genlib->send_email(DEFAULT_NAME, DEFAULT_EMAIL, set_value('first_name'), set_value('email'), "Welcome! Please verify your email", $u_msg);
				
				//Send email end
				*/
				redirect('auth/login');
			}
			else
			{
				$_SESSION['message'] = $this->ion_auth->errors();
				$this->session->mark_as_flash('message');
				redirect('auth/register');
			}
		}
	}

	public function verify_email($activation_code)
	{

	}

	/* ******************************************* */

    /**
	 * Change password
	 */
	public function change_password()
	{
		$this->form_validation->set_rules('old', 'Old Password', 'required');
		$this->form_validation->set_rules('new', "New Password", 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[new_confirm]|regex_match[/[A-Z]/]|regex_match[/\W/]');
		$this->form_validation->set_message('regex_match', 'The password must contain at least one UPPERCASE letter and one special character (e.g. * ! , . ()');
		$this->form_validation->set_rules('new_confirm', "Confirm New Password", 'required');

		if (!$this->ion_auth->logged_in())
		{
			redirect('auth/login', 'refresh');
		}

		$user = $this->ion_auth->user()->row();

		if ($this->form_validation->run() === FALSE)
		{
			// display the form
			// set the flash data error message if there is one
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

			$this->data['min_password_length'] = $this->config->item('min_password_length', 'ion_auth');
			$this->data['old_password'] = array(
				'name' => 'old',
				'id' => 'old',
				'type' => 'password',
			);
			$this->data['new_password'] = array(
				'name' => 'new',
				'id' => 'new',
				'type' => 'password',
				'pattern' => '^.{' . $this->data['min_password_length'] . '}.*$',
			);
			$this->data['new_password_confirm'] = array(
				'name' => 'new_confirm',
				'id' => 'new_confirm',
				'type' => 'password',
				'pattern' => '^.{' . $this->data['min_password_length'] . '}.*$',
			);
			$this->data['user_id'] = array(
				'name' => 'user_id',
				'id' => 'user_id',
				'type' => 'hidden',
				'value' => $user->id,
			);

			// render
			// $this->_render_page('auth' . DIRECTORY_SEPARATOR . 'change_password', $this->data);
			$this->render('auth' . DIRECTORY_SEPARATOR . 'change_password');
		}
		else
		{
			$identity = $this->session->userdata('identity');

			$change = $this->ion_auth->change_password($identity, $this->input->post('old'), $this->input->post('new'));

			if ($change)
			{
				//if the password was successfully changed
				$this->session->set_flashdata('message', $this->ion_auth->messages());
				$this->logout();
			}
			else
			{
				$this->session->set_flashdata('message', $this->ion_auth->errors());
				redirect('auth/change_password', 'refresh');
			}
		}
	}

	/**
	 * Forgot password
	 */
	public function forgot_password()
	{

		// setting validation rules by checking whether identity is username or email
		if ($this->config->item('identity', 'ion_auth') != 'email')
		{
			$this->form_validation->set_rules('identity', $this->lang->line('forgot_password_identity_label'), 'required');
		}
		else
		{
			$this->form_validation->set_rules('identity', $this->lang->line('forgot_password_validation_email_label'), 'required|valid_email');
		}


		if ($this->form_validation->run() === FALSE)
		{
			$this->data['type'] = $this->config->item('identity', 'ion_auth');
			// setup the input
			$this->data['identity'] = array('name' => 'identity',
				'id' => 'identity',
			);

			if ($this->config->item('identity', 'ion_auth') != 'email')
			{
				$this->data['identity_label'] = $this->lang->line('forgot_password_identity_label');
			}
			else
			{
				$this->data['identity_label'] = $this->lang->line('forgot_password_email_identity_label');
			}

			// set any errors and display the form
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			// $this->_render_page('auth' . DIRECTORY_SEPARATOR . 'forgot_password', $this->data);
			$this->render('auth' . DIRECTORY_SEPARATOR . 'forgot_password');
		}
		else
		{
			$identity_column = $this->config->item('identity', 'ion_auth');
			$identity = $this->ion_auth->where($identity_column, $this->input->post('identity'))->users()->row();

			if (empty($identity))
			{

				if ($this->config->item('identity', 'ion_auth') != 'email')
				{
					$this->ion_auth->set_error('forgot_password_identity_not_found');
				}
				else
				{
					$this->ion_auth->set_error('forgot_password_email_not_found');
				}

				$this->session->set_flashdata('message', $this->ion_auth->errors());
				redirect("auth/forgot_password", 'refresh');
			}

			// run the forgotten password method to email an activation code to the user
			$forgotten = $this->ion_auth->forgotten_password($identity->{$this->config->item('identity', 'ion_auth')});

			if ($forgotten)
			{
				// if there were no errors
				$this->session->set_flashdata('message', $this->ion_auth->messages());
				// $_SESSION['message'] = $this->ion_auth->messages();
				// $this->session->mark_as_flash('message');
				redirect("auth/login", 'refresh'); //we should display a confirmation page here instead of the login page
			}
			else
			{
				$this->session->set_flashdata('message', $this->ion_auth->errors());
				redirect("auth/forgot_password", 'refresh');
			}
		}
	}

	/**
	 * Reset password - final step for forgotten password
	 *
	 * @param string|null $code The reset code
	 */
	public function reset_password($code = NULL)
	{
		if (!$code)
		{
			show_404();
		}

		$user = $this->ion_auth->forgotten_password_check($code);

		if ($user)
		{
			// if the code is valid then display the password reset form

			$this->form_validation->set_rules('new', $this->lang->line('reset_password_validation_new_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[new_confirm]');
			$this->form_validation->set_rules('new_confirm', $this->lang->line('reset_password_validation_new_password_confirm_label'), 'required');

			if ($this->form_validation->run() === FALSE)
			{
				// display the form

				// set the flash data error message if there is one
				$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

				$this->data['min_password_length'] = $this->config->item('min_password_length', 'ion_auth');
				$this->data['new_password'] = array(
					'name' => 'new',
					'id' => 'new',
					'type' => 'password',
					'pattern' => '^.{' . $this->data['min_password_length'] . '}.*$',
				);
				$this->data['new_password_confirm'] = array(
					'name' => 'new_confirm',
					'id' => 'new_confirm',
					'type' => 'password',
					'pattern' => '^.{' . $this->data['min_password_length'] . '}.*$',
				);
				$this->data['user_id'] = array(
					'name' => 'user_id',
					'id' => 'user_id',
					'type' => 'hidden',
					'value' => $user->id,
				);
				$this->data['csrf'] = $this->_get_csrf_nonce();
				$this->data['code'] = $code;

				// render
				// $this->_render_page('auth' . DIRECTORY_SEPARATOR . 'reset_password', $this->data);
				$this->render('auth' . DIRECTORY_SEPARATOR . 'reset_password');
			}
			else
			{
				// do we have a valid request?
				if ($this->_valid_csrf_nonce() === FALSE || $user->id != $this->input->post('user_id'))
				{

					// something fishy might be up
					$this->ion_auth->clear_forgotten_password_code($code);

					show_error($this->lang->line('error_csrf'));

				}
				else
				{
					// finally change the password
					$identity = $user->{$this->config->item('identity', 'ion_auth')};

					$change = $this->ion_auth->reset_password($identity, $this->input->post('new'));

					if ($change)
					{
						// if the password was successfully changed
						$this->session->set_flashdata('message', $this->ion_auth->messages());
						redirect("auth/login", 'refresh');
					}
					else
					{
						$this->session->set_flashdata('message', $this->ion_auth->errors());
						redirect('auth/reset_password/' . $code, 'refresh');
					}
				}
			}
		}
		else
		{
			// if the code is invalid then send them back to the forgot password page
			$this->session->set_flashdata('message', $this->ion_auth->errors());
			redirect("auth/forgot_password", 'refresh');
		}
	}

	/**
	 * Activate the user
	 *
	 * @param int         $id   The user ID
	 * @param string|bool $code The activation code
	 */
	public function activate($id, $code = FALSE)
	{
		if ($code !== FALSE)
		{
			$activation = $this->ion_auth->activate($id, $code);
		}
		else if ($this->ion_auth->is_admin())
		{
			$activation = $this->ion_auth->activate($id);
		}

		if ($activation)
		{
			// redirect them to the auth page
			$this->User_model->create_profile($id);
			$this->session->set_flashdata('message', $this->ion_auth->messages());
			redirect("auth", 'refresh');
		}
		else
		{
			// redirect them to the forgot password page
			$this->session->set_flashdata('message', $this->ion_auth->errors());
			redirect("auth/forgot_password", 'refresh');
		}
	}

	public function logout()
	{
		$this->ion_auth->logout();
		redirect('auth/login');
	}

	public function dummy_json()
	{
		$json = '[
    	{
	        "id": "bitcoin", 
	        "name": "Bitcoin", 
	        "symbol": "BTC", 
	        "rank": "1", 
	        "price_usd": "6659.76684263", 
	        "price_btc": "1.0", 
	        "24h_volume_usd": "6482367115.14", 
	        "market_cap_usd": "115378296124", 
	        "available_supply": "17324675.0", 
	        "total_supply": "17324675.0", 
	        "max_supply": "21000000.0", 
	        "percent_change_1h": "0.8", 
	        "percent_change_24h": "0.02", 
	        "percent_change_7d": "0.27", 
	        "last_updated": "1539672386"
	    }
	]';
	$this->output->set_content_type('application/json')->set_output($json);
	}

}
