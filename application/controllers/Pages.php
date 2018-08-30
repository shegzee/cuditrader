<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pages extends MY_Controller {

	public function index()
	{
		// $this->load->view('pages/home');
		$this->render('pages/home', NULL);

	}

	public function view($page = 'home') {
		if (! file_exists(APPPATH.'views/pages/'.$page.'.php')) {
			show_404();
		}
		$this->data['page_title'] = ucfirst($page);

		$this->render('pages/'.$page, 'pages_template');
		// $data['page_content'] = $this->load->view('pages/'.$page, '', TRUE);

		// $this->load->view('templates/main.php', $data);
	}
}
