<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bank extends Auth_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Bank_model');
	}
	/*
	view all bank accounts for current user
	*/
	public function index()
	{

	}

	/*
	add new bank account details
	*/
	public function add()
	{
		$this->load->helper('form');
		$this->load->library('form_validation');

		$this->data["title"] = "Add bank";

		// prepopulate selects
		// $get_all_banks 				= $this->Bank_model->get_banks();
		// $get_account_types = $this->Bank_model->get_bank_account_types();

		// $banks 			= array();
		// $account_types 	= array();
		
		// foreach ($all_banks as $bank_details) {
		// 	$banks[$bank_details['id']] = $bank_details['name'];
		// }
		
		// foreach ($all_bank_account_types as $bank_account_types) {
		// 	$account_types[$bank_account_types['id']] = $bank_account_types['type'];
		// }

		// $this->data['banks'] 			= $banks;
		// $this->data['account_types'] 	= $account_types;

		$this->data['banks'] 			= parent::prep_select('banks', 'id', 'name', TRUE, 'name');
		$this->data['banks'][""] = "";
		$this->data['account_types'] 	= parent::prep_select('bank_account_types', 'id', 'name');

		$this->form_validation->set_rules('bank_id', 'Bank', 'required');
		$this->form_validation->set_rules('account_number', 'Account number', 'required');
		$this->form_validation->set_rules('account_type_id', 'Account type', 'required');

		if ($this->form_validation->run() === FALSE) {
			$this->render('bank/add');
		}
		else {
			$data = array(
    		'bank_id' => $this->input->post('bank_id'),
    		'user_id' => $this->user->id,
    		'account_number' => $this->input->post('account_number'),
    		'account_name' => $this->input->post('account_name'),
    		'account_type_id' => $this->input->post('account_type_id'),
    		'is_primary' => $this->input->post('is_primary'),
    		'description' => $this->input->post('description'));

			$this->Bank_model->add_bank_account($data);
			
			$_SESSION['message'] = "The bank account has been added successfully";
			$this->session->mark_as_flash('message');
			redirect('user/profile');
		}

	}

	/*
	view single bank account details
	*/
	public function view($id)
	{

	}

	/*
	edit single bank account details
	*/
	public function edit($id)
	{

	}

	/*
	delete single bank account details
	*/
	public function delete($id)
	{

	}
}