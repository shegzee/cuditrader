<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Loan extends Auth_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Loan_model');

		$this->data['loan_currencies']	= parent::prep_select('loan_units', 'id', 'name', FALSE, 'name');
		$this->data['cryptocurrencies']	= parent::prep_select('collateral_units', 'id', 'name', TRUE, 'name', 'ASC');
		$this->data['statuses']			= parent::prep_select('loan_status', 'status_number', 'status', FALSE, 'status');
		$this->data['status_ids'] 		= array_flip($this->data['statuses']);
	}

	public function index()
	{
		$this->view_loans_by_status();
	}

	/*
	here, the user requests for a loan.
	loan object is created, with status 0
	*/
	public function request()
	{
		$this->load->helper('form');
		$this->load->library('form_validation');

		$this->data["page_title"] = "Request a loan";

		// $this->data['loan_currencies']	= parent::prep_select('loan_units', 'id', 'name', FALSE, 'name');
		// $this->data['cryptocurrencies']	= parent::prep_select('collateral_units', 'id', 'name', TRUE, 'name', 'ASC');

		$this->form_validation->set_rules('loan_unit_id', 'Loan Unit', 'required');
		$this->form_validation->set_rules('loan_amount', 'Loan Amount', 'required|numeric');
		$this->form_validation->set_rules('collateral_unit_id', 'Collateral Unit', 'required');
		$this->form_validation->set_rules('collateral_amount', 'Collateral Amount', 'required|numeric');
		$this->form_validation->set_rules('loan_duration', 'Loan Duration', 'required|is_natural');

		if ($this->form_validation->run() === FALSE) {
			$this->render('loan/request_loan');
		}
		else {
			$data = array(
				'loan_unit_id' 			=> $this->input->post('loan_unit_id'),
				'loan_amount' 			=> $this->input->post('loan_amount'),
				'collateral_unit_id' 	=> $this->input->post('collateral_unit_id'),
				'collateral_amount' 	=> $this->input->post('collateral_amount'),
				'loan_duration' 		=> $this->input->post('loan_duration'),
				'user_id'				=> $this->user->id
			);

			if ($this->Loan_model->new_loan($data) == TRUE) {
				$_SESSION['message'] = "The loan request has been made. It will be processed within 24 hours.";
				$this->session->mark_as_flash('message');
				redirect('loan/view');
			}
			// else if {

			// }
			else {
				$_SESSION['message'] = "Sorry, you have a pending loan request.";
				$this->session->mark_as_flash('message');
			}

		}
	}

	public function cancel($loan_id)
	{
		$result = $this->Loan_model->cancel_loan($loan_id, $this->user->id);
		$_SESSION['message'] = $result;
		$this->session->mark_as_flash('message');
		if ($result) {
			redirect('loan/');
		}
		else {
			redirect('loan/view_loan/'.$loan_id);
		}
	}

	/*
	here, the user can view a single loan
	*/
	public function view_loan($loan_id=0)
	{
		
		$loan = $this->Loan_model->get_loan($loan_id, $this->user->id);
		if (!$loan) {
			redirect('loan/');
		}
		$this->data['loan'] = $loan;
		$this->render('loan/view_single');
	}

	/*
	the user can view loans for a particular status, or all loans
	*/
	public function view_loans_by_status($status="all")
	{
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
		$this->render('loan/view_loans');
	}

}
