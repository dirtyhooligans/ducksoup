<?php
class Controller_Api_User extends Controller_Api
{
	public function __construct($CORE)
	{
		$this->core = $CORE;
	}
	
	public function login()
	{
		$user_login = Model_User::loginldap();		
		echo json_encode($user_login);
	}
}
?>