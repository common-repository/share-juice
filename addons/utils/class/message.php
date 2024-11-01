<?php
class SJMessage
{

	private $message_array;
	public
	function __construct()
	{


	}
	public static
	function get_message_from_array($msg_key, $group,$params_array ='' )
	{

		$message_array = self::get_message_array_from_group($group);
		$msg = $message_array[$msg_key];
		if($msg != NULL)		return vprintf($msg,$params_array);

	}

	public static
	function get_message_from_string($msg_key,$group, $message_string='' )
	{
		
		$message_array = self::get_message_array_from_group($group);
		$msg = $message_array[$msg_key];
		if($msg != NULL)		return sprintf($msg,$message_string);

	}
	
	public static function get_message_array_from_group($group)
	{
		global $share_juice_general_msg_array;
		if($group == 'general' )		 
		return $share_juice_general_msg_array;
		
		
		
	}
}

?>