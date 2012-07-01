<?php
class Model_Users extends Model
{
	public $db;
	
	public $info;
	
	public function __construct($CORE)
	{
		parent::__construct($CORE);
		
		$this->db = new Db_Mysqli($this->core);
	}
	
	public function getAll()
	{
		if (! $this->db )
			$this->db = new Db_Mysqli($this->core);
			
			$query = sprintf(
                    "
                    SELECT
                    	*
                    FROM
                        %s.users u
                    "
                    ,
                    $this->db->config->name
                  );
        
        $results = $this->db->Select(array("query" => $query));
                  
        if ($results['valid'])
        {
        	return $results['records'];
        }
        else 
        {
        	return $results['error'];
        }
	}
	
	public function createUser($DATA, $MD5PASS = true)
    { 
        $uinfo = $DATA;
        
        if (! $this->db )
			$this->db = new Db_Mysqli($this->core, 'ducksoup');
			
        // check the provided user name
        if ( empty($uinfo['uname']) )
        {
            return array('error' => true, 'description' => $this->core->lang->errors->E008);
        }
        else
        {
            $check_uname = self::unameExists($uinfo['uname']);
            
            if ( $check_uname['error'] ) 
            {
                return $check_uname;
            }
            
            if ( $check_uname ) 
            {
                return array('error' => true, 'description' => $this->core->lang->errors->E007);
            }
        }
        
        // validate and verify provided email
        if ( empty($uinfo['email']) )
        {
            return array('error' => true, 'description' => $this->core->lang->errors->E009);
        }
        else
        {
            $check_email = self::emailExists($DATA['email']);
            
            if ( $check_email['error'] ) 
                return $check_email;
            
            if ( $check_email )
            {
              //return array('error' => true, 'description' => Environment::Error('E003'), 'E003');
              return array('error' => true, 'description' => $this->core->lang->errors->E003);
              
            }
        }
        
        if ( empty($uinfo['fname']) )
        {
            $uinfo['fname'] = $uinfo['uname'];    
        }
        
        /*
        if (! empty($uinfo['zip']) )
        {
            $zip_info = Locale::zipInfo($uinfo['zip']);
            
            if ( $zip_info['valid'] )
            {
                $uinfo['zip']   = $zip_info['zip'];
                $uinfo['city']  = $zip_info['zip'];
                $uinfo['state'] = $zip_info['zip'];
            }
        }
        */
        
		/*
        else
        {
            return array('error' => true, 'description' => $this->core->lang->errors->E010, 'E010');
        }
        */
		
        if (! empty($uinfo['pass']) && !$MD5PASS )
        {
        	$noemail = true;
        	$md5pass = $uinfo['pass'];
        }
        else
        	$uinfo['pass'] = self::randomPassword();
        
        $query = sprintf(
                    "
                    INSERT INTO
                        %s.users
                    SET
                        uname = '%s',
                        fname = '%s',
                        lname = '%s',
                        email = '%s',
                        password = '%s',
                        city = '%s',
                        state = '%s',
                        zip = '%s'
                    "
                    ,
                    $this->db->config->name,
                    $uinfo['uname'],
                    $uinfo['fname'],
                    $uinfo['lname'],
                    $uinfo['email'],
                    $md5pass,
                    $uinfo['city'],
                    $uinfo['state'],
                    $uinfo['zip']
                  );
        
        $results = $this->db->Insert(array("query" => $query));
                  
        if ($results['valid'])
        {

        	if ( $noemail )
            {
            	return array('id' => $results['insert_id']);
            }
            else 
            {
	            $Msg .= $this->core->lang->emails->new_user->welcome;
	            
	            if(!empty($uinfo['uname']))
	                $Msg .= ' '.$uinfo['uname'].', <BR>';
	            
	            $Msg .= '<BR>';
	            $Msg .= $this->core->lang->emails->new_user->welcome_msg;
	            $Msg .= '<BR>';
	            $Msg .= '<BR>';
	            $Msg .= $this->core->lang->emails->new_user->email;
	            $Msg .= $uinfo['email'];
	            $Msg .= '<BR>';
	            $Msg .= $this->core->lang->emails->new_user->uname;
	            $Msg .= $uinfo['uname'];
	            $Msg .= '<BR>';
	            $Msg .= $this->core->lang->emails->new_user->password;
	            $Msg .= $uinfo['pass'];
	            $Msg .= '<BR>';
	            $Msg .= '<BR>';
	            $Msg .= '<a href="'.$this->core->lang->emails->new_user->login_page_link.'" >';            
	            $Msg .= $this->core->lang->emails->forgot_password->login_page;
	            $Msg .= '</a>';
	            
            
            	$WelcomeEmail = Emails::send(
                                        $uinfo['email'], 
                                        $this->core->config->site_name, 
                                        $this->core->config->email->noreply, 
                                        $this->core->lang->emails->new_user->subject, 
                                        $Msg, 
                                        'welcome'
                                       );
            
	            if ( $WelcomeEmail['error'] )
	            {
	                return $WelcomeEmail;
	            }
	            else
	            {
					//Model_Friends::requestFriend($results['insert_id'], 30001, 1);
					//Model_Friends::requestFriend(30001, $results['insert_id'], 1);
					
	                return array('id' => $results['insert_id']);
	            }
            }
            
            
        }
        else
        {
            return $results['error'];
        }
                
    }
    
