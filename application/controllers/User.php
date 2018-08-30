<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends Auth_Controller {

	public function __construct()
	{
		parent::__construct();
		// $this->load->library('ion_auth');
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
		$this->load->model('Bank_model');
		$this->data['banks'] = $this->compose_array('banks', 'name');
		$this->data['account_types'] = $this->compose_array('bank_account_types','name');
		$this->data['bank_details'] = $this->Bank_model->bank_details($this->user->id);
		$this->render('user/profile');
		
	}

	public function edit_profile()
	{
		// $current_user = $this->ion_auth->user()->row();

		$this->load->helper('form');
		$this->load->library('form_validation');
		
		$this->form_validation->set_rules('first_name', 'First name', 'trim|required');
		$this->form_validation->set_rules('last_name', 'Last name', 'trim|required');
		// $this->form_validation->set_rules('username', 'Username', 'trim|required|is_unique[users.username]');
		$this->form_validation->set_rules('email', 'Email', 'trim|valid_email|required|callback_crosscheck_email['.$this->user->id.']');

		if ($this->form_validation->run()===FALSE)
		{
			$this->render('user/edit_profile');
		}
		else
		{
			$user_data = array(
				'first_name' => $this->input->post('first_name'),
				'last_name' => $this->input->post('last_name'),
				'email' => $this->input->post('email')
			);
			$user_profile_data = array();

			if ($this->User_model->update_user_details($this->user->id, $user_data, $user_profile_data)) {
				$_SESSION['message'] = 'Profile edited successfully';
				$this->session->mark_as_flash('message');
				redirect('user/profile');
			}
			else {
				$_SESSION['message'] = 'Error editing profile';
				$this->session->mark_as_flash('message');
				redirect('user/edit_profile');
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

}
