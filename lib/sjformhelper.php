<?php
class SJFormHelper
{
	public static function createSelectHTML($array_select, $key_selected = '', $name =
	'', $id = '', $class = '')
	{
		
		ob_start();
		?>
		<select <?php echo !empty($name) ? "name=" . $name . "\t" : '';
		echo !empty($id) ? "id=" . $id . "\t" : '';
		echo !empty($class) ? "class=" . $class . "\t" : ''; ?> >
		<?php
		foreach ($array_select as $key => $value) {
			
			if ($key == $key_selected) {
				?>
				<option value=<?php echo $key ?> SELECTED><?php echo $value ?></option>
				<?php
				} else {
				?>
				<option value=<?php echo $key ?>><?php echo $value ?></option>
				
				<?php
			}
		}
		?>
		</select>
		<?php
		$c = ob_get_clean();
		
		return $c;
		
	}
	
	public static function close_fieldset()
	{
		echo '</FIELDSET>';
		
	}
	
	
	public static function form_start_html($id = "", $class = "",$enc_type ='')
	{
		ob_start();
		?>
		
		<form method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>" <?php
		if ($id != null)
			echo "id=\"{$id}\"" . ' ';
		if ($class != null)
			echo "class=\"{$class}\"" . ' ';
		if($enc_type != null)
			echo "enctype=\"multipart/form-data\"";
		?>>
		
		<?
		echo ob_get_clean();
	}
	
	public static function show_form_start_html($action_url='',$method ='post',$id = "", $class = "",$enc_type ='')
	{
		if(empty($action_url))
			$action_url= $_SERVER["REQUEST_URI"];
		ob_start();
		?>
		
		<form method="<?php echo $method ?>" action="<?php echo $action_url; ?>" <?php
		if ($id != null)
			echo "id=\"{$id}\"" . ' ';
		if ($class != null)
			echo "class=\"{$class}\"" . ' ';
		if($enc_type != null)
			echo "enctype=\"multipart/form-data\"";
		?>>
		
		<?
		echo ob_get_clean();
	}
	
	
	public static function div_start($div_id = '', $div_class = '')
	{
		ob_start();
		?>
		<div <?php if(!empty($div_id)) echo "id=\"{$div_id}\"" ;
		if(!empty($div_class)) echo "class=\"{$div_class}\"" ;?>>
		<? 
		echo ob_get_clean();
	}
	public static function div_close($div_close_comment = '')
	{
		echo '</div>';
		if(!empty($div_close_comment))
		 echo call_user_func_array(array("SJFormHelper", "show_html_comment"),array($div_close_comment));
	}
	public static function form_close_html()
	{
		echo "</form>";
	}
	public static function start_fieldset($legend, $name = '', $id = '', $class = '')
	{
		echo "<fieldset name=\"{$name}\" $id=\"{$id}\" $class=\"{$class}\">";
		echo "<LEGEND>{$legend}</LEGEND>";
	}
	
	public static function button_html($class = '', $value = '', $button_text = '')
	{
		echo "<button type=\"submit\" name=\"submit\" class=\"{$class}\" value={$value} >{$button_text}</button>";
	}
	
	public static function string_in_string($haystack,$needle){
		if(strpos($haystack, $needle) !== false)
			return true;
		else return false;
		
		
	}
	
	public static function form_validation($name,$value,$rules)
	{
		
		$errors=array();
		$rules = explode('|',$rules);
		
		if(!empty($rules))
			foreach($rules as $rule)
		{
			
			switch($rule)
			{
				
				case 'trim':
				$value = trim($value);
				break;
				case 'null':
				if(empty($value))
					$errors[] = "{$name} cannot be null ";
				break;
				default:
				break;
			}
			
		}
		
		
	}
	public static function show_html_comment($comment)
	{
		
		echo "<!---- {$comment} ------>";
		
	}
	public static function show_heading($heading_type,$heading_text)
	{
		echo "<{$heading_type}>{$heading_text}</{$heading_type}>";
	}
	
	public function input_text_field_html(
	$field_name,
	$field_label,
	$field_id,
	$field_value='',
	$field_class='',
	$style='',
	$size='',
	$helper_text = '',
	$echo = true
	)
	{
		ob_start();
		?>
		
		<div class="share-juice-label-and-field">
		<label for="<?php echo $field_id ?>"><?php echo $field_label ?></label>
		<input type ="text" 
		name="<?php echo $field_name ?>" 
		id="<?php echo $field_id ?>" 
		value="<?php echo $field_value ?>" 
		<?php if(!empty($field_class)) echo "class= \"$field_class\"" ?>
		<?php if(!empty($size)) echo "size=\"{$size}\"" ?>/>
		<?php if(!empty($helper_text)) echo "<span class=\"helper\">{$helper_text}</span>" ;?>
		</div>
		
		
		<?php	
		
		if($echo == true)
			echo ob_get_clean();
		else
		return ob_get_clean(); 
	}
	public function input_hidden_field_html($field_name,
	$field_id,
	$field_value='',
	$field_class='',
	$echo = true)
	{
		ob_start();
		?>
		
		<input type ="hidden" 
		name="<?php echo $field_name ?>" 
		id="<?php echo $field_id ?>" 
		value="<?php echo $field_value ?>" 
		<?php if(!empty($field_class)) echo "class= \"$field_class\"" ?> />	
		
		<?php	
		
		if($echo == true)
			echo ob_get_clean();
		else
		return ob_get_clean(); 
	}
	public function input_checkbox_field_html(
	$field_name,
	$field_label,
	$field_id,
	$field_checked=false,
	$field_class='',
	$style = '',
	$helper_text= '' ,
	$echo = true)
	{
		ob_start();
		?>
		
		<div class="share-juice-label-and-field">
		<label for="<?php echo $field_id ?>"><?php echo $field_label ?></label>
		<input type ="checkbox" 
		name="<?php echo $field_name ?>" 
		id="<?php echo $field_id ?>" 
		<?php echo isset($field_checked) && $field_checked == true ?"Checked":false?> 
		<?php if(!empty($field_class)) echo "class= \"$field_class\"" ?> />
		<?php if(!empty($helper_text)) echo "<span class=\"helper\">{$helper_text}</span>" ;?>
		</div>
		<?php	
		if($echo == true)
			echo ob_get_clean();
		else
		return ob_get_clean(); 
	}
	
}

?>