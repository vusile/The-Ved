	<h2>Editing <?php echo str_replace('_',' ',ucfirst($table_name)); ?></h2>
		<?php
			echo validation_errors();

			echo form_open ('bureau/action/update/' . $table_name .'/'.$id);

			$fields = $query->list_fields();
			$ref_fields = array ();
			foreach ($rel as $col => $fields_list)
				$ref_fields[$col] = $fields_list->list_fields();

			foreach ($query->result() as $item)
			{
				foreach ($fields as $field)
				{
					if($field == 'id')
						continue;
					else
						$disabled = '';
						
					echo '<label>' . str_replace('_',' ',ucfirst($field)) .'</label>';
					if(element($field, $referring))
					{
						$options = '';
						$options .= "<select name = '" . $referring[$field] . "'>";
						foreach ($rel[$field]->result() as $referenced)
						{
							if($item->$field == $referenced->id)
								$selected = 'selected';
							else
								$selected = '';
							
							$options .= "<option $selected value = '" . $referenced->id ."' >" . $referenced->$ref_fields[$field][1] . '</option>';
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
							case 'last_modified_date':
							$value = date ('d-m-Y',strtotime($item->$field));
							$disabled = 'readonly';
							break;
							
							case 'birthday':
							$value = date ('d-m-Y',strtotime($item->$field));
							break;
							
							case 'last_modified_by':
							$value = $users[$item->$field];
							break;
							
							case 'admission_number':
							case 'application_number':
							$disabled = 'readonly';
							$value = $item->$field;
							break;
							
							default:
							$value = $item->$field;
							$disabled = '';
							break;
						}
							
						if($type_info[$field] == 'text')
							echo "<textarea name = '$field' >".$value ."</textarea><br />";
						else
						{
							
							echo "<input type = 'text' $disabled name = '" . $field . "' value = '" .$value  . "' /><br />";
						}
					}	
				}
			}
		?>
		<input type = 'submit' value = 'Save' />
		</form>