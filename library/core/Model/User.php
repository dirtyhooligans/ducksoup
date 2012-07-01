<?php
class Model_User extends Model
{
	public $db;
	
	public $info;
	
	public function __construct($CORE)
	{
		parent::__construct($CORE);
		
		$this->db = new Db_Mysqli($this->core, 'ducksoup');
	}
	
	public function getUser($ID)
    {
    	if (! $this->db )
			$this->db = new Db_Mysqli($this->core, 'ducksoup');
		
		$id = (int) $ID;
		$user = (string) $ID;
        
        $query = sprintf("
                    SELECT 
                        u.`id`,
                        u.`dname`,
                        u.`uname`,
                        u.`fname`,
                        u.`lname`,
                        u.`email`,
                        u.`logins`,
                        u.`address`,
                        u.`city`,
                        u.`state`,
                        u.`zip`,
                        u.`views`,
                        u.`active`,
                        u.`admin`,
                        u.`current_login`, 
                        DATE_FORMAT(u.`last_active`, '%%c/%%e/%%y %%l:%%i %%p') AS `last_active`,
                        DATE_FORMAT(u.`last_login`, '%%c/%%e/%%y %%l:%%i %%p') AS `last_login`,
                        DATE_FORMAT(u.`current_login`, '%%c/%%e/%%y %%l:%%i %%p') AS `current_login`,
                        DATE_FORMAT(u.`created`, '%%c/%%e/%%y') AS `user_since`,
						UNIX_TIMESTAMP(u.`last_login`) AS `last_login_unix`
                    FROM 
                        %s.`users` u
                    WHERE 
                        u.`id` = %d
                         OR
                        u.`uname` = '%s'
                    LIMIT 1                      
                    "    
                    , 
                    $this->db->config->name,
                    $id,
                    $user
                    );
		
        $results = $this->db->Select(array("query" => $query));
       
        if ($results['valid'])
        {
            $info = $results['records'][0];
			
            if (empty($info['dname']))
            {
                if (empty($info['fname']))
                {
                    $info['dname'] = $info['uname'];
                }
                else
                {
                    $info['dname'] = $info['fname'];
                }
            }
			
			if (date('Ymd', $info['last_login_unix']) == date('Ymd', mktime()) )
			{
				$info['last_login_neat'] = "Today @".date('g:ia', $info['last_login_unix']);
			}
			else if (date('Ymd', $info['last_login_unix']) == date('Ymd', mktime(0,0,0,date('m'),date('d')-1,date('Y'))) )
			{
				$info['last_login_neat'] = "Yesterday @".date('g:ia', $info['last_login_unix']);
			}
			else if ( ((mktime()-$info['last_login_unix'])/86400) < 7 )
			{
				$info['last_login_neat'] = date("l @ g:ia", $info['last_login_unix']);
			}
			else
			{
				$info['last_login_neat'] = date("M j @ g:ia", $info['last_login_unix']);
			}
			
			$info['system_date_unix'] = time();
			
			return $info;
        }
        else
        {
            return $results['error'];
        }
        
    }
    
    public function loginldap($USER = false, $PASS = false)
    {
    	$user = $USER;
		$pass = $PASS;
	
    	if ( empty($user) ) $user = $this->core->http_vars['uname'];
		if ( empty($pass) ) $pass = $this->core->http_vars['pass'];
		
		if ( empty($user) )
			return array('error' => true, 'description' => 'User not provided');
			
		if ( empty($pass) )
			return array('error' => true, 'description' => 'Password not provided');
				
		if ( strpos($user, '@') !== false )
		{
			$split_email = explode('@', $user);
			$user = $split_email[0];
		}
		/*
		if ( $this->core->config->config == 'local' )
		{
			return $pass;
			return self::login($user, md5(Auth::decrypt($pass)));
		}
		*/	
		
		$ldap = Ldap::authUser($user, 
							   $pass,
							   $this->core->config->auth->ldap->server,
							   $this->core->config->auth->ldap->basedn,
							   "(&(objectClass=person)(uid=$user))",
							   "uid=$user, "
							  );

		if (! $ldap )
		{
			$pass = Auth::decrypt($pass);
			//return array('error' => true, 'description' => 'p: ' . $pass);
			$login = self::login($user, md5($pass));
			
	    	if (! empty($login['id']) )
	    		return $login;
	    	
			$ldap = Ldap::authUser($user, 
							   $pass,
							   $this->core->config->auth->ldap->server,
							   $this->core->config->auth->ldap->basedn,
							   "(&(objectClass=person)(uid=$user))",
							   "uid=$user, "
							  );
			
		}	

		if ( $ldap )
    	{
    		$isUser =  Model_Users::unameExists($user);
    		
    		if (! $isUser)
    		{
    			$user_info['uname'] = $ldap['uid'][0];
	    		$user_info['email'] = $ldap['uid'][0].'@saymedia.com';
	    		$user_info['fname'] = $ldap['givenname'][0];
	    		$user_info['lname'] = $ldap['sn'][0];
	    		$user_info['dname'] = $ldap['cn'][0];
	    		$user_info['pass']  = md5($pass);
	    			    		
    			$create_user = Model_Users::createUser($user_info, false);
    		}
			else
			{
				
				$update_password = Model_Users::updatePasswordByUsername($user, md5($pass));
				//return $update_password;
			}
 			
    		return self::login($user, md5($pass));
    	}
    	else
    	{
	    	return array('error' => true, 'description' => 'LDAP Login Failed ' .$pass);
    	}

    }
	
