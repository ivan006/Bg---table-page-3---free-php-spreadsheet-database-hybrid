<?php
class Table_c extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();

		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		// $this->load->model('trip');
		// $this->load->library('../modules/trips/controllers/table_page_lib');
		$this->load->library('table_page_lib');




		$this->load->database();
		$this->load->library(['ion_auth', 'form_validation']);
		$this->load->helper(['url', 'language']);
		$this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));
		$this->lang->load('auth');
	}

	public function index($table)
	{
		$table = urldecode($table);
		if (!$this->ion_auth->logged_in())
		{
			// redirect them to the login page
			redirect('auth/login', 'refresh');
		}


		$erd_path = APPPATH.'erd/erd/erd.json';
		$erd= file_get_contents($erd_path);
		$erd= json_decode($erd, true);

		// $data["tab_d"]["cols"]["editable"] = $erd[$table]["fields"];
		// $data["tab_d"]["cols"]["visible"] = $erd[$table]["fields"];
		//
		//
		// $data["tab_o"]["table"] = $table;
		// $data["tab_o"]["rel_name"] = $table;
		// $data["tab_o"]["rel_name_id"] = $data["tab_o"]["rel_name"];
		// $data["tab_o"]["data_endpoint"] = "fetch";
		//
		// $data = $this->table_page_lib->table_o_and_d("overview", $erd, $table, null, $record["id"], "", $dont_scan);
		$data = $this->table_page_lib->table_o_and_d("table", $erd, $table, null, null, "", null);


		$data["owner_group_options"] = array(
			"assumed" => "no",
			"options" => $this->table_page_lib->owner_group_options()
		);


		$data['title'] = $table;
		$this->load->view('table_header_v', array("data"=>$data));
		$this->load->view('table_block_v', array("data"=>$data,"owner_group_options"=>$data["owner_group_options"]));
		$this->load->view('table_footer_v');

	}

	public function insert($table)
	{
		if (!$this->ion_auth->logged_in())
		{
			// redirect them to the login page
			redirect('auth/login', 'refresh');
		}
		$result = $this->table_page_lib->insert($table);
		header('Content-Type: application/json');
		echo json_encode($result, JSON_PRETTY_PRINT);
	}

	public function fetch_without_inheritance($table)
	{
		if (!$this->ion_auth->logged_in())
		{
			// redirect them to the login page
			redirect('auth/login', 'refresh');
		}
		$result = $this->table_page_lib->fetch_without_inheritance($table);
		header('Content-Type: application/json');
		echo json_encode($result, JSON_PRETTY_PRINT);
	}

	public function fetch($table)
	{
		if (!$this->ion_auth->logged_in())
		{
			// redirect them to the login page
			redirect('auth/login', 'refresh');
		}
		$result = $this->table_page_lib->fetch($table, "table", array());
		header('Content-Type: application/json');
		echo json_encode($result, JSON_PRETTY_PRINT);
	}

	public function fetch_for_record($table, $haystack, $needle, $foreign_key)
	{
		if (!$this->ion_auth->logged_in())
		{
			// redirect them to the login page
			redirect('auth/login', 'refresh');
		}
		$result = $this->table_page_lib->fetch($table, "record", array("haystack"=>$haystack, "needle"=>$needle, "foreign_key"=>$foreign_key));
		header('Content-Type: application/json');
		echo json_encode($result, JSON_PRETTY_PRINT);
	}

	// public function fetch_join_where($table_1, $table_2, $haystack, $needle)
	// {
	// 	if (!$this->ion_auth->logged_in())
	// 	{
	// 		// redirect them to the login page
	// 		redirect('auth/login', 'refresh');
	// 	}
	// 	$result = $this->table_page_lib->fetch_join_where($table_1, $table_2, $haystack, $needle);
	// 	header('Content-Type: application/json');
	// 	echo json_encode($result, JSON_PRETTY_PRINT);
	// }

	public function delete($table)
	{
		if (!$this->ion_auth->logged_in())
		{
			// redirect them to the login page
			redirect('auth/login', 'refresh');
		}
		$result = $this->table_page_lib->delete($table);
		header('Content-Type: application/json');
		echo json_encode($result, JSON_PRETTY_PRINT);
	}

	public function edit($table)
	{
		if (!$this->ion_auth->logged_in())
		{
			// redirect them to the login page
			redirect('auth/login', 'refresh');
		}
		$result = $this->table_page_lib->edit($table);
		header('Content-Type: application/json');
		echo json_encode($result, JSON_PRETTY_PRINT);
	}

	public function update($table)
	{
		if (!$this->ion_auth->logged_in())
		{
			// redirect them to the login page
			redirect('auth/login', 'refresh');
		}
		$result = $this->table_page_lib->update($table);
		header('Content-Type: application/json');
		echo json_encode($result, JSON_PRETTY_PRINT);
	}
}
