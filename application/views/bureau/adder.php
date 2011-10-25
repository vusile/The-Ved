	<h2>Adding New Currency</h2>

		<?php
				
			echo validation_errors(); 
			$attributes = array ('id'=>'form');
			
			echo form_open ('bureau/action/save/'.$table_name,$attributes);

	
			$fields = $this->db->list_fields($table_name);
			$ref_fields = array ();
			foreach ($rel as $col => $fields_list)
				$ref_fields[$col] = $fields_list->list_fields();

				foreach ($fields as $field)
				{
					if($field == 'id')
						//$disabled = 'readonly';
						continue;
					else
						$disabled = '';
					echo '<div class = \'row\'>';
					echo '<div class = \'column1\'>';
					echo '<label>' . str_replace('_',' ',ucfirst($field)) . ':</label>';
					echo '</div>';
					echo '<div class = \'column2\'>';
					if(element($field, $referring))
					{
						$options = '';
						$options .= "<select name = '" . $referring[$field] . "'>";
						foreach ($rel[$field]->result() as $referenced)
						{
							$options .= "<option value = '" . $referenced->id ."' >" . $referenced->$ref_fields[$field][1] . "</option>";
						}
						$options .= '</select><br />';
						echo $options;
					}
					else
					{
						switch ($field)
						{
							case 'added_by':
							$value = $this->session->userdata('username');
							$disabled = 'readonly';
							break;
							
							case 'added_date':
							$value = date ('d-m-Y');
							$disabled = 'readonly';
							break;
							
							case 'last_modified_by':
							case 'last_modified_date':
							case 'admission_number':
							$disabled = 'readonly';
							$value = '';
							break;
							
							default:
							$value = set_value($field);
							break;
						}
						
						if($type_info[$field] == 'text')
							echo "<textarea name = '$field' >$value</textarea><br />";
						else
							echo "<input type = 'text' $disabled name = '" . $field . "' value = '$value' /><br />";
						
					}
					echo '</div>';
					echo '</div>';
				}
			
		?>
		<div class = 'row'>
		<div class = 'submit'>
		<input type = 'submit' value = 'Save' />
		</div>
		<div class = 'reset'>
		<input type = 'reset' value = 'Reset' />
		</div>
		</div>
		<!--</div>-->
		</form>
		