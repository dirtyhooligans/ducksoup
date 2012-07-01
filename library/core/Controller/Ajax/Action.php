<?php
class Controller_Ajax_Action extends Controller_Ajax
{
	public function __construct($CORE)
	{
		parent::__construct($CORE);
	}
	
	public function login()
	{
		$user_login = Model_User::login();
		
		echo json_encode($user_login);
	}
	
	public function register()
	{
		$register_user = Model_Users::createUser($this->core->http_vars);
		
		echo json_encode($register_user);
	}
	
	public function activate()
	{
		
		$activate_user = Model_Users::activateUser($this->core->http_vars);
		
		echo json_encode($activate_user);
	}
	
	public function getfriends()
	{
		if(! Auth::verify() )
			return array('error' => true, 'description' => 'auth fail');
		
		if (! empty( $this->core->http_vars['user_id'] ) )
		{
			$id = $this->core->http_vars['user_id'];
		}
		else
		{
			$id = $this->user['id'];
		}
		
		$return = Model_Friends::getFriends($id);
	
		
		echo json_encode($return);
	}
	
	public function requestfriend()
	{
		if(! Auth::verify() )
			return array('error' => true, 'description' => 'auth fail');
			
		$return = Model_Friends::getFriends($this->core->user['id'], $this->core->http_vars['friend_id']);
		
		
		echo json_encode($return);
	}
}