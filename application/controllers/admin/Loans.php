<?php
defined('BASEPATH') OR exit('');

/**
 * Loan Controller
 *
 * @author Olu Segun <ojosamuelolusegun@gmail.com>
 * @date 7th September, 2018
 */
class Loans extends CI_Controller
{
    
    public function __construct() 
    {
    	parent::__construct();

    	$this->genlib->checkLogin();
        
        $this->load->model(['admin/loan']);

        // // load currencies
        // $data['loan_unit_icons'] = $this->load_loan_unit_icons();
        // $data['collateral_unit_icons'] = $this->load_collateral_unit_icons();

        // load status numbers
        // $this->status_numbers = $this->load_status_numbers();

    }

  //   private function load_status_numbers() {
  //   	// $this->db->like('status', $status);
		// // $this->db->start_cache();
		// // $this->db->select('status_number');
		// // $this->db->stop_cache();
		// $result = $this->loan->loan_status_numbers();
		// return $result;
  //   }

  //   public function get_status_number($status)
  //   {
  //   	$status = strtolower($status);
  //       foreach ($this->status_numbers as $value) {
  //           # code...
  //           $this_status = $value->status;
  //           if (stristr($status, strtolower($this_status))) {
  //               return $value->status_number;
  //           }
  //       }
  //       return -1;
  //   }

  //   public function get_status_text($status_number)
  //   {
  //       foreach ($this->status_numbers as $value) {
  //           # code...
  //           $this_status_number = $value->status_number;
  //           if ($status_number == $this_status_number) {
  //               return $value->status;
  //           }
  //       }
  //       return "";
  //   }

    private function load_loan_unit_icons() {
		return $this->prep_select('loan_units', 'id', 'logo', TRUE);
    }

    private function load_collateral_unit_icons() {
		return $this->prep_select('collateral_units', 'id', 'logo', TRUE);
    }

    public function index(){
        $this->load->helper('form');

        $sub_data['allUsersList'] = $this->prep_select('users', 'id', 'email', TRUE);
        $sub_data['allStatusesList'] = $this->prep_select('loan_status', 'status_number', 'status', TRUE);
        $sub_data['allLUnitsList'] = $this->prep_select('loan_units', 'id', 'name', TRUE);
        $sub_data['allCUnitsList'] = $this->prep_select('collateral_units', 'id', 'name', TRUE);
        $data['pageContent'] = $this->load->view('admin/loans/loan', $sub_data, TRUE);
        $data['pageTitle'] = "Loans";

        $this->load->view('admin/main', $data);
    }

    /**
     * "larl_" = "Load All Requested Loans"
     */
    public function larl_(){
        $this->genlib->ajaxOnly();
        
        $this->load->helper('text');
        
        //set the sort order
        $orderBy = $this->input->get('orderBy', TRUE) ? $this->input->get('orderBy', TRUE) : "status_number";
        $orderFormat = $this->input->get('orderFormat', TRUE) ? $this->input->get('orderFormat', TRUE) : "ASC";
        
        //count the total number of items in db
        $totalLoans = $this->db->count_all('loans');
        
        $this->load->library('pagination');
        
        $pageNumber = $this->uri->segment(3, 0);//set page number to zero if the page number is not set in the third segment of uri
    
        $limit = $this->input->get('limit', TRUE) ? $this->input->get('limit', TRUE) : 10;//show $limit per page
        $start = $pageNumber == 0 ? 0 : ($pageNumber - 1) * $limit;//start from 0 if pageNumber is 0, else start from the next iteration
        
        //call setPaginationConfig($totalRows, $urlToCall, $limit, $attributes) in genlib to configure pagination
        $config = $this->genlib->setPaginationConfig($totalLoans, "loans/larl_", $limit, ['onclick'=>'return loans(this.href);']);
        
        $this->pagination->initialize($config);//initialize the library class
        
        // load currencies
        $data['loan_unit_icons'] = $this->load_loan_unit_icons();
        $data['collateral_unit_icons'] = $this->load_collateral_unit_icons();

        $status_number = $this->loan->get_status_number("PENDING");
        //get all items from db
        $data['reqLoans'] = $this->loan->getAll($status_number, $orderBy, $orderFormat, $start, $limit);
        $data['range'] = $totalLoans > 0 ? "Showing " . ($start+1) . "-" . ($start + count($data['reqLoans'])) . " of " . $totalLoans : "";
        $data['links'] = $this->pagination->create_links();//page links
        $data['sn'] = $start+1;
        
        $json['loansList'] = $this->load->view('admin/loans/requestedloanlist', $data, TRUE);//get view with populated items table

        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }
    
