<?php
class Action extends CI_Controller {

	function delete ($table_name,$id)
	{
		$this->load->model ('bureau/Vedmodel');
		$this->Vedmodel->delete($table_name,$id);		
	}
	
		
	function edit ($table_name,$id)
	{

		$this->load->helper('form');
		$this->load->helper('array');
		$this->load->model ('bureau/Vedmodel');
		$data = array();
		$data['id'] = $id;
		$extra = array();
		$extra['id'] = $id;
		
		$id = $this->db->escape($id);
		$query = $this->db->query("Select * from $table_name where id = $id");
		$extra['query'] = $query;
		
	
	
		
		switch ($table_name)
		{
			case 'subject_groups':
			$view = 'Subject_groups_editor';
			$this->special_for_subject_groups_edit($table_name, 'Edit', $view,$extra);
			break;
			
			default:
			$view = 'Editor';
			$this->referenced($table_name, 'Edit', $view, $extra);
			break;
		}
	}
	

	
	function update ($table_name,$id)
	{

		$this->load->model ('bureau/Vedmodel');
		if($this->Vedmodel->update($_POST,$table_name,$id))
			redirect("bureau/ved/index/$table_name");
		
	}
	
	function add ($table_name)
	{

			$this->referenced($table_name,'Add','Adder');
	
	}
	
	function getter ($table_name)
	{
		$data = array();
		$query = $this->db->get($table_name);
		foreach ($query->result() as $row)
		{
			$data[$row->id] = $row->title;
		}
		
		return $data;
	}
	
	
	function referenced($table,$title,$view,$extra='')
	{
		

		$this->load->model ('bureau/Vedmodel');
		$this->load->helper('array');
		
		$this->load->helper('form');
		$this->load->model ('bureau/Vedmodel');
		$data['table_name'] = $table;
		$data['title'] = $title;
		
		$query = $this->Vedmodel->find_relationship('bureau',$table);
			
		$ref_tab = array();
		$ref_col = array();
		$referring = array();
		
		foreach ($query->result() as $item)
		{
			$ref_tab[$item->column_name] = $item->referenced_table_name;
			$ref_col[] = $item->referenced_column_name;
			$referring[$item->column_name] = $item->column_name;
			
		}
		
		$ref_data = array();
		if($query->num_rows() > 0)
		{
			foreach ($ref_tab as $col=>$tab)
			{
				$ref_data[$col] = $this->Vedmodel->get($tab,$query->num_rows()+5,$this->db->count_all($tab));
			}
		}
		
		$type_info = array();
		$types = $this->Vedmodel->data_type('bureau', $table);
		foreach ($types->result() as $type)
			$type_info[$type->col] = $type->type;
		
		$data['rel'] = $ref_data;
		$data['rel_info'] = $ref_col;
		$data['referring'] = $referring;
		$data['type_info'] = $type_info;
		
		if (isset($extra) and $extra != '')
			foreach ($extra as $index => $dat)
				$data[$index] = $dat;
		
		$this->load->view ('bureau/Header',$data);
		$this->load->view ('bureau/'.$view,$data);
		$this->load->view ('bureau/Footer');

	}
	
	function save ($table)
	{
	
		$this->load->model ('bureau/Vedmodel');
		
		$validates = $this->Vedmodel->validation_parameters ('bureau',$table);
		
		foreach ($validates->result() as $validator)
		{
			if($validator->col == 'id')
				continue;
			
			else if($validator->nul == 'NO')
			{
				$required = 'required';
				$this->form_validation->set_rules($validator->col, ucfirst($validator->col), $required);
			}
		}

		if ($this->form_validation->run() == TRUE)
		{
			if ($this->Vedmodel->save($table,$_POST))
			{
				redirect("bureau/ved/index/$table");
			}
		}
		else
		{
			
			$this->referenced ($table,'Add','Adder');
			
		}
	}
	
	function view ($table,$id)
	{

		echo "This signifies success";
	}
	
	function confirm($table,$id)
	{
		$data['table'] = $table;
		$this->db->where('id',$id);
		$data['id'] = $id;

		$result = $this->db->get($table);
		foreach($result->result() as $res)
		{
			switch($table)
			{
				case 'bu_rates':
				$data['title'] = $res->currency;
				break;
				
				default:
				$data['title'] = $res->title;
				break;
			}
		}
		
		$this->load->view('bureau/Confirm',$data);
	}
		
}