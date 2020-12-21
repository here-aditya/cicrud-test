<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */

	public function __construct()
	{
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->helper('form');
		$this->load->model('employee_model');
	}


	public function index()
	{
		//$this->output->enable_profiler(TRUE);
		/*echo $this->config->item('index_page');
		$sections = array(
			'config'  => TRUE,
			'queries' => TRUE
		);
		$this->output->set_profiler_sections($sections);
		$this->output->enable_profiler(FALSE);
		echo FCPATH;*/
		$this->load->helper('url');
		//echo base_url();
		$this->load->view('welcome_message');
	}


	public function showEmployees()
	{
		$filter_data = $this->input->post();
		//echo '<pre>'; print_r($filter_data);
		$employee_data = $this->employee_model->fetchEmployees(null, $filter_data);
		echo $table_view = $this->load->view('employee_table', array('emp_data' => $employee_data), true);
	}

	public function deleteEmployee($empId)
	{
		$this->employee_model->unlinkEmployee($empId);
		$this->showEmployees();
	}

	public function getEmployee($empId)
	{
		$emp_data = $this->employee_model->fetchEmployees($empId);
		echo json_encode($emp_data);
	}

	public function saveEmployee($empId=0)
	{
		$this->form_validation->set_error_delimiters('', '<br>');
		$this->form_validation->set_rules('emp_f_name','First Name', 'required|min_length[3]');
		$this->form_validation->set_rules('emp_l_name','Last Name', 'required|min_length[3]');
		$this->form_validation->set_rules('emp_dept','Department','required');
		$this->form_validation->set_rules('emp_age','Age','required|numeric');
		if ($this->form_validation->run() == FALSE) {
			echo json_encode(['status' => 'error', 'error_details' => validation_errors()]);
			die;
		} 
		
		$post_data = $this->input->post();
		$data = [];
		$data['f_name'] = $post_data['emp_f_name'];
		$data['l_name'] = $post_data['emp_l_name'];
		$data['dept_code'] = $post_data['emp_dept'];
		$data['age'] = $post_data['emp_age'];
		if($empId > 0) {
			$this->employee_model->updateEmployee($empId, $data);
		} else {
			$this->employee_model->addEmployee($data);
		}
		
		$employee_data = $this->employee_model->fetchEmployees();
		$table_view = $this->load->view('employee_table', array('emp_data' => $employee_data), true);
		echo json_encode(['status' => 'success', 'success_data' => $table_view]);
	}
}
