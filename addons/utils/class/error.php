<?php
class SJError
{

	const ERROR = 'E';
	const WARNING = 'W';
	const INFORMATION = 'I';

	private $error_messages ;

	public
	function __construct()
	{

		$this->error_messages = array();
	}

	public
	function add($error_type,$error_message)
	{

		$this->error_messages[$error_type][] = $error_message;

	}

	public
	function erase_messages()
	{

		unset($this->error_messages);
	}

	public
	function show_messages()
	{
		if(isset($this->error_messages['E']))	foreach($this->error_messages['E'] as $message){
			echo '<div class="error"><p>'.wp_kses($message,wp_kses_allowed_html('data')).'</p></div>';
		}
		if(isset($this->error_messages['I']))	foreach($this->error_messages['I'] as $message){
			echo '<div class="updated"><p>'.wp_kses($message,wp_kses_allowed_html('data')).'</p></div>';
		}
	}
	public
	function is_severe_error()
	{

		$z = $this->error_messages;


		if(isset($z['E']))     return true;
		else    return false;
	}

	public function get_messages($error_type)
	{
		return isset($this->error_messages[$error_type])?$this->error_messages[$error_type]:'';
	}
	
	public function get_messages_all()
	{
		return $this->error_messages;
	}
	public function reset()
	{
		$this->error_messages = '';
	}
}

?>