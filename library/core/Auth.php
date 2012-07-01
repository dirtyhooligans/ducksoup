<?php
/*
 * 
 * 
 */
class Auth
{	
	public $core;
	
	public function __construct($CORE)
	{
		$this->core = $CORE;
		
		if (! $_SESSION['session'] ) 
		{
			session_start();
		}
	}
	
	public function verify($LOGIN_URL = false, $RETURN_URL = false)
	{		
		$login_url  = $LOGIN_URL;
		$return_url = $RETURN_URL;
		
		if (! empty( $_SESSION['u']['ts'] ) )
		{
			
			$user_auth_timestamp = (int) $_SESSION['u']['ts'];
			
			$user_auth_expire    = $user_auth_timestamp + $this->core->config->auth->session_timeout;
			
			if ( mktime() > $user_auth_expire )
			{
				if ( $login_url )
				{
					if ( $return_url )
						$_SESSION['return_url'] = $return_url;
						
					header("Location: " . $login_url);
					exit();
				}
				else
					return false;			
			}
			else
			{
				$_SESSION['u']['ts'] = mktime() + $this->core->config->auth->session_timeout;
				
				return true;
			}
		}
		else
		{
			if ( $return_url )
					$_SESSION['return_url'] = $return_url;
					
			if ( $login_url )
			{					
				header("Location: " . $login_url);
				exit();
			}
			else
				return false;
		}
	}
	
	public function encrypt($STRING, $KEY = false)
	{
		$string = $STRING;
		$key    = $KEY;
		
		if (! $KEY ) $key = 'shelly';
		
		return Auth_Crypt::encrypt($string, $key);
	}
	
	public function decrypt($STRING, $KEY=false)
	{
		$string = $STRING;
		$key    = $KEY;
		
		if (! $KEY ) $key = 'shelly';
		
		return Auth_Crypt::decrypt($string, $key);
	}
	
	public function destroy()
	{
		session_destroy();
		
		return;
	}
}