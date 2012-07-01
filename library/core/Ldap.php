<?php

class Ldap
{

	public function authUser($user, $pass, $server, $basedn, $filter, $dn)
	{
		if ( empty($pass) )
			return false;
	
		if (!($connect = ldap_connect($server))) 
			return false;
			//die ("Could not connect to LDAP server");
		
		try
		{
			if (!($bind = @ldap_bind($connect, "$dn" . "$basedn", $pass))) 
			{
				//return false;
			}
			
			$sr   = ldap_search($connect, $basedn,"$filter");
			$info = ldap_get_entries($connect, $sr);
			
			return $info[0];
		}
		catch(Exception $e)
		{
			return $e;
		}

	}
}
?>