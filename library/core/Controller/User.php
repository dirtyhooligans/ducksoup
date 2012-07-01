<?php
class Controller_User extends Controller 
{
	public function __construct($CORE) 
	{
		parent::__construct($CORE);
	}
	
	public function login()
	{
		
		if( Auth::verify() )
		{
			header("Location: " . BASE_URL . "");
			exit();
		}
			
		$this->loadView('User_Login');
	}
	
	public function validate()
	{
		if (! empty($_SESSION['return_url']) )
		{
			$authed_url = $_SESSION['return_url'];
			unset($_SESSION['return_url']);
		}
		else
			$authed_url = BASE_URL;
			
		header("Location: " . $authed_url);
		exit();
	}
	
	public function logout()
	{
		Auth::destroy();
		
		header("Location: " . BASE_URL . "");
		exit();
	}
	
}
?>