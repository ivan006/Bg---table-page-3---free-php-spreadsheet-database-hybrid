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

		// $this->load->library('table_page_lib');
		// $this->load->library('erd_lib');

		// $this->load->library(
		// 	'table_page_lib',
		// 	'erd_lib'
		// );




		$this->load->database();
		$this->load->library([
			'ion_auth',
			'form_validation',
			'table_page_lib',
			'erd_lib',
		]);
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


		// $data = $this->table_page_lib->table_abilities($table);
		$data = $this->table_page_lib->postcalculated_table_for_table($table);
		// header('Content-Type: application/json');
		// echo json_encode($data, JSON_PRETTY_PRINT);
		// exit;

		$permisssion_options = array(
			"owner" => array(
				"assumed" => "",
				"options" => $this->table_page_lib->user_groups_for_dropdown()
			),
			// "assignbility" => array(
			// 	"assumed" => "",
			// 	"options" => array(
			// 		"Private",
			// 		// "Organisation",
			// 		"Public"
			// 	)
			// ),
			"editability" => array(
				"assumed" => "",
				"options" => array(
					array(
						"label"=>"Private",
						"value"=>"pr",
					),
					// array(
					// 	"label"="Organisation",
					// 	"value"="1",
					// ),
					array(
						"label"=>"Public",
						"value"=>"pu",
					),
				)
			),
			"visibility" => array(
				"assumed" => "",
				"options" => array(
					array(
						"label"=>"Private",
						"value"=>"pr",
					),
					// array(
					// 	"label"="Organisation",
					// 	"value"="1",
					// ),
					array(
						"label"=>"Public",
						"value"=>"pu",
					),
				)
			),
		);

		$this->load->view('table_header_v', array(
			"data"=>$data,
			"type"=>"g_table_core_abilities"
		));

		$this->load->view('table_block_v', array(
			"data"=>$data["g_core_abilities"],
			"permisssion_options"=>$permisssion_options,
			"type"=>"g_table_core_abilities"
		));
		$this->load->view('table_footer_v', array("data"=>$data));

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

		// $groups = $this->ion_auth->get_users_groups();
		// $groups = $groups->result_array();
		$groups = $this->table_page_lib->user_groups();

		// header('Content-Type: application/json');
		// echo json_encode($groups, JSON_PRETTY_PRINT);
		// exit;
		$groups = array_column($groups, "id");



		$result = $this->table_page_lib->fetch($table, "table", array(), $groups);
		header('Content-Type: application/json');
		echo json_encode($result, JSON_PRETTY_PRINT);
	}

	public function fetch_for_record($table, $h_type, $haystack, $needle)
	{
		if (!$this->ion_auth->logged_in())
		{
			// redirect them to the login page
			redirect('auth/login', 'refresh');
		}



		$groups = $this->ion_auth->get_users_groups();
		$groups = $groups->result_array();
		$groups = array_column($groups, "id");

		$result = $this->table_page_lib->fetch(
			$table,
			"record",
			array(
				"haystack"=>$haystack,
				"needle"=>$needle,
				// this varable isnt really needed all it does is says not to bother checking if the pimrary key is ever used as a foreign key which it never will be so uselless

				"haystack_type"=>$h_type

			),
			$groups
		);
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

		$table = urldecode($table);
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
