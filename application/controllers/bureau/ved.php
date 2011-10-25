<?php
class Ved extends CI_Controller {
	function index($table)
	{
		$this->load->library('pagination');
		$config['base_url'] = base_url().'index.php/general/ved/index/'.$table;
		$config['total_rows'] = $this->db->count_all($table);
			
		$config['per_page'] = 10;
		$config['uri_segment'] = 5;
		
		$this->pagination->initialize($config);
	
		$data['title'] = 'Home';
		$datas = array();
		$table_name = $table;
		$this->load->model('bureau/Vedmodel');
		
		$datas['data'] = $this->Vedmodel->get_data($table_name, $config['per_page'],$this->uri->segment(5));
		$datas['table_name'] = $table_name;
		$type_info = array();
		$types = $this->Vedmodel->data_type('bureau',$table_name);
		foreach ($types->result() as $type)
			$type_info[$type->col] = $type->type;
		$datas['type_info'] = $type_info;
		$this->load->library('table');
		$this->load->view('bureau/Header',$data);
		$this->load->view('bureau/Home',$data);	
		$this->load->view('bureau/Viewer',$datas);
		$this->load->view('bureau/Footer');
		
	}
}