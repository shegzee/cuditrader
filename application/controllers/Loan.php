<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Loan extends Auth_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Loan_model');
		$this->load->model('Utility_model');

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
			}
			// else if {

			// }
			else {
				$_SESSION['message'] = "Sorry, you have a pending loan request.";
				$this->session->mark_as_flash('message');
			}
			redirect('user/loans');

		}
	}

	public function cancel($loan_id)
	{
		$result = $this->Loan_model->cancel_loan($loan_id, $this->user->id);
		if ($result) {
			$_SESSION['message'] = "Loan request has been cancelled";
			$this->session->mark_as_flash('message');
			redirect('user/loans');
		}
		else {
			$_SESSION['message'] = "Sorry, we were unable to perform this action";
			$this->session->mark_as_flash('message');
			redirect('user/loans/'.$loan_id);
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

	/********************************************************
	AJAX methods to compute collateral amount and loan amount based on values
	*********************************************************/

	public function compute_collateral_amount()
	{
		$loan_unit_id = $this->input->get('loan_unit_id', TRUE) ? $this->input->get('loan_unit_id', TRUE) : 1;
		$loan_amount = $this->input->get('loan_amount', TRUE) ? $this->input->get('loan_amount', TRUE) : 0;
		$collateral_unit_id = $this->input->get('collateral_unit_id', TRUE) ? $this->input->get('collateral_unit_id', TRUE) : 1;

		$collateral_unit_price = $this->Utility_model->get_collateral_unit_price($collateral_unit_id); // dollar price of one unit of collateral

		if ($collateral_unit_price == "connection error") {
			$status = 0;
			$collateral_amount = 0;
		}
		else {
			$status = 1;
			$markup = $this->Utility_model->get_markup($collateral_unit_id); // markup
			$loan_unit_exchange_rate = $this->Utility_model->get_exchange_rate($loan_unit_id); // one dollar in loan unit

			$collateral_dollar_price = ($loan_amount / $loan_unit_exchange_rate) * ((100 + $markup) / 100); // worth of collateral to be obtained
			$collateral_amount = $collateral_dollar_price / $collateral_unit_price; // amount of collateral
		}

		$json = array('collateral_amount' => $collateral_amount, 'status' => $status);

		// $this->output->set_content_type('application/json')->set_output($collateral_unit_price);
		$this->output->set_content_type('application/json')->set_output(json_encode($json));
	}

	public function collateral_computation_data()
	{
		$loan_unit_id = $this->input->get('loan_unit_id', TRUE) ? $this->input->get('loan_unit_id', TRUE) : 1;
		// $loan_amount = $this->input->get('loan_amount', TRUE) ? $this->input->get('loan_amount', TRUE) : 0;
		$collateral_unit_id = $this->input->get('collateral_unit_id', TRUE) ? $this->input->get('collateral_unit_id', TRUE) : 1;

		$json = array();
		$json['collateral_unit_api_url'] = $this->Utility_model->get_collateral_unit_api_url($collateral_unit_id);
		$json['markup'] = doubleval($this->Utility_model->get_markup($collateral_unit_id));
		$json['loan_unit_exchange_rate'] = $this->Utility_model->get_exchange_rate($loan_unit_id);

		$this->output->set_content_type('application/json')->set_output(json_encode($json));
	}

	public function compute_loan_amount()
	{
		$collateral_unit_id = $this->input->get('collateral_unit_id', TRUE) ? $this->input->get('collateral_unit_id', TRUE) : 1;
		$collateral_amount = $this->input->get('collateral_amount', TRUE) ? $this->input->get('collateral_amount', TRUE) : 0;
		$loan_unit_id = $this->input->get('loan_unit_id', TRUE) ? $this->input->get('loan_unit_id', TRUE) : 1;

		$collateral_unit_price = $this->Utility_model->get_collateral_unit_price($collateral_unit_id); // dollar price of one unit of collateral

		if ($collateral_unit_price == "connection error") {
			$status = 0;
			$loan_amount = 0;
		}
		else {
			$status = 1;
			$markup = $this->Utility_model->get_markup($collateral_unit_id); // markup
			$loan_unit_exchange_rate = $this->Utility_model->get_exchange_rate($loan_unit_id); // one dollar in loan unit

			$collateral_dollar_price = $collateral_amount * $collateral_unit_price;
			$loan_amount = ($collateral_dollar_price * $loan_unit_exchange_rate) / ((100 + $markup) / 100);
		}

		$json = array('loan_amount' => $loan_amount, 'status' => $status);

		$this->output->set_content_type('application/json')->set_output(json_encode($json));
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
