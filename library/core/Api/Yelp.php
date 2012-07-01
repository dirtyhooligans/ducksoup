<?php
class Api_Yelp
{
	public $core;
	
	public $consumer_key;
	public $consumer_secret;
	public $token;
	public $token_secret;
	public $api_url;
	
	public function __construct($CORE)
	{
		$this->core = $CORE;
		
		$this->api_url         = $this->core->config->api->yelp->url;
		
		$this->consumer_key    = $this->core->config->api->yelp->consumer_key;
		$this->consumer_secret = $this->core->config->api->yelp->consumer_secret;
		$this->token           = $this->core->config->api->yelp->token;
		$this->token_secret    = $this->core->config->api->yelp->token_secret;
	}
	
	public function getByGeo($TERM, $LATITUDE, $LONGITUDE, $ALTITUDE=false)
	{
		$term = $TERM;		
		$lat  = $LATITUDE;
		$lng  = $LONGITUDE;
		$alt  = $ALTITUDE;
		$dst  = $DISTANCE;
				
		$url = $this->api_url;
		
		$url .= "/search?";
		
		if (! empty($term) ) 
			$url .= "term=" . str_replace(' ', '+', $term); 
		else
			return self::error(__CLASS__, __METHOD__, 'No Search Term Provided');
			
		$url .= "&ll=".$lat.','.$lng;
		
		if ( $alt )
			$url .= ','.$alt;
		
		return $this->saveYelpResult($this->request($url));
	}
	
	public function getByLocation($TERM, $LOCATION)
	{
		$term = $TERM;		
		$loc  = $LOCATION;
				
		$url = $this->api_url;
		
		$url .= "/search?";
		
		if (! empty($term) ) 
			$url .= "term=" . str_replace(' ', '+', $term);
		else
			return self::error(__CLASS__, __METHOD__, 'No Search Term Provided');
			
		$url .= "&location=".$loc;
		
		return $this->saveYelpResult($this->request($url));
	}
	
	public function saveYelpResult($DATA)
	{
		$data = $DATA;
		
		if ( $data['error'] )
			return $data;
		
		if ( is_array($data['businesses']) )
		{
			$total = count($data['businesses']);
			for($i=0;$i<$total;$i++)
            {
                $return[] = self::getPlace($this->saveYelpPlace($data['businesses'][$i]));
            }
		}
		else
			$return = $result;
		
		return $return;
	}
	
	public function saveYelpPlace($DATA)
	{
		$data = $DATA;
		
		$name           = $data['name'];
		$yelp_id        = $data['id'];
		$yelp_url       = $data['url'];
		$yelp_image_url = $data['image_url'];
		$phone          = $data['phone'];
		
		foreach ($data['location']['address'] as $addr)
			$address   .= $addr . ' ';
		$city           = $data['location']['city'];
		$state          = $data['location']['state_code'];
		$postal_code    = $data['location']['postal_code'];
		$country_code   = $data['location']['country_code'];
		$latitude       = $data['location']['coordinate']['latitude'];
		$longitude      = $data['location']['coordinate']['longitude'];
		
		if ( isset($_SESSION['u']['id']) )
			$user_id = (int) $_SESSION['u']['id'];
		else
			$user_id = 1000;

		
		if (! $this->db )
			$this->db = new Db_Mysqli($this->core, 'ducksoup');
			
		$isPlace = $this->isPlace($yelp_id);	
			
		if ($isPlace)
			return $isPlace;
			
		$query = sprintf(
		                "
		                INSERT INTO
		                    %s.`places`
		              	SET
		                	`name` = '%s',
		                	`yelp_id` = '%s',
		                	`yelp_url` = '%s',
		                	`yelp_image_url` = '%s',
		                	`phone`  = '%s',
		                	`address` = '%s',
		                	`city` = '%s',
		                	`state` = '%s',
		                	`postal_code` = '%s',
		                	`country_code` = '%s',
		                	`latitude` = '%s',
		                	`longitude` = '%s',
		                	`added_by` = %d
		                "
		                ,
		                $this->db->config->name,
		                $this->db->escapeString($name),
		                $yelp_id,
		                $yelp_url,
		                $yelp_image_url,
		                $phone,
		                $address,
		                $city,
		                $state,
		                $postal_code,
		                $country_code,
		                $latitude,
		                $longitude,
		                $user_id
		                );
					                 
		$results = $this->db->Insert(array("query" => $query));
		                 
		if( $results['valid'] )
		{
			return $results['insert_id'];
		}
		else
		{
			return $results;
		}
	}
	