    /**
     * "laal_" = "Load All Approved Loans"
     */
    public function laal_(){
        $this->genlib->ajaxOnly();
        
        $this->load->helper('text');
        
        //set the sort order
        $orderBy = $this->input->get('orderBy', TRUE) ? $this->input->get('orderBy', TRUE) : "status_number";
        $orderFormat = $this->input->get('orderFormat', TRUE) ? $this->input->get('orderFormat', TRUE) : "ASC";
        
        //count the total number of items in db
        $totalLoans = $this->db->count_all('loans');
        
        $this->load->library('pagination');
        
        $pageNumber = $this->uri->segment(3, 0);//set page number to zero if the page number is not set in the third segment of uri
    
        $limit = $this->input->get('limit', TRUE) ? $this->input->get('limit', TRUE) : 10;//show $limit per page
        $start = $pageNumber == 0 ? 0 : ($pageNumber - 1) * $limit;//start from 0 if pageNumber is 0, else start from the next iteration
        
        //call setPaginationConfig($totalRows, $urlToCall, $limit, $attributes) in genlib to configure pagination
        $config = $this->genlib->setPaginationConfig($totalLoans, "loans/laal_", $limit, ['onclick'=>'return loans(this.href);']);
        
        $this->pagination->initialize($config);//initialize the library class
        
        // load currencies
        $data['loan_unit_icons'] = $this->load_loan_unit_icons();
        $data['collateral_unit_icons'] = $this->load_collateral_unit_icons();

        $status_number = $this->loan->get_status_number("APPROVED");
        //get all items from db
        $data['appLoans'] = $this->loan->getAll($status_number, $orderBy, $orderFormat, $start, $limit);
        $data['range'] = $totalLoans > 0 ? "Showing " . ($start+1) . "-" . ($start + count($data['appLoans'])) . " of " . $totalLoans : "";
        $data['links'] = $this->pagination->create_links();//page links
        $data['sn'] = $start+1;
        
        $json['loansList'] = $this->load->view('admin/loans/approvedloanlist', $data, TRUE);//get view with populated items table

        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }

    /**
     * "lagl_" = "Load All Granted Loans" (user has sent collateral)
     */
    public function lagl_(){
        $this->genlib->ajaxOnly();
        
        $this->load->helper('text');
        
        //set the sort order
        $orderBy = $this->input->get('orderBy', TRUE) ? $this->input->get('orderBy', TRUE) : "status_number";
        $orderFormat = $this->input->get('orderFormat', TRUE) ? $this->input->get('orderFormat', TRUE) : "ASC";
        
        //count the total number of items in db
        $totalLoans = $this->db->count_all('loans');
        
        $this->load->library('pagination');
        
        $pageNumber = $this->uri->segment(3, 0);//set page number to zero if the page number is not set in the third segment of uri
    
        $limit = $this->input->get('limit', TRUE) ? $this->input->get('limit', TRUE) : 10;//show $limit per page
        $start = $pageNumber == 0 ? 0 : ($pageNumber - 1) * $limit;//start from 0 if pageNumber is 0, else start from the next iteration
        
        //call setPaginationConfig($totalRows, $urlToCall, $limit, $attributes) in genlib to configure pagination
        $config = $this->genlib->setPaginationConfig($totalLoans, "loans/lagl_", $limit, ['onclick'=>'return loans(this.href);']);
        
        $this->pagination->initialize($config);//initialize the library class
        
        // load currencies
        $data['loan_unit_icons'] = $this->load_loan_unit_icons();
        $data['collateral_unit_icons'] = $this->load_collateral_unit_icons();

        $status_number = $this->loan->get_status_number("GRANTED");
        //get all items from db
        $data['graLoans'] = $this->loan->getAll($status_number, $orderBy, $orderFormat, $start, $limit);
        $data['range'] = $totalLoans > 0 ? "Showing " . ($start+1) . "-" . ($start + count($data['graLoans'])) . " of " . $totalLoans : "";
        $data['links'] = $this->pagination->create_links();//page links
        $data['sn'] = $start+1;
        
        $json['loansList'] = $this->load->view('admin/loans/grantedloanlist', $data, TRUE);//get view with populated items table

        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }
    
