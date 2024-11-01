<?php
class SJFormHelper
{
	public static
	function createSelectHTML($array_select, $key_selected = '', $name =
		'', $id = '', $class = '')
	{

		ob_start();
		?>
		<select <?php echo !empty($name) ? "name=" . esc_attr($name) . "\t" : '';
		echo !empty($id) ? "id=" . esc_attr($id) . "\t" : '';
		echo !empty($class) ? "class=" . esc_attr($class) . "\t" : ''; ?> >
			<?php
			foreach($array_select as $key => $value){

				if($key == $key_selected){
					?>
					<option value=<?php echo esc_attr($key) ?> SELECTED>
						<?php echo esc_attr($value) ?>
					</option>
					<?php
				}
				else
				{
					?>
					<option value=<?php echo esc_attr($key) ?>>
						<?php echo esc_attr($value) ?>
					</option>

					<?php
				}
			}
			?>
		</select>
		<?php
		$c = ob_get_clean();

		return $c;

	}

	public static
	function close_fieldset()
	{
		echo '</FIELDSET>';

	}


	public static
	function form_start_html($name,$id = "", $class = "admin-form",$enc_type = '')
	{
		ob_start();
		?>

		<form method="post" action="<?php echo esc_url($_SERVER["REQUEST_URI"]); ?>" <?php
		if(!empty($name)) echo 'name='.SJ_NAMESPACE_FOR_CSS_DIV.esc_attr($name).'" ';
		if($id != null)			echo 'id="'.SJ_NAMESPACE_FOR_CSS_DIV.esc_attr($id).'" ';
		else        echo 'id="'.SJ_NAMESPACE_FOR_CSS_DIV.esc_attr($name).'" ';
		if(!empty($class) != null)			echo 'class="'.SJ_NAMESPACE_FOR_CSS_DIV.esc_attr($class).'" ';
		if(!empty($enc_type) != null)			echo 'enctype="'.esc_attr($enc_type).'"';
		?>>
		<?php
		echo ob_get_clean();
	}

	public static
	function form_start_html_new($form_arr)
	{
		extract($form_arr);
		ob_start();
		?>

		<form method="post" action="<?php echo empty($action_url)? esc_url($_SERVER["REQUEST_URI"]):esc_url($action_url); ?>" <?php
		if(!empty($name)) echo 'name='.SJ_NAMESPACE_FOR_CSS_DIV.esc_attr($name).'" ';
		if(!empty($id))	echo 'id="'.SJ_NAMESPACE_FOR_CSS_DIV.esc_attr($id).'" ';
		else        echo 'id="'.SJ_NAMESPACE_FOR_CSS_DIV.esc_attr($name).'" ';
		if(!empty($class) != null)			echo 'class="'.SJ_NAMESPACE_FOR_CSS_DIV.esc_attr($class).'" ';
		if(!empty($enc_type) != null)			echo '"enctype="'.esc_attr($enc_type).'"';
		?>>
		<?php
		echo ob_get_clean();
	}

	public static
	function show_form_start_html($action_url = '',$method = 'post',$id = "", $class = "",$enc_type = '')
	{
		if(empty($action_url))			$action_url = $_SERVER["REQUEST_URI"];
		ob_start();
		?>

		<form method="<?php echo $method ?>" action="<?php echo esc_url($action_url); ?>" <?php
		if($id != null)			echo "id=\"".esc_attr($id)."\"" . ' ';
		if($class != null)			echo "class=\"".esc_attr($class)."\"" . ' ';
		if($enc_type != null)			echo "enctype=\"multipart/form-data\"";
		?>>

		<?php
		echo ob_get_clean();
	}


	public static
	function div_start($div_id = '', $div_class = '',$echo = true)
	{
		ob_start();
		?>
		<div <?php
		if(!empty($div_id)) echo 'id="'.SJ_NAMESPACE_FOR_CSS_DIV.esc_attr($div_id).'"' ;
		if(!empty($div_class)) echo 'class="'.SJ_NAMESPACE_FOR_CSS_DIV.esc_attr($div_class).'"' ;?>>
		<?php

		if($echo == true)		 echo ob_get_clean();
		else return ob_get_clean();
	}
	public static
	function div_close($div_close_comment = '')
	{
		echo '</div>';
		if(!empty($div_close_comment))		 echo call_user_func_array(array("SJFormHelper","show_html_comment"),array($div_close_comment));
	}
	public static
	function form_close_html($name = '')
	{
		$str = "</form>";
		if(!empty($name)){
			$str .= "<!--Form name = \"".esc_attr($name)."\"";
			$str .= "-->";

		}
		echo $str;

	}
	public static
	function start_fieldset($legend, $name = '', $id = '', $class = '')
	{
		//echo "<fieldset name=\"".esc_attr($name)."\" $id=\"".esc_attr($id)."\" $class=\"{$class}\">";
		//echo "<LEGEND>{$legend}</LEGEND>";
	}

