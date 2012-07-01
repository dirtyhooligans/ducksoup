<?php

/**
 * Session
 * 
 * @author S. Partridge
 *
 */
class Session 
{
	protected static $Instance;

	public function __construct()
	{
		session_start();
	}
	
	public function init()
	{
		$_SESSION['session'] = true;
		return;
	}
}

?>