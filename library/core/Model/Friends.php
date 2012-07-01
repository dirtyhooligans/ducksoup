<?php
class Model_Friends extends Model
{
	public $db;
	
	public $info;
	
	public function __construct($CORE)
	{
		parent::__construct($CORE);
		
		$this->db = new Db_Mysqli($this->core);
	}
	
	public function getFriends($ID, $DETAILS = false)
	{
		
		if (! $this->db )
			$this->db = new Db_Mysqli($this->core);
		
		$id = (int) $ID;
        
        $query = sprintf("
                    SELECT 
                    	 u.`id`,
                    	 u.`uname`,
                    	 u.`dname`,
                        uf.`friend_id`,
                        uf.`active`,
                        uf.`requested`,
                        uf.`confirmed`
                    FROM 
                        %s.`user_friends` uf
                    LEFT JOIN
                    	%s.`users` u
                    ON
                    	u.`id` = uf.`friend_id`
                    WHERE 
                        uf.`user_id` = %d                      
                    "    
                    , 
                    $this->db->config->name,
                    $this->db->config->name,
                    $id
                    );
		
        $results = $this->db->Select(array("query" => $query));
       
        if ($results['valid'])
        {
        	if ( $DETAILS )
        		return $results;
        		
            $total = $results['total'];
            
            for ( $i = 0; $i < $total; $i++ )
            {
            	$return[$results['records'][$i]['friend_id']] = $results['records'][$i];
            	if ( empty($results['records'][$i]['dname']) )
            		$return[$results['records'][$i]['friend_id']]['dname'] = $results['records'][$i]['uname'];
            	
            	//$return[$results['records'][$i]['friend_id']]['info'] = Model_User::getUser($results['records'][$i]['friend_id']); 
            }
            
            return $return;
        }
		else
        {
            return $results['error'];
        }
	}
	
	public function requestFriend($USER_ID, $FRIEND_ID, $AUTO_CONFIRM = 0, $DETAILS = false)
	{
		
		if (! $this->db )
			$this->db = new Db_Mysqli($this->core);
		
		$user_id      = (int) $USER_ID;
        $friend_id    = (int) $FRIEND_ID;
		$auto_confirm = (int) $AUTO_CONFIRM;
		
		if ( $auto_confirm == 1 )
		{
			$confirmed = ", `confirmed` = NOW() ";
		}
        
        $query = sprintf("
                    INSERT INTO
                    	%s.`user_friends`
                    SET
                    	`user_id`   = %d,
                        `friend_id` = %d,
                        `active`    = %d
                        %s                   
                    "    
                    , 
                    $this->db->config->name,
                    $user_id,
                    $friend_id,
                    $auto_confirm,
                    $confirmed
                    );
		
        $results = $this->db->Insert(array("query" => $query));
       
        if ($results['valid'])
        {
        	if ( $DETAILS )
        		return $results;
        		
            $return = true;
            
            return $return;
        }
		else
        {
            return $results['error'];
        }
	}
	
	public function confirmFriend($USER_ID, $FRIEND_ID)
    {
    	if (! $this->db )
			$this->db = new Db_Mysqli($this->core);
			
		$user_id = $USER_ID;
		
		$friend_id = $FRIEND_ID;
			       
        $query = sprintf(
		                "
		                UPDATE
		                    %s.`user_friends`
		                SET
		                    `confirmed` = NOW(),
		                    `active` = 1
		                WHERE
		                    `user_id` = %d
		                      AND
		                    `friend_id` = %d                       
		                "
		                ,
		                $this->db->config->name,
		                $user_id,
		                $friend_id
		               );
             
        $results = $this->db->Update(array("query" => $query));
        
        if( $results )
        {     
            return true;
        }
        else
        {
            throw new Core_Exception(mysqli_error($mysqli), mysqli_errno($mysqli));
        }                    
    }
}
?>