	public static
	function button_html($class = '', $value = '', $button_text = '',$id='')
	{
		
		echo "<button id=\"".esc_attr($id)."\" type=\"submit\" name=\"submit\" class=\"".esc_attr($class)."\" value=".esc_attr($value).">".esc_attr($button_text)."</button>";
	}

	public static
	function string_in_string($haystack,$needle)
	{
		if(strpos($haystack, $needle) !== false)			return true;
		else return false;


	}

	public static
	function form_validation($name,$value,$rules)
	{

		$errors = array();
		$rules = explode('|',$rules);

		if(!empty($rules))
		foreach($rules as $rule){

			switch($rule){

				case 'trim':
				$value = trim($value);
				break;
				case 'null':
				if(empty($value))					$errors[] = "{$name} cannot be null ";
				break;
				default:
				break;
			}

		}


	}
	public static
	function show_html_comment($comment)
	{

		echo "<!---- ".esc_html($comment)." ------>";

	}
	public static
	function show_heading($heading_type,$heading_text)
	{
		echo "<".esc_attr($heading_type).">".esc_attr($heading_text)."</".esc_attr($heading_type).">";
	}

	public
	function input_text_field_html(
		$field_name,
		$field_label,
		$field_id,
		$field_value = '',
		$field_class = '',
		$style = '',
		$size = '',
		$helper_text = '',
		$echo = true
	)
	{
		ob_start();
		?>

		<div class="share-juice-label-and-field">
			<label for="<?php echo esc_attr($field_id) ?>">
				<?php echo esc_attr($field_label) ?>
			</label>
			<input type ="text"
			name="<?php echo esc_attr($field_name) ?>"
			id="<?php echo esc_attr($field_id) ?>"
			value="<?php echo esc_attr($field_value) ?>"
			<?php
			if(!empty($field_class)) echo "class= \"".esc_attr($field_class)."\"" ?>
			<?php
			if(!empty($size)) echo "size=\"".esc_attr($size)."\"" ?>/>
			<?php
			if(!empty($helper_text)) echo "<span class=\"helper\">".esc_attr($helper_text)."</span>" ;?>
		</div>


		<?php

		if($echo == true)			echo ob_get_clean();
		else		return ob_get_clean();
	}
	public
	function input_hidden_field_html($field_name,
		$field_id,
		$field_value = '',
		$field_class = '',
		$echo = true)
	{
		ob_start();
		?>

		<input type ="hidden"
		name="<?php echo esc_attr($field_name) ?>"
		id="<?php echo esc_attr($field_id) ?>"
		value="<?php echo esc_attr($field_value) ?>"
		<?php
		if(!empty($field_class)) echo "class= \"".esc_attr($field_class)."\"" ?> />

		<?php

		if($echo == true)			echo ob_get_clean();
		else		return ob_get_clean();
	}
	public
	function input_checkbox_field_html(
		$field_name,
		$field_label,
		$field_id,
		$field_checked = false,
		$field_class = '',
		$style = '',
		$helper_text = '' ,
		$echo = true)
	{
		ob_start();
		?>

		<div class="share-juice-label-and-field">
			<label for="<?php echo esc_attr($field_id) ?>">
				<?php echo esc_attr($field_label) ?>
			</label>
			<input type ="checkbox"
			name="<?php echo esc_attr($field_name) ?>"
			id="<?php echo esc_attr($field_id) ?>"
			<?php echo isset($field_checked) && $field_checked == true ?"Checked":false?>
			<?php
			if(!empty($field_class)) echo "class= \"".esc_attr($field_class)."\"" ?> />
			<?php
			if(!empty($helper_text)) echo "<span class=\"helper\">".esc_attr($helper_text)."</span>" ;?>
		</div>
		<?php
		if($echo == true)			echo ob_get_clean();
		else		return ob_get_clean();
	}
	public
	function input_radio_group_field_html(
		$field_name,
		$field_label,
		$field_id,
		$radio_group_key_values_array,
		$radio_group_key_checked,
		$field_class = '',
		$style = '',
		$helper_text = '' ,
		$echo = true)
	{
		ob_start();
		?>

		<div class="share-juice-label-and-field">
			<label for="<?php echo esc_attr($field_id) ?>">
				<?php echo esc_attr($field_label) ?>
			</label>
			<?php
			foreach($radio_group_key_values_array as $key=>$value){
				?>
				<input type ="radio"
				name="<?php echo esc_attr($field_name) ?>"
				id="<?php echo esc_attr($field_id) ?>"
				value="<?php echo esc_attr($key) ?>"
				<?php echo $radio_group_key_checked == $key ?"Checked":false?>
				<?php
				if(!empty($field_class)) echo "class= \"".esc_attr($field_class)."\"" ?> /><?php echo $value ?>
				<?php
			}?>
			<?php
			if(!empty($helper_text)) echo "<span class=\"helper\">".esc_attr($helper_text)."</span>" ;?>
		</div>
		<?php
		if($echo == true)			echo ob_get_clean();
		else		return ob_get_clean();
	}