    /**
     * "ladl_" = "Load All Denied Loans"
     */
    public function ladl_(){
        $this->genlib->ajaxOnly();
        
        $this->load->helper('text');
        
        //set the sort order
        $orderBy = $this->input->get('orderBy', TRUE) ? $this->input->get('orderBy', TRUE) : "status_number";
        $orderFormat = $this->input->get('orderFormat', TRUE) ? $this->input->get('orderFormat', TRUE) : "ASC";
        
        //count the total number of items in db
        $totalLoans = $this->db->count_all('loans');
        
        $this->load->library('pagination');
        
        $pageNumber = $this->uri->segment(3, 0);//set page number to zero if the page number is not set in the third segment of uri
    
        $limit = $this->input->get('limit', TRUE) ? $this->input->get('limit', TRUE) : 10;//show $limit per page
        $start = $pageNumber == 0 ? 0 : ($pageNumber - 1) * $limit;//start from 0 if pageNumber is 0, else start from the next iteration
        
        //call setPaginationConfig($totalRows, $urlToCall, $limit, $attributes) in genlib to configure pagination
        $config = $this->genlib->setPaginationConfig($totalLoans, "loans/ladl_", $limit, ['onclick'=>'return loans(this.href);']);
        
        $this->pagination->initialize($config);//initialize the library class
        
        // load currencies
        $data['loan_unit_icons'] = $this->load_loan_unit_icons();
        $data['collateral_unit_icons'] = $this->load_collateral_unit_icons();

        $status_number = $this->loan->get_status_number("DENIED");
        //get all items from db
        $data['denLoans'] = $this->loan->getAll($status_number, $orderBy, $orderFormat, $start, $limit);
        $data['range'] = $totalLoans > 0 ? "Showing " . ($start+1) . "-" . ($start + count($data['denLoans'])) . " of " . $totalLoans : "";
        $data['links'] = $this->pagination->create_links();//page links
        $data['sn'] = $start+1;
        
        $json['loansList'] = $this->load->view('admin/loans/deniedloanlist', $data, TRUE);//get view with populated items table

        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }
    
    /**
     * "lacl_" = "Load All Cleared Loans"
     */
    public function lacl_(){
        $this->genlib->ajaxOnly();
        
        $this->load->helper('text');
        
        //set the sort order
        $orderBy = $this->input->get('orderBy', TRUE) ? $this->input->get('orderBy', TRUE) : "status_number";
        $orderFormat = $this->input->get('orderFormat', TRUE) ? $this->input->get('orderFormat', TRUE) : "ASC";
        
        //count the total number of items in db
        $totalLoans = $this->db->count_all('loans');
        
        $this->load->library('pagination');
        
        $pageNumber = $this->uri->segment(3, 0);//set page number to zero if the page number is not set in the third segment of uri
    
        $limit = $this->input->get('limit', TRUE) ? $this->input->get('limit', TRUE) : 10;//show $limit per page
        $start = $pageNumber == 0 ? 0 : ($pageNumber - 1) * $limit;//start from 0 if pageNumber is 0, else start from the next iteration
        
        //call setPaginationConfig($totalRows, $urlToCall, $limit, $attributes) in genlib to configure pagination
        $config = $this->genlib->setPaginationConfig($totalLoans, "loans/lacl_", $limit, ['onclick'=>'return loans(this.href);']);
        
        $this->pagination->initialize($config);//initialize the library class
        
        // load currencies
        $data['loan_unit_icons'] = $this->load_loan_unit_icons();
        $data['collateral_unit_icons'] = $this->load_collateral_unit_icons();

        $status_number = $this->loan->get_status_number("CLEARED");
        //get all items from db
        $data['cleLoans'] = $this->loan->getAll($status_number, $orderBy, $orderFormat, $start, $limit);
        $data['range'] = $totalLoans > 0 ? "Showing " . ($start+1) . "-" . ($start + count($data['cleLoans'])) . " of " . $totalLoans : "";
        $data['links'] = $this->pagination->create_links();//page links
        $data['sn'] = $start+1;
        
        $json['loansList'] = $this->load->view('admin/loans/clearedloanlist', $data, TRUE);//get view with populated items table

        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }
    
