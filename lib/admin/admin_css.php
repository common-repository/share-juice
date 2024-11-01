<?php

class SJAdminCSS
{


	public
	function validate_and_generate_forms()
	{

		global $share_juice_error;

		if(!empty($_POST)){

			switch($_POST['submit']){
				case "SaveFile":
				$this->save_file();

			}


		}
		$this->show_file_contents();
	}

	function save_file()
	{

		$content = isset($_POST['css_text'])?$_POST['css_text']:'';
		if(!is_dir(SJ_UPLOAD_DIR_BASE))            mkdir(SJ_UPLOAD_DIR_BASE);
		if(!is_dir(SJ_CUSTOM_DIR_BASE))            mkdir(SJ_CUSTOM_DIR_BASE);

		$custom_file = SJ_CUSTOM_DIR_BASE . '/custom.css';

		//echo $custom_file;
		$file = fopen($custom_file, 'w+');
		$data = fwrite($file, $content);
		fclose($file);
		share_juice_empty_w3t_cache();

	}
	function show_file_contents()
	{

		$custom_file = SJ_CUSTOM_DIR_BASE . '/custom.css';


		//echo $custom_file;
		$data = '';

		if(file_exists($custom_file)){
			$file = fopen($custom_file, 'r');
			if(filesize($custom_file) > 0)                $data = fread($file, filesize($custom_file));
			fclose($file);
		}

		SJFormHelper::div_start("admin");
		share_juice_admin_header('Custom CSS Editor');
		SJFormHelper::div_start("accordion");

		SJFormHelper::heading('h3',"Custom CSS");
		SJFormHelper::div_start('admin-custom-css');

		SJFormHelper::form_start_html('css-edit');
		SJFormHelper::field_input_html(	array(
				'name'                => 'css_text',
				'name_array'          => '',
				'type'                =>"textarea",
				'id'                  =>'edit-css-textarea',
				'label'               => _('Enter your CSS Here'),
				'value'=> $data));
		?>
		<p class="large-label">
			<label for="file_location">
				This file is located at:
			</label>
			<input type="text" name="file_location" size=80 readonly="" value="<?php echo esc_attr($custom_file) ?>"/>
		</p>
		<p>
			Press Save File once if you don't see a file name above
		</p>
		<p>
			<button type="submit"  name="submit" value="SaveFile">
				Save File
			</button>
		</p>

		<?php

		SJFormHelper::form_close_html();
		SJFormHelper::div_close();

		SJFormHelper::div_close("accordion");
		SJFormHelper::div_close("admin");

	}

}



?>