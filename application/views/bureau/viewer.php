<div style = "float: left; clear: none;">
<h1>Currencies Table - <?php echo anchor ('bureau/action/add/'.$table_name, 'Add'); ?></h1>
<table border = "1">
<tr><!--table headers-->
<?php
	$fields = $data->list_fields();
	$field_arr  = array();
	$j = 0;
	foreach ($fields as $field)
	{
		if($j==9)
			break;
		echo '<th style = \'color: red;\'>'.str_replace('_',' ',ucfirst($field)).'</th>';
		$field_arr[] = $field;
		$j++;
	}
	echo '<th>Edit</td>';
	echo '<th>Delete</td>';
?>	
</tr>
<?php	
		
	foreach ($data->result() as $item)
	{
		
		echo '<tr onMouseOver = "this.bgColor = \'#d3d3d3\'" onMouseOut = "this.bgColor = \'#ffffff\'">';
		$i = 0;
		if($data->num_fields() >= 9)
			$num_fields = 9;
		else
			$num_fields = $data->num_fields();
		
		while ( $i < $num_fields )
		{
			if ($type_info[$field_arr[$i]] == 'date')
				$value = date('d-m-Y',strtotime($item->$field_arr[$i]));
			else 
			{
				switch ($field_arr[$i])
				{
					case 'level':
					$value = $this->session->userdata('levels');
					$value = $value[$item->$field_arr[$i]];
					break;
					
					case 'stream':
					$value = $this->session->userdata('streams');
					$value = $value[$item->$field_arr[$i]];
					break;
					
					case 'class':
					$value = $this->session->userdata('classes');
					$value = $value[$item->$field_arr[$i]];
					break;
					
					case 'status':
					$value = $this->session->userdata('statuses');
					$value = $value[$item->$field_arr[$i]];
					break;
					
					case 'subject_group':
					$value = $this->session->userdata('subject_groups');
					
					$value = $value[$item->$field_arr[$i]];
					break;
					
					case 'special':
					$value = $this->session->userdata('binaries');
					$value = $value[$item->$field_arr[$i]];
					break;
					
					default:
					$value = $item->$field_arr[$i];
					break;
				}
				$location = substr(anchor ('bureau/action/view/' . $table_name . '/' . $item->$field_arr[0], 'Edit'),9,-10);
				echo "<td onClick = \"parent.location= '".$location."'\">$value</td>";
				$i++;
			}
		}
		
		$attributes = array ('rel'=>'facebox');
	
		$userdata = array ('uri'=> $this->uri->uri_string());
	

		echo '<td>'. anchor ('bureau/action/edit/' . $table_name . '/' . $item->$field_arr[0], 'Edit') .'</td>';
		echo '<td>'. anchor ('bureau/action/confirm/' . $table_name . '/' . $item->$field_arr[0], 'Delete',$attributes) .'</td>';
		echo '</tr>';
	}
?>
</table>
<?php echo $this->pagination->create_links(); ?>
</div>