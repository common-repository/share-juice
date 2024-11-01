<?php
class CSSDynamic
{
	
	
	private $css_string = '';
	private $options = array();
	public function __construct()
	{
		 $share_juice_options = get_option('share-juice-options');
		 
		 $this->options = isset($share_juice_options['global_floating_box_options'])?$share_juice_options['global_floating_box_options']:'';
		 
		
	}
	
	public function save()
	{
		$this->build();
		$this->save_css_string_to_file();
		
	}
	
	function build()
	{
		
		//build heading
		$this->build_css_head();
		//build css for floating bar
		$this->build_floating_bar_css();
		
		
	}
	
	public function build_css_head()
	{
		ob_start();
		?>
		/* -------------------------------------------------------------
		Do not change this CSS FIle.This file is dynamically generated 
		-------------------------------------------------------------*/
		
		<?php
		$this->css_string .= ob_get_clean();
	}
	public function build_floating_bar_css()
	{
		
				
		ob_start();
		?>
		/* --------------------- 
		Floating Bar CSS
		------------------------*/
		
		
		div.share-juice-floating-bar{
			
			margin-left: -<?php echo isset($this->options['floating_box_left_margin'])?intval($this->options['floating_box_left_margin']):0; ?>px;
			background: #<?php echo isset($this->options['floating_box_shadow']) ? esc_html($this->options['floating_box_bgcolor']):"#ffffff"; ?>;
			<?php if(isset($this->options['floating_box_shadow']) && $this->options['floating_box_shadow'] == true)
			{
				?>	
				-moz-box-shadow: 3px 3px 4px #<?php echo  isset($this->options['floating_box_shadowcolor']) ? esc_html($this->options['floating_box_shadowcolor']):"#00000" ;?> ;
				-webkit-box-shadow: 3px 3px 4px <?php echo  isset($this->options['floating_box_shadowcolor']) ? esc_html($this->options['floating_box_shadowcolor']):"#00000" ;?>;
				box-shadow: 3px 3px 4px <?php echo  isset($this->options['floating_box_shadowcolor']) ? esc_html($this->options['floating_box_shadowcolor']):"#00000" ;?>;
				/* For IE 8 */
				-ms-filter: "progid:DXImageTransform.Microsoft.Shadow(Strength=4, Direction=135, Color='<?php echo  isset($this->options['floating_box_shadowcolor']) ? esc_html($this->options['floating_box_shadowcolor']):"#00000" ;?>')";
				/* For IE 5.5 - 7 */
				filter: progid:DXImageTransform.Microsoft.Shadow(Strength=4, Direction=135, Color='<?php echo  isset($this->options['floating_box_shadowcolor']) ? esc_html($this->options['floating_box_shadowcolor']):"#00000" ;?>');
				<?php } ?>
			
		}
		
		
		
		<?php
		
		$this->css_string .= ob_get_clean();
		
		
	}
	
	function save_css_string_to_file()
	{
		
		$path = SJ_SCRIPTS_DIR_BASE . "/style-add.css";
		//If the file does not exist, it will be created
		//because of w+ option
		$handle = fopen($path, 'w+');
		fwrite($handle, $this->css_string);
		fclose($handle);
		
	}
}
?>