	public function isPlace($YELP_ID)
	{
		$yelp_id = $YELP_ID;

		if ( empty($yelp_id) )
			return false;
	
		if (! $this->db )
			$this->db = new Db_Mysqli($this->core, 'ducksoup');
			
		$query = sprintf(
		                "
		                SELECT
		                	p.`id`
		                FROM
		                    %s.`places` p
		                WHERE
		                	p.`yelp_id` = '%s'
		                "
		                ,
		                $this->db->config->name,
		                $yelp_id
		                );
		                 
        $results = $this->db->Select(array("query" => $query));

        if( $results['valid'] )
        {
        	$id = $results['records'][0]['id'];
        	if (! empty($id))
        		return $id;
        	else
        		return false;
        }
        else
        {
        	return $results;
        }
	}

    public function getPlace($ID)
	{
		$id = (int) $ID;

		if (! $this->db )
			$this->db = new Db_Mysqli($this->core, 'ducksoup');

		$query = sprintf(
		                "
		                SELECT
		                	p.`id`,
		                	p.`name`,
		                	p.`yelp_id`,
		                	p.`yelp_url`,
		                	p.`yelp_image_url`,
		                	p.`phone`,
		                	p.`address`,
		                	p.`city`,
		                	p.`state`,
		                	p.`postal_code`,
		                	p.`country_code`,
		                	p.`latitude`,
		                	p.`longitude`,
		                	p.`added`,
		                	UNIX_TIMESTAMP(p.`added`) AS `added_unix`,
		                	p.`added_by`,
		                	p.`updated`
		                FROM
		                    %s.`places` p
		                WHERE
		                	p.`id` = %d
		                "
		                ,
		                $this->db->config->name,
		                $id
		                );

        $results = $this->db->Select(array("query" => $query));

        if( $results['valid'] )
        {
        	if (! empty($results['records'][0]['id']) )
        	    return $results['records'][0];
        	else
        		return false;
        }
        else
        {
        	return $results;
        }
	}
	
	public function request($URL)
	{
			
		$unsigned_url = $URL;

		// Token object built using the OAuth library
		$token = new Oauth_Token($this->token, $this->token_secret);
		
		// Consumer object built using the OAuth library
		$consumer = new Oauth_Consumer($this->consumer_key, $this->consumer_secret);
		
		// Yelp uses HMAC SHA1 encoding
		$signature_method = new Oauth_Signaturemethod_Hmacsha1();
		
		// Build OAuth Request using the OAuth PHP library. Uses the consumer and token object created above.
		$oauthrequest = Oauth_Request::from_consumer_and_token($consumer, $token, 'GET', $unsigned_url);
		
		// Sign the request
		$oauthrequest->sign_request($signature_method, $consumer, $token);
		
		// Get the signed URL
		$signed_url = $oauthrequest->to_url();
		
		// Send Yelp API Call
		$ch = curl_init($signed_url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		$data = curl_exec($ch); // Yelp response
		curl_close($ch);
		
		// return Yelp response data
		return json_decode($data, true);
	}

	public function error($CLASS, $METHOD, $MESSAGE)
	{
		$return['status'] = 'FAIL';
		$return['api'] = 'Yelp';
		$return['call'] = $CLASS . '::' . $METHOD;
		$return['message'] = $MESSAGE;
		
		return $return;
	}
}
?>