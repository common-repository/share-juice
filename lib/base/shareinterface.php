<?php 
interface ShareInterface{
    
     public static function get_customization_options_html($value);
     public static function validate_button_configuration();
   public function get_button_HTML();  
   public function get_js_code();
   public function echo_js_code();
   public function is_js_in_footer_ok();
}

?>