	public function activateUser($DATA)
    { // 0.1.0 
    	
    	if (! $this->db )
			$this->db = new Db_Mysqli($this->core, 'ducksoup');
    	
    	$UID     = $DATA['uid'];
    	$CURRENT = $DATA['opass'];
    	$NEW     = $DATA['npass'];
    	$CONFIRM = $DATA['cpass'];
        
        if ( $CURRENT != self::userPass($UID) )
            return array('error' => true, 'description' => $this->core->lang->errors->E005);
        
        
        if( $CONFIRM )
        {
            if ( md5($NEW) != $CONFIRM )
                return array('error' => true, 'description' => $this->core->lang->errors->E006);
        }
        
        $query = sprintf(
                "
                UPDATE
                    %s.users
                SET
                    password = '%s',
                    active = '1'
                WHERE
                    id = '%d'                        
                "
                ,
                $this->db->config->name,
                md5($NEW),
                $UID
             );
             
        //$results = DB::Update(array("query" => $query));
        $results = $this->db->Update(array("query" => $query));
        
        if ( $results['valid'] )
        {
            $user = Model_User::getUser($UID);
            
            return Model_User::getUser($UID);
        }
        else
        {
            return $results['error'];
        }
    
    }

	public function updatePasswordByUsername($USERNAME, $MD5_PASS)
    { // 0.1.0 
    	
    	if (! $this->db )
			$this->db = new Db_Mysqli($this->core);
    	
    	$username = $USERNAME;
    	$pass     = $MD5_PASS;
    	
        $query = sprintf(
                "
                UPDATE
                    %s.users
                SET
                    password = '%s'
                WHERE
                    uname = '%s'                       
                "
                ,
                $this->db->config->name,
                $pass,
                $username
             );
          
        $results = $this->db->Update(array("query" => $query));
        
        if ( $results['valid'] )
        {
            return true;
        }
        else
        {
            return $results['error'];
        }
    
    }
    
    public function forgotPassword($VALUE)
    { // 0.1.0
     
    	if (! $this->db )
			$this->db = new Db_Mysqli($this->core, 'ducksoup');
			
        $query = sprintf(
                    "
                    SELECT 
                        u.id,
                        u.uname,
                        u.email
                    FROM 
                        %s.users u
                    WHERE
                        u.id = '%d'
                          OR
                        u.email = '%s'
                          OR
                        u.uname = '%s'
                    "
                    ,
                    $this->db->config->name,
                    $VALUE,
                    $VALUE,
                    $VALUE
                  );
        
        $results = $this->db->Select(array("query" => $query));
        
        if ( $results['valid'] )
        {
            if( $results['records'][0] )
            {
                $uinfo = $results['records'][0];
                $uinfo['password'] = self::randomPassword();
                
                
                $query = sprintf(
                        "
                        UPDATE
                            %s.users
                        SET
                            password = '%s',
                            active = 0
                        WHERE
                            id = '%d'                        
                        "
                        ,
                        $this->db->config->name,
                        md5($uinfo['password']),
                        $uinfo['id']
                     );
                     
                //$results = DB::Update(array("query" => $query));
                $results = $this->db->Update(array("query" => $query));
        
                if ( $results['valid'] )
                {                    
                    $Msg = $this->core->lang->emails->forgot_password->msg;
                    $Msg .= '<BR>';
                    $Msg .= '<BR>';
                    $Msg .= $this->core->lang->emails->forgot_password->email;
                    $Msg .= $uinfo['email'];
                    $Msg .= '<BR>';
                    $Msg .= $this->core->lang->emails->forgot_password->uname;
                    $Msg .= $uinfo['uname'];
                    $Msg .= '<BR>';
                    $Msg .= $this->core->lang->emails->forgot_password->password;
                    $Msg .= $uinfo['password'];
                    $Msg .= '<BR>';
                    $Msg .= '<BR>';
                    $Msg .= '<a href="'.BASE_URL.'" >';            
                    $Msg .= $this->core->lang->emails->forgot_password->login_page;
                    $Msg .= '</a>';

                    $ForgotEmail = Emails::send(
                                               $uinfo['email'], 
                                               $this->core->config->site_name, 
                                               $this->core->config->email->noreply, 
                                               $this->core->lang->emails->forgot_password->subject, 
                                               $Msg, 
                                               'account_update'
                                              );
                    
                    if( $ForgotEmail['error'] )
                    {
                        return $ForgotEmail;
                    }
                    else
                    {
                        return array('id' => $uinfo['id']);
                    }
                    
                }
                else
                {
                    return $results['error'];
                }
                
            }
            else
            {
                return array('error' => true, 'description' => $this->core->lang->errors->E011, 'E011');
            }
            
        }
            
    }
    
    public function userPass($USERID)
    { // 0.1.0 
    
    	if (! $this->db )
			$this->db = new Db_Mysqli($this->core, 'ducksoup');
			
        $InfoData = array();
        
        $query = sprintf("
                    SELECT 
                        u.password
                    FROM 
                        %s.users u
                    WHERE 
                        u.id = '%d'
                    "    
                    , 
                    $this->db->config->name,    
                    $USERID
                    );
        
        //$results = DB::Select(array("query" => $query));
        $results = $this->db->Select(array("query" => $query));
        
        if ( $results['valid'] )
        {
            return $results['records'][0]['password'];
        }
        else
        {
            return $results['error'];
        }
    }
    
	public function randomPassword()
    { // 0.1.0
        $chars = "ab2c3d4e5f6ghjkmnpqrstuvw7x8y9z";

        srand((double)microtime()*1000000);
        
        $i = 0;
        
        while ($i <= 7) 
        {
            $num = rand() % 33;
            $tmp = substr($chars, $num, 1);
            $pass .= $tmp;
            $i++;
        }

        return $pass;

    }
    
    public function unameExists($UNAME)
    { // 0.1.0
    
    	if (! $this->db )
			$this->db = new Db_Mysqli($this->core, 'ducksoup');
			
        $query = sprintf(
                    "
                    SELECT 
                        u.id
                    FROM 
                        %s.users u
                    WHERE
                        u.uname = '%s'
                    "
                    ,
                    $this->db->config->name,
                    $UNAME
                  );
        
        //$results = DB::Select(array("query" => $query));
        $results = $this->db->Select(array("query" => $query));
        
        if ( $results['valid'] )
        {
            if (! empty($results['records'][0]['id']))
            {
                return true;
            }
            else
            {
				return false;
              }
        }
        else
        {
            return $results['error'];
        }

    }
    
    public function emailExists($EMAIL)
    { 
    	if (! $this->db )
			$this->db = new Db_Mysqli($this->core, 'ducksoup');

        $query = sprintf(
                    "
                    SELECT 
                        u.id
                    FROM 
                        %s.users u
                    WHERE
                        u.email = '%s'
                    "
                    ,
                    $this->db->config->name,
                    $EMAIL
                  );
        //$results = DB::Select(array("query" => $query));
        $results = $this->db->Select(array("query" => $query));
        
        if ( $results['valid'] )
        {
            if (! empty($results['records'][0]['id']) )
            {
                return true;
            }
            else
            {
                return false;
            }
        }
        else
        {
            return $results['error'];
        }

    }
    
    public function numUsers()
    {
        $allUsers = self::getUsers();
        return $allUsers['total'];
    }
	
}
?>