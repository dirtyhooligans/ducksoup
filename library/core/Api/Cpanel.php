<?php
class Api_Cpanel extends Api
{
	public function __construct($CORE)
	{
		parent::__construct($CORE);
	}
	
	public function getAccounts($BY = 'user')
	{
		$by = $BY;
		$params['searchtype'] = $by;
		return self::call('listaccts', $params);
	}
	
	private function call($FUNCTION, $PARAMS = array(), $FORMAT = 'json')
	{
		$function = $FUNCTION;
		$params   = $PARAMS;
	
		return $this->core->config;
	
		$whmusername = $this->core->config->cpanel->user;
		
		$whmpassword = $this->core->config->cpanel->pass;
		
		$url = $this->core->config->cpanel->host."/json-api/".$function;
		
		$total_params = count($params);
		
		if (is_array($params) && $total_params > 0 )
		{
			$query = "?";
			$query_count = 0;
			
			foreach( $params as $var => $value)
			{
				if ( $query_count > 0 )
					$query .= "&";
				$query .= $var."=".$value; 
				
				$query_count++;
			}
			
			$url = $url . $query;
		}
		
		$curl = curl_init();		
		# Create Curl Object
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER,0);	
		# Allow self-signed certs
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST,0); 	
		# Allow certs that do not match the hostname
		curl_setopt($curl, CURLOPT_HEADER,0);			
		# Do not include header in output
		curl_setopt($curl, CURLOPT_RETURNTRANSFER,1);	
		# Return contents of transfer on curl_exec
		$header[0] = "Authorization: Basic " . base64_encode($whmusername.":".$whmpassword) . "\n\r";
		curl_setopt($curl, CURLOPT_HTTPHEADER, $header);  
		# set the username and password
		curl_setopt($curl, CURLOPT_URL, $url);			
		# execute the query
		$result = curl_exec($curl);
		if ($result == false) {
			error_log("curl_exec threw error \"" . curl_error($curl) . "\" for $query");	
		# log error if curl exec fails
		}
		curl_close($curl);
		
		$return['result'] = json_decode($result);
		$return['url'] = $url;
		return $return;
	}
	
}
?>