	public function login($USER = false, $PASS = false)
	{
		
		if (! $this->db )
			$this->db = new Db_Mysqli($this->core, 'ducksoup');

		$user = $USER;
		$pass = $PASS;
		
		if (! $USER )	
		{
			$user = $this->core->http_vars['uname'];
		}
		
		if (! $PASS )	
		{
			$pass = $this->core->http_vars['pass'];
		}
		
		$query = sprintf(
                    "
                    SELECT 
                        u.`id` AS `id`,
                        u.`password`,
                        u.`uname`,
                        u.`current_login`
                    FROM 
                        %s.`users` u
                    WHERE
                        u.`id` = '%d'
                          OR
                        u.`email` = '%s'
                          OR
                        u.`uname` = '%s'
					LIMIT 1
                    "
                    ,
                    $this->db->config->name,
                    (int) $user, 
                    $user, 
                    $user
                  );
      
        $results = $this->db->Select(array("query" => $query));
		
        $result = $results['records'][0];
      
		if (! empty( $result['id'] ) )
		{
			if ($result['password'] == $pass)
			{
				//$SyncAnonToUser = $this->syncAnonToUser($results[0]['id']);
				$authUser = self::authorizeUser($result);
				
				if (! empty( $authUser['error'] ) )
				{
					return $authUser;
				}
				else
				{
					$this->info = self::getUser($results['records'][0]['id']);
					
					$quotes[] = "Welcome to the Thunderdome {NAME}";
					$quotes[] = "Use the force {NAME}.";
					$quotes[] = "Do you want the red pill or the blue pill {NAME}?";
					$quotes[] = "Say hello to my little friend, {NAME}";
					$quotes[] = "Hakuna matata, {NAME}";
					$quotes[] = "You have to ask yourself one question. 'Do I feel lucky?' Well, do ya {NAME}";
					$quotes[] = "I am your father {NAME}";
					$quotes[] = "Kick the tires and light the fires {NAME}";
					$quotes[] = "Welcome back {NAME} to the show that never ends";
					$quotes[] = "When this baby hits 88 mph you're going to see some serious shit {NAME}";
					$quotes[] = "{NAME} we better back up. We don't have enough road to get up to 88";
					$quotes[] = "We're going to need a bigger boat {NAME}";
					$quotes[] = "Pay no attention to that man behind the curtain {NAME}";
					//$quotes[] = "{NAME}";
					
					$randQuoteNum = rand(0, (count($quotes)-1));
					
					$random_quote = $quotes[$randQuoteNum];
					
					$this->info['welcome_message'] = str_replace('{NAME}', $this->info['fname'], $random_quote);

					$_SESSION['u']['id'] = $this->info['id'];
					$_SESSION['u']['ts'] = mktime() + $this->core->config->auth->session_timeout;
					
					return $this->info;
				}
			}
			else
			{            
				return array('error' => true, 'code' => 'E002', 'description' => 'Password Invalid');
			}
		}
		else
		{
			//return $query;
			return array('error' => true, 'description' => 'User ('.$user.') not found');
		}
	}
	
	public function logout()
	{
		Auth::destroy();
		return true;
	}
	
	public function authorizeUser($USERINFO)
    {
    	if (! $this->db )
			$this->db = new Db_Mysqli($this->core, 'ducksoup');
			       
        $query = sprintf(
		                "
		                UPDATE
		                    %s.`users`
		                SET
		                    `current_login` = NOW(),
		                    `logins` = `logins` + 1,
		                    `last_login` = '%s'
		                WHERE
		                    `id` = '%d'                        
		                "
		                ,
		                $this->db->config->name,
		                $USERINFO['current_login'],
		                $USERINFO['id']
		               );
             
        $results = $this->db->Update(array("query" => $query));
        
        if( $results ){    
            
            $_SESSION['u']['id'] = $USERINFO['id'];
            
            if( LOCALHOST )
            {
                setcookie("core_uid", $USERINFO['id'], time() + LOGIN_SESSION_TIMEOUT, '/', false, 0);
            }
            else
            {    
                setcookie("core_uid", $USERINFO['id'], time() + LOGIN_SESSION_TIMEOUT, '/', DOMAIN );
            }
         
            return true;
        }
        else
        {
            throw new Core_Exception(mysqli_error($mysqli), mysqli_errno($mysqli));
        }                    
    }
}
?>