    /**
     * "laca_" = "Load All Cancelled Loans"
     */
    public function laca_(){
        $this->genlib->ajaxOnly();
        
        $this->load->helper('text');
        
        //set the sort order
        $orderBy = $this->input->get('orderBy', TRUE) ? $this->input->get('orderBy', TRUE) : "status_number";
        $orderFormat = $this->input->get('orderFormat', TRUE) ? $this->input->get('orderFormat', TRUE) : "ASC";
        
        //count the total number of items in db
        $totalLoans = $this->db->count_all('loans');
        
        $this->load->library('pagination');
        
        $pageNumber = $this->uri->segment(3, 0);//set page number to zero if the page number is not set in the third segment of uri
	
        $limit = $this->input->get('limit', TRUE) ? $this->input->get('limit', TRUE) : 10;//show $limit per page
        $start = $pageNumber == 0 ? 0 : ($pageNumber - 1) * $limit;//start from 0 if pageNumber is 0, else start from the next iteration
        
        //call setPaginationConfig($totalRows, $urlToCall, $limit, $attributes) in genlib to configure pagination
        $config = $this->genlib->setPaginationConfig($totalLoans, "loans/laca_", $limit, ['onclick'=>'return loans(this.href);']);
        
        $this->pagination->initialize($config);//initialize the library class
        
        // load currencies
        $data['loan_unit_icons'] = $this->load_loan_unit_icons();
        $data['collateral_unit_icons'] = $this->load_collateral_unit_icons();

        $status_number = $this->loan->get_status_number("CANCELLED");
        //get all items from db
        $data['canLoans'] = $this->loan->getAll($status_number, $orderBy, $orderFormat, $start, $limit);
        $data['range'] = $totalLoans > 0 ? "Showing " . ($start+1) . "-" . ($start + count($data['canLoans'])) . " of " . $totalLoans : "";
        $data['links'] = $this->pagination->create_links();//page links
        $data['sn'] = $start+1;
        
        $json['loansList'] = $this->load->view('admin/loans/cancelledloanlist', $data, TRUE);//get view with populated items table

        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }

    /*
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    */

