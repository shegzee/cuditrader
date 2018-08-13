<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Loan extends Auth_Controller {

	public function __construct()
	{
		parent::__construct();
		// $this->load->library('ion_auth');
	}

	public function index()
	{
        $this->data['user'] = $this->ion_auth->user()->row();
		$this->render('pages/home');
	}

}