	public static
	function field_input_html($field_array)
	{

		$msg = self::check_field_array($field_array);
		if(!empty($msg)){
			var_dump($msg);

			return;
		}
		$echo = true;
		$enclosing_class    = "admin-label-and-field";
		$label_before_field = false;
		//override the echo above if supplied
		extract($field_array,EXTR_OVERWRITE);

		$field_name_with_encl_arr_name = !empty($name_array)? $name_array.'['.$name.']':$name;
		if(!isset($id))
		{
			if(!empty($name_array))
			 $id = $name_array.'['.$name.']';
		}
		
		if(!isset($value)){
			if($type == "checkbox"){
				$value = isset($get_value_from_array[$name]) && ($get_value_from_array[$name] == true)? true:false;
			}
			else
			{
				$value = isset($get_value_from_array[$name])?$get_value_from_array[$name]:'';
			}


		}


		//attach namespace to div class and ids
		$id              = "share-juice-".esc_attr($id);
		$enclosing_class = "share-juice-".esc_attr($enclosing_class);
		$class           = !empty($class)? "share-juice-".esc_attr($class):'';


		ob_start();
		if($type == "text"){

			?>

			<div class="<?php echo esc_attr($enclosing_class); ?>">
				<label for="<?php echo esc_attr($id) ?>">
					<?php echo esc_attr($label).' :' ;?>
				</label><input type ="text"	name="<?php echo esc_attr($field_name_with_encl_arr_name); ?>" <?php
				if(!empty($value)) echo "value=\"".esc_attr($value)."\"".' ';
				if(!empty($class)) echo "class= \"".esc_attr($class)."\"".' '; ;
				if(!empty($id)) echo "id= \"".esc_attr($id)."\"".' '; ?>
				<?php
				if(!empty($size)) echo "size=\"".esc_attr($size)."\"".' '; ?>/>
				<?php
				if(!empty($helper_text)) echo "<span class=\"helper\">".wp_kses($helper_text,wp_kses_allowed_html('data'))."</span>".' '; ;?>
			</div>

			<?php
		}
		elseif($type == "hidden"){
			?>
			<div class="<?php echo esc_attr($enclosing_class); ?>">
				<input type ="hidden"	name="<?php echo esc_attr($field_name_with_encl_arr_name); ?>"<?php
				if(!empty($value)) echo "value=\"".esc_attr($value)."\"".' ';
				if(!empty($class)) echo "class= \"".esc_attr($class)."\"".' '; ;
				if(!empty($id)) echo "id= \"".esc_attr($id)."\"".' '; ?>
				<?php
				if(!empty($size)) echo "size=\"".esc_attr($size)."\"".' '; ?>/>
				<?php
				if(!empty($helper_text)) echo "<span class=\"helper\">"
				.wp_kses($helper_text,wp_kses_allowed_html('data'))
				."</span>".' '; ;?>
			</div>
			<?php
		}

		elseif($type == "checkbox"){

			?>
			<div class="<?php echo esc_attr($enclosing_class); ?>">
<!--<label for="<?php echo $id ?>">
					<?php echo $label.' :' ;?><?php echo '<a href="" class="share-juice-helper-link"><img src="'.SJ_IMG_URL.'/helper.png"></a>';?>
				</label>-->
				<?php
				if($label_before_field == true){
					?>
					<label for="<?php echo esc_attr($id) ?>">
						<?php echo esc_attr($label).' :' ;?>
					</label><?php
				}?><input type ="checkbox"	name="<?php echo esc_attr($field_name_with_encl_arr_name); ?>"<?php
				if(!empty($value) && $value == TRUE) echo "CHECKED".' ';
				if(!empty($class)) echo "class= \"".esc_attr($class)."\"".' '; ;
				if(!empty($id)) echo "id= \"".esc_attr($id)."\"".' '; ?>
				<?php
				if(!empty($size)) echo "size=\"".esc_attr($size)."\"".' '; ?>/><?php
				if($label_before_field == false){
					?>
					<label class="<?php echo SJ_NAMESPACE_FOR_CSS_DIV."admin-side-label"; ?>" for="<?php echo esc_attr($id) ?>">
						<?php echo esc_attr($label) ; ?>
					</label>
					<?php
				}
				if(!empty($helper_text)) echo "<span class=\"helper\">".wp_kses($helper_text,wp_kses_allowed_html('data'))."</span>".' ' ;?>
			</div>
			<?php
		}
		if($type == "select"){
			if(isset($selected_value))
			 $value = $selected_value;
			?>
			<div class="<?php echo esc_attr($enclosing_class); ?>">
				<label for="<?php echo esc_attr($id) ?>">
					<?php echo esc_attr($label).' :' ;?>
				</label>
				<?php
				echo self::createSelectHTML($select_array,$value,$field_name_with_encl_arr_name,$id,$class);
				if(!empty($helper_text)) echo "<span class=\"helper\">".wp_kses($helper_text,wp_kses_allowed_html('data'))."</span>".' '; ;?>
			</div>
			<?php

		}
		elseif($type == "textarea"){

		if(isset($field_has_html_text) && $field_has_html_text == true)
		 	
			?>
			<div class="<?php echo esc_attr($enclosing_class); ?>">
				<label for="<?php echo esc_attr($id) ?>">
					<?php echo esc_attr($label).' :' ;?>
				</label>
				<textarea name="<?php echo esc_attr($field_name_with_encl_arr_name); ?>" <?php
				if(!empty($class)) echo "class= \"".esc_attr($class)."\"".' '; ;
				if(!empty($id)) echo "id= \"".esc_attr($id)."\"".' '; ?>
				<?php
				if(!empty($cols)) echo "cols=\"".esc_attr($cols)."\"".' ';
				if(!empty($rows)) echo "rows=\"".esc_attr($rows)."\"".' ';
				?><?php echo ">". stripslashes(esc_textarea($value))."</textarea>";//this is to be done to avoid whitespaces introduced by formatting in the code editor
				if(!empty($helper_text)) echo "<span class=\"helper\">".wp_kses($helper_text,wp_kses_allowed_html('data'))."</span>".' '; ;?>
			</div>
			<?php
		}
		elseif($type == "radio"){

			?>
			<div class="<?php echo esc_attr($enclosing_class); ?> share-juice-admin-radio">
					<label for="<?php echo esc_attr($id) ?>">
					<?php echo esc_attr($label).' :' ;?>
				</label>
				<?php
				if(!empty($radio_array))				foreach($radio_array as $radio_key=>$radio_value){

					$id = str_replace('_','-','share-juice-'.esc_attr($name).'-'.esc_attr($radio_key).'"');

					?><input type ="radio" name="<?php echo esc_attr($field_name_with_encl_arr_name); ?>" <?php
					echo "value=\"".esc_attr($radio_key)."\"".' ';
					if(!empty($class)) echo "class= \"".esc_attr($class)."\"".' '; ;
					//special case for radio button
					if(!empty($id)) echo 'id="'.esc_attr($id).'"'.' ';
					if(!empty($size)) echo "size=\"".esc_attr($size)."\"".' ';
					if($value == $radio_key) echo 'checked'.' ';
					?>/>
					<label class="share-juice-admin-side-label" for="<?php echo esc_attr($id) ?>">
						<?php echo esc_attr($radio_value) ;?>
					</label>
					<?php

				}
				if(!empty($helper_text)) echo "<span class=\"helper\">".wp_kses($helper_text,wp_kses_allowed_html('data'))."</span>".' '; ;?>
			</div>

			<?php
		}

		if($echo == true)		echo ob_get_clean();
		else		return ob_get_clean();
	}

	function  check_field_array($field_array)
	{

		if(!is_array($field_array) || empty($field_array)){

			return array('Field array is either not array or is empty');
		}
		if(empty($field_array['name'])){
			return array('field name is empty');
		}
		return ;
	}
	function heading($heading_type,$heading_text,$heading_class = '')
	{
		echo "<".esc_attr($heading_type).">".esc_attr($heading_text)."</".esc_attr($heading_type).">";
	}
}

?>