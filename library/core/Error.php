<?php
class Error
{
	public $core;
	
	public function __construct($CORE)
	{
		$this->core = $CORE;
	}
	
	public function db($TYPE, $MESSAGE, $DESCRIPTION)
	{
		$err = array('error' => true, 'type' => $TYPE, 'code' => $MESSAGE, 'description' => $DESCRIPTION);
		$_SESSION['ducksoup']['errors'][] = $err;
		return $err;
	}
	
	public function user($MESSAGE, $DESCRIPTION)
	{
		$err = array('error' => true, 'type' => 'USER', 'code' => $MESSAGE, 'description' => $DESCRIPTION);
		$_SESSION['ducksoup']['errors'][] = $err;
		return $err;
	}
}