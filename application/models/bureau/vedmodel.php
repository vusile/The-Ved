<?php
class Vedmodel extends CI_Model{
	function get_data($table_name, $num, $offset)
	{
		
		$query = $this->db->get ($table_name, $num, $offset);
		return $query;
	}	
	//get_data and get are essentially the same thing
	function get($table_name)
	{
		$query = $this->db->query("select * from $table_name");
		
		return $query;
	}
	
	function update ($data, $table_name,$id)
	{

		$fields = $this->db->list_fields($table_name);
		$set = '';
		foreach ($data as $field => $datum)
		{
			switch ($field)
			{
				case 'password':
				$data[$field] = sha1($datum);
				break;
				
				
				case 'last_modified_by':			
				$data[$field] = $this->session->userdata('userid');
				break;
				
				case 'added_date':
				case 'birthday':
				$data[$field]= date('Y-m-d',strtotime($datum));
				break;
				
				case 'last_modified_date':
				$data['last_modified_date'] = date('Y-m-d');
		
			}
		}
		
		

		foreach ($fields as $field)
		{
			if ($field == 'id')
				continue;
			$set .= $field . '=' . $this->db->escape($data[$field]) . " , "; 
		}
		$set = substr($set,0,-2);
		if($query = $this->db->query ("update $table_name set $set where id = $id"))
			return true;
		
	}
	
	function find_relationship ($schema, $table_name)
	{
		$query = $this->db->query ("SELECT u.referenced_table_schema, u.referenced_table_name,
			u.referenced_column_name, u.column_name 
			FROM information_schema.table_constraints AS c
			INNER JOIN information_schema.key_column_usage AS u
			USING ( constraint_schema, constraint_name ) 
			WHERE c.constraint_type =  'FOREIGN KEY'
			AND c.table_schema =  '$schema'
			AND c.table_name =  '$table_name'
			LIMIT 0 , 30"
		);
		
		return $query;
	}
	
	function validation_parameters ($schema, $table_name)
	{
		$query = $this->db->query ("SELECT i.is_nullable as nul,i.column_name as col from information_schema.columns as i where  i.table_schema = '$schema' and i.table_name = '$table_name' ");

		return $query;
	}
	
	function data_type ($schema, $table_name)
	{
		$query = $this->db->query ("SELECT i.column_name as col,i.data_type as type from information_schema.columns as i where  i.table_schema = '$schema' and i.table_name = '$table_name' ");

		return $query;
	}
	
	function save ($table, $data)
	{	
		foreach ($data as $field => $datum)
		{
			switch ($field)
			{
				case 'password':
				$data[$field] = sha1($datum);
				break;
			
				case 'added_by':				
				$data[$field] = $this->session->userdata('userid');
				break;
			
				case 'added_date':
				$data[$field]= date('Y-m-d',strtotime($datum));
				break;
			
				case 'birthday':
				$data[$field]=date('Y-m-d',strtotime($datum));
				break;
			
			}
		}
		
		$query_str = $this->db->insert_string($table,$data);
		if($this->db->query($query_str))
			return true;
	}
	
	function array_getter($table_name,$columns='', $where = '')
	{
		if($columns != '')
		{
			
			$cols = '';
			foreach ($columns as $column)
			{
				$cols .= $column .',';
			}
			$cols = substr ($cols,0,-1);
			
			$this->db->select ($cols);
		}
		
		if($where != '')
		{
		
			
			foreach ($where as $key=>$value)
			{
				$col =$key;
				$val = $value;
			}
			$this->db->where($col, $val);
		}
		
		$result=$this->db->get($table_name);
		$to_return = array();
		foreach($result->result() as $row)
		{
			
			$to_return[] = $row;
		}
		
		return $to_return;
	
	}
	
	function delete($table_name,$id)
	{
		$id = $this->db->escape($id);
		if($this->db->query ("Delete from $table_name where id = $id"))
			redirect("bureau/ved/index/$table_name");

	}
}