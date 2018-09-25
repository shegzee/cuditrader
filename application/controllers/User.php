<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends User_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->data['loan_unit_icons'] = $this->load_loan_unit_icons();
        $this->data['collateral_unit_icons'] = $this->load_collateral_unit_icons();
		// $this->load->library('ion_auth');
	}

	private function load_loan_unit_icons() {
		return $this->prep_select('loan_units', 'id', 'logo', TRUE);
    }

    private function load_collateral_unit_icons() {
		return $this->prep_select('collateral_units', 'id', 'logo', TRUE);
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
		$this->load->helper('form');
		$this->load->library('form_validation');
		
		$this->form_validation->set_rules('full_name', 'Full name', 'trim|required');
		
		$this->data['page_title'] = "Profile";
		
		if ($this->form_validation->run()===FALSE)
		{
			$this->render('user/profile');
		}
		else
		{
			$split_name = split(" ", $this->input->post('full_name'), 2);
			$user_data = array(
				'first_name' => $split_name[0],
				'last_name' => $split_name[1],
				'phone' => $this->input->post('phone'),
				'address' => $this->input->post('address')
			);
			$user_profile_data = array();

			if ($this->User_model->update_user_details($this->user->id, $user_data, $user_profile_data)) {
				$_SESSION['message'] = 'Profile edited successfully';
				$this->session->mark_as_flash('message');
			}
			else {
				$_SESSION['message'] = 'Error editing profile'.var_dump($user_data);
				$this->session->mark_as_flash('message');
			}

		}

		$this->render('user/profile');
		
	}

	public function bank()
	{
		$this->load->helper('form');
		$this->load->model('Bank_model');
		

		$this->load->library('form_validation');
		$this->form_validation->set_rules('bank_id', 'Bank', 'required');
		$this->form_validation->set_rules('account_number', 'Account number', 'required|is_natural');
		$this->form_validation->set_rules('account_type_id', 'Account type', 'required');

		if ($this->form_validation->run()) {
			$data = array(
    		'bank_id' => $this->input->post('bank_id'),
    		'user_id' => $this->user->id,
    		'account_number' => $this->input->post('account_number'),
    		'account_name' => $this->input->post('account_name'),
    		'account_type_id' => $this->input->post('account_type_id'),
    		'description' => $this->input->post('description'));

			if ($this->input->post('is_primary')) {
				$data['is_primary'] = TRUE;
				// echo $dab;
			} else {
				$data['is_primary'] = FALSE;
			}

			if ($this->Bank_model->add_bank_account($data)) {
			// if (TRUE) {
				if ($data['is_primary']){
					$_SESSION['message'] = "The new primary bank account has been added successfully";
				} else {
					$_SESSION['message'] = "The bank account has been added successfully";
				}
				$this->session->mark_as_flash('message');
				// redirect('user/bank');
			}
			else {
				$_SESSION['message'] = "An error occurred. Please, try later.";
				$this->session->mark_as_flash('message');
			}
		}
		$this->data['banks_dropdown'] = parent::prep_select('banks', 'id', 'name', TRUE, 'name');
		$this->data['account_types_dropdown'] 	= parent::prep_select('bank_account_types', 'id', 'name', TRUE);


		$this->data['banks'] = $this->compose_array('banks', 'name');
		$this->data['account_types'] = $this->compose_array('bank_account_types','name');
		$this->data['bank_details'] = $this->Bank_model->bank_details($this->user->id);

		$this->data['page_title'] = "Bank Accounts";
		$this->render('user/bank');
	}

	/*
	the user can view loans for a particular status, or all loans
	*/
	public function loans($status="all")
	{
		$this->data['page_title'] = "Loans";

		$this->load->model("Loan_model");

		$this->data['loan_currencies']	= parent::prep_select('loan_units', 'id', 'name', FALSE, 'name');
		$this->data['cryptocurrencies']	= parent::prep_select('collateral_units', 'id', 'name', TRUE, 'name', 'ASC');
		$this->data['statuses']			= parent::prep_select('loan_status', 'status_number', 'status', FALSE, 'status_number');
		$this->data['status_ids'] 		= array_flip($this->data['statuses']);

		$this->data['status'] 			= $status;
		// $this->data['statuses']			= parent::prep_select('loan_status', 'status_number', 'status', FALSE, 'status');
		// $this->data['status_ids'] 		= array_flip($this->data['statuses']);
		// $this->data['loan_currencies']	= parent::prep_select('loan_units', 'id', 'name', FALSE, 'name');
		// $this->data['cryptocurrencies']	= parent::prep_select('collateral_units', 'id', 'name', TRUE, 'name', 'ASC');
		if ($status=="all") {
			$this->data['loans'] = $this->Loan_model->get_loans($this->user->id);
		}
		else {
			$this->data['loans'] = $this->Loan_model->get_loans($this->user->id, $status);
		}
		$this->render('user/loans');
	}

	public function collaterals()
	{
		$this->data['page_title'] = "Collaterals";
		// $approved_loans = 
		$this->render('user/collaterals');
	}

	public function edit_profile()
	{
		// $current_user = $this->ion_auth->user()->row();
		$this->data['page_title'] = "Profile";

		$this->load->helper('form');
		$this->load->library('form_validation');
		
		$this->form_validation->set_rules('full_name', 'Full name', 'trim|required');
		// $this->form_validation->set_rules('first_name', 'First name', 'trim|required');
		// $this->form_validation->set_rules('last_name', 'Last name', 'trim|required');
		// $this->form_validation->set_rules('username', 'Username', 'trim|required|is_unique[users.username]');
		// $this->form_validation->set_rules('email', 'Email', 'trim|valid_email|required|callback_crosscheck_email['.$this->user->id.']');

		if ($this->form_validation->run()===FALSE)
		{
			$this->render('user/profile');
		}
		else
		{
			$split_name = split(" ", $this->input->post('full_name'), 2);
			$user_data = array(
				'first_name' => $split_name[0],
				'last_name' => $split_name[1],
				'phone' => $this->input->post('phone'),
				'address' => $this->input->post('address')
			);
			$user_profile_data = array();

			if ($this->User_model->update_user_details($this->user->id, $user_data, $user_profile_data)) {
				$_SESSION['message'] = 'Profile edited successfully';
				$this->session->mark_as_flash('message');
				redirect('user/profile');
			}
			else {
				$_SESSION['message'] = 'Error editing profile'.var_dump($user_data);
				$this->session->mark_as_flash('message');
				redirect('user/profile');
			}

		}
	}

	public function upload_profile_picture()
    {
        $config['upload_path']          = './uploads/profile_pictures/';
        $config['allowed_types']        = 'gif|jpg|png';
        $config['max_size']             = 1000;
        $config['max_width']            = 1024;
        $config['max_height']           = 768;
        $config['encrypt_name']			= TRUE;

        $this->load->library('upload', $config);

        if ( ! $this->upload->do_upload('new_picture'))
        {
            // $this->data['error'] = array('error' => $this->upload->display_errors());
            $_SESSION['message'] = $this->upload->display_errors();
			$this->session->mark_as_flash('message');
        }
        else
        {
            // $upload_data = array('upload_data' => $this->upload->data());
            $upload_data = $this->upload->data();
            $this->User_model->update_profile_picture($this->user->id, $upload_data['file_name']);
            $data['upload_data'] = $upload_data;
            $_SESSION['message'] = "Image uploaded successfully";
			$this->session->mark_as_flash('message');
        }
        redirect('user/edit_profile');
    }

	public function crosscheck_email($email, $user_id){
        //check db to ensure email was previously used for user with $user_id i.e. the same user we're updating his details
        $userWithEmail = $this->genmod->getTableCol('users', 'id', 'email', $email);
        if (!$userWithEmail) return TRUE;
        if($userWithEmail == $user_id){
            //used for same user. All is well.
            return TRUE;
        }
        
        else{
            $this->form_validation->set_message('crosscheck_email', 'This email is already attached to another user');
                
            return FALSE;
        }
    }

    public function full_name($user) {
    	return $user->first_name." ".$user->last_name;
    }

}