    /**
     * 
     */
    public function update(){
        $this->genlib->ajaxOnly();
        
        $this->load->library('form_validation');

        $this->form_validation->set_error_delimiters('', '');
        
        $this->form_validation->set_rules('userId', 'User', ['required'], ['required'=>"required"]);
        $this->form_validation->set_rules('statusNumber', 'Status', ['required']);
        $this->form_validation->set_rules('loanUnitId', 'Loan Unit', ['required']);
        $this->form_validation->set_rules('loanAmount', 'Loan Amount', ['required']);
        $this->form_validation->set_rules('collateralUnitId', 'Collateral Unit', ['required']);
        $this->form_validation->set_rules('collateralAmount', 'Collateral Amount', ['required']);
        $this->form_validation->set_rules('duration', 'Collateral Amount', ['required']);
        $this->form_validation->set_rules('loanId', 'Loan', ['required']);
        
        if($this->form_validation->run() !== FALSE){
            /**
             * update info in db
             */
                
            $id = $this->input->post('loanId', TRUE);

            $updated = $this->loan->update($id, set_value('userId'), set_value('statusNumber'), set_value('loanUnitId'), set_value('loanAmount'), set_value('collateralUnitId'), set_value('collateralAmount'), set_value('duration'));
            
            
            $json = $updated ? 
                    ['status'=>1, 'msg'=>"Loan successfully updated"] 
                    : 
                    ['status'=>0, 'msg'=>"Oops! Unexpected server error! Pls contact administrator for help. Sorry for the embarrassment"];
        }
        
        else{
            //return all error messages
            $json = $this->form_validation->error_array();//get an array of all errors
            
            $json['msg'] = "One or more required fields are empty or not correctly filled";
            $json['status'] = 0;
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }
    
    public function delete(){
        $this->genlib->ajaxOnly();
        
        $bank_id = $this->input->post('_bId');
        $new_value = $this->genmod->gettablecol('banks', 'deleted', 'id', $bank_id) == 1 ? 0 : 1;
        
        $done = $this->bank->delete($bank_id, $new_value);
        
        $json['status'] = $done ? 1 : 0;
        $json['_nv'] = $new_value;
        $json['_bId'] = $bank_id;
        
        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }

    public function approve(){
        $this->genlib->ajaxOnly();
        $approved_status_number = $this->loan->get_status_number("APPROVED");
        $requested_status_number = $this->loan->get_status_number("REQUESTED");
        $loan_id = $this->input->post('_lId');
        $new_value = $this->genmod->gettablecol('loans', 'status_number', 'id', $loan_id) == $approved_status_number ? $requested_status_number : $approved_status_number;

        $updated = $this->loan->approve_loan($loan_id, $_SESSION['admin_id'], $new_value);
        
        
        $json = $updated ? 
                ['status'=>1, 'msg'=>"Loan approved", '_lid'=>$loan_id] 
                : 
                ['status'=>0, 'msg'=>"Oops! Unexpected server error! Pls contact administrator for help. Sorry for the embarrassment"];
                    
        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }

    public function deny(){
        $this->genlib->ajaxOnly();
        $denied_status_number = $this->loan->get_status_number("DENIED");
        $requested_status_number = $this->loan->get_status_number("REQUESTED");
        $loan_id = $this->input->post('_lId');
        $new_value = $this->genmod->gettablecol('loans', 'status_number', 'id', $loan_id) == $denied_status_number ? $requested_status_number : $denied_status_number;

        $updated = $this->loan->deny_loan($loan_id, $_SESSION['admin_id'], $new_value);
        
        
        $json = $updated ? 
                ['status'=>1, 'msg'=>"Loan denied", '_lid'=>$loan_id] 
                : 
                ['status'=>0, 'msg'=>"Oops! Unexpected server error! Pls contact administrator for help. Sorry for the embarrassment"];
                    
        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }

    public function revert(){
        $this->genlib->ajaxOnly();
        $pending_status_number = $this->loan->get_status_number("PENDING");
        // $requested_status_number = $this->loan->get_status_number("REQUESTED");
        $loan_id = $this->input->post('_lId');
        // $new_value = $this->genmod->gettablecol('loans', 'status_number', 'id', $loan_id) == $denied_status_number ? $requested_status_number : $denied_status_number;
        $new_value = $pending_status_number;

        $updated = $this->loan->revert_loan($loan_id, $_SESSION['admin_id'], $new_value);
        
        
        $json = $updated ? 
                ['status'=>1, 'msg'=>"Loan reverted to pending", '_lid'=>$loan_id] 
                : 
                ['status'=>0, 'msg'=>"Oops! Unexpected server error! Pls contact administrator for help. Sorry for the embarrassment"];
                    
        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }

    public function clear(){
        $this->genlib->ajaxOnly();
        $cleared_status_number = $this->loan->get_status_number("CLEARED");
        // $requested_status_number = $this->loan->get_status_number("REQUESTED");
        $loan_id = $this->input->post('_lId');
        // $new_value = $this->genmod->gettablecol('loans', 'status_number', 'id', $loan_id) == $denied_status_number ? $requested_status_number : $denied_status_number;
        $new_value = $cleared_status_number;

        $updated = $this->loan->clear_loan($loan_id, $_SESSION['admin_id'], $new_value);
        
        
        $json = $updated ? 
                ['status'=>1, 'msg'=>"Loan cleared", '_lid'=>$loan_id] 
                : 
                ['status'=>0, 'msg'=>"Oops! Unexpected server error! Pls contact administrator for help. Sorry for the embarrassment"];
                    
        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }

    public function grant(){

        $this->genlib->ajaxOnly();
        $granted_status_number = $this->loan->get_status_number("GRANTED");
        $approved_status_number = $this->loan->get_status_number("APPROVED");
        $loan_id = $this->input->post('_lId');
        $new_value = $this->genmod->gettablecol('loans', 'status_number', 'id', $loan_id) == $granted_status_number ? $approved_status_number : $granted_status_number;

        $updated = $this->loan->grant_loan($loan_id, $_SESSION['admin_id'], $new_value);
        
        
        $json = $updated ? 
                ['status'=>1, 'msg'=>"Loan granted", '_lid'=>$loan_id] 
                : 
                ['status'=>0, 'msg'=>"Oops! Unexpected server error! Pls contact administrator for help. Sorry for the embarrassment"];
                    
        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }

    
    /*
    Creates items for use in a dropdown (to be used in form_dropdown())

    $table_name: the name of the table
    $key_field: the key
    $value_field: user friendly display data
    $blank_first: make the first item in the dropdown blank
    $order_by: order by
    $direction: direction for order by
    */
    protected function prep_select($table_name, $key_field, $value_field, $blank_first=FALSE, $order_by="", $direction="ASC") {
        // prepopulate select
        if ($blank_first) {
            $result = array(""=>"");
        }
        else {
            $result = array();
        }
        $this->db->order_by($order_by, $direction);
        $this->db->select("$key_field, $value_field");
        $query = $this->db->get($table_name);
        
        $result_array = $query->result_array();

        
        foreach ($result_array as $item) {
            $result[$item[$key_field]] = $item[$value_field];
        }

        return $result;
    }

	public function next_status($current_status)
	{
		switch ($current_status) {
			case 'PENDING':
				return 'APPROVED';
				break;

			case 'APPROVED':
				return 'GRANTED';
				break;

			case 'GRANTED':
				return 'CLEARED';
				break;

			case 'DENIED':
				return 'APPROVED';
				break;
			
			default:
				break;
		}
	}

}
