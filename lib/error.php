<?php
class ShareJuiceError
{
 
 const ERROR = 'E';
 const WARNING = 'W';
 const INFORMATION = 'I';
    
 private $error_messages ;
 
  public function __construct()
  {
    
    $this->error_messages = array();
  }

 public function add($error_type,$error_message)
 {
    
    $this->error_messages[$error_type][] = $error_message;
     
 }

public function erase_messages()
{
    
    unset($this->error_messages);
}

public function show_messages()
{
    if(isset($this->error_messages['E']))
	foreach($this->error_messages['E'] as $message)
	{
		echo '<div class="error"><p>'.$message.'</p></div>';
	}
     if(isset($this->error_messages['I']))
	foreach($this->error_messages['I'] as $message)
	{
		echo '<div class="updated"><p>'.$message.'</p></div>';
	}
}
public function isSevereError()
{
  
  $z = $this->error_messages;
    
    
    if(isset($z['E']))
     return true;
    else 
    return false;
}

}

?>