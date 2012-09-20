<?php
/*
 * 
 * 
 * 
 */
class Db_Mysqli
{	
	protected $_connection = null;
	
	public static $core;
	
	public static $config;
	
	protected $Host;
	
	protected $Name;
	
	protected $User;
	
	protected $Pass;
	
	protected $Port;
	
	protected $Db;
	
	public function __construct($CORE, $DB = 'main')
	{
		$this->core = $CORE;
		
		$this->Db = $DB;
		$this->config = $this->core->config->db->$DB;
		
		$this->Host = $this->config->host;
        $this->User = $this->config->user;
        $this->Pass = $this->config->pass;
        $this->Name = $this->config->name;
		$this->Port = $this->config->port;
				
        $this->connect();
	}
	
	protected function connect()
    {
    	if ($this->_connection) 
    	{
            return;
        }

        if (!extension_loaded('mysqli')) 
        {
            throw new Core_Exception('The Mysqli extension is required for this adapter but the extension is not loaded');
        }

        if (isset($this->Port)) {
            $port = (integer) $this->Port;
        } 
        else 
        {
            $port = null;
        }

        $this->_connection = mysqli_init();

        if(!empty($this->_config['driver_options'])) 
        {
            foreach($this->_config['driver_options'] as $option=>$value) 
            {
                if(is_string($option)) 
                {
                    // Suppress warnings here
                    // Ignore it if it's not a valid constant
                    $option = @constant(strtoupper($option));
                    if($option === null)
                        continue;
                }
                mysqli_options($this->_connection, $option, $value);
            }
        }

        // Suppress connection warnings here.
        // Throw an exception instead.
        $_isConnected = @mysqli_real_connect(
            $this->_connection,
            $this->Host,
            $this->User,
            $this->Pass,
            $this->Name,
            $port
        );

        if ($_isConnected === false || mysqli_connect_errno()) 
        {

            $this->closeConnection();
            /**
             * @see Zend_Db_Adapter_Mysqli_Exception
             */
          	die(mysqli_connect_error());
            //throw new Exception(mysqli_connect_error());
        }

        if (!empty($this->_config['charset'])) {
            mysqli_set_charset($this->_connection, $this->_config['charset']);
        }
    }
    
    /**
     * Test if a connection is active
     *
     * @return boolean
     */
    public function isConnected()
    {
        return ((bool) ($this->_connection instanceof mysqli));
    }

    /**
     * Force the connection to close.
     *
     * @return void
     */
    public function closeConnection()
    {
        if ($this->isConnected()) {
            $this->_connection->close();
        }
        $this->_connection = null;
    }
    
	
	public function Select($PARAMS = array())
	{
		if (isset($PARAMS['db'])) 
		{
			$this->ChangeDB($PARAMS['db']);
		}
		
		if (! empty($PARAMS['query']) )
		{
			$query = $PARAMS['query'];
			
			$patterns[0] = "/ \'(.+)\' /";
			
			$replaces[0] = mysqli_real_escape_string($this->_connection, "\1");
			
			$query = preg_replace($patterns, $replaces, $query, $total_strings);
			
		}
		
		$return['error'] = false;
		
		// TODO: for initial dev, may remove if it causes too much latency with larger queries
		$return['query'] = str_replace("\n", " ", str_replace("\t", "", str_replace("    ", "", $query )));
		
		$result = $this->_connection->query($query);

        if ( $result )
		{
            $return['valid']   = true;
			
			$return['records'] = false;
			
			while ( $row = $result->fetch_assoc() )
            {
                $records[] = $row;
            }
			
			$total = 0;
			
			if ( is_array( $records[0] ) )
			{
				$total = count($records);
				
				$return['records'] = $records;
				
				$return['fields'] = $result->fetch_fields();
			}
			
			$return['total'] = $total;
			
			
			$result->close();
        }
        else
        {
			$return['valid'] = false;
            //throw new Exception( $this->error ."\n$query" );
			//throw new Exception($this->_connection->error . ' - ' .$this->_connection->errno);
			$return['error'] = Error::db('Mysqli::Select', $this->_connection->error, $this->_connection->errno);
			$return['error']['query'] = $query;
			
        }

		return $return;
	}
	
	public function Update($PARAMS = array())
	{

		if (isset($PARAMS['db'])) 
		{
			$this->ChangeDB($PARAMS['db']);
		}
		
		if (! empty($PARAMS['query']) )
		{
			$query = $PARAMS['query'];
			
			$patterns[0] = "/ \'(.+)\' /";
			
			$replaces[0] = mysqli_real_escape_string($this->_connection, "\1");
			
			$query = preg_replace($patterns, $replaces, $query, $total_strings);
			
		}
		
		$return['error'] = false;
		
		$result = $this->_connection->query($query);

        if ($result)
        {
            $return['valid'] = true;
			
        }
        else
        {
            //throw new Exception(self::$_sInstance->error . "<br />\n$query");
			$return['error'] = Error::db('Mysqli::Update', $this->_connection->error, $this->_connection->errno);
			$return['error']['query'] = $query;
		}
		
		
		
		return $return;
	}
	
	public function Insert($PARAMS = array())
	{
		if (isset($PARAMS['db'])) 
		{
			$this->ChangeDB($PARAMS['db']);
		}
		
		if (! empty($PARAMS['query']) )
		{
			$query = $PARAMS['query'];
			
			$patterns[0] = "/ \'(.+)\' /";
			
			$replaces[0] = mysqli_real_escape_string($this->_connection, "\1");
			
			$query = preg_replace($patterns, $replaces, $query, $total_strings);
			
		}
		
		$return['error'] = false;
		
		$result = $this->_connection->query($query);

        if ( $result )
        {
			$return['valid']     = true;
			$return['insert_id'] = $this->_connection->insert_id;

        }
        else
        {
            $return['error'] = Error::db('Mysqli::Insert', $this->_connection->error, $this->_connection->errno);
		}
		
		return $return;
	}
	
	public function Delete($PARAMS = array())
	{
		if (isset($PARAMS['db'])) 
		{
			$this->ChangeDB($PARAMS['db']);
		}
		
		if (! empty($PARAMS['query']) )
		{
			$query = $PARAMS['query'];
			
			$patterns[0] = "/ \'(.+)\' /";
			
			$replaces[0] = mysqli_real_escape_string($this->_connection, "\1");
			
			$query = preg_replace($patterns, $replaces, $query, $total_strings);
			
		}
		
		$return['error'] = false;
		
		$result = $this->_connection->query($query);

        if ( $result )
        {
			$return['valid']     = true;
        }
        else
        {
            // throw new Exception(self::$_sInstance->error . "<br />\n$query");
            $return['error'] = Error::db('Mysqli::Delete', $this->_connection->error, $this->_connection->errno);
			//$return['error'] = Core_Exception($this->_connection->error . ' - ' .$this->_connection->errno);	
		}

		return $return;
	}
	
	public function ChangeDB($DB = 'main')
    {
    	if ( $this->Db == $DB )
    		return;

        $this->Host = $this->config->$DB->host;
        $this->User = $this->config->$DB->user;
        $this->Pass = $this->config->$DB->pass;
        $this->Name = $this->config->$DB->name;
		$this->Port = $this->config->$DB->port;

		$this->connect();
    }
	
	public function escapeString($str)
    {
    	return mysqli_real_escape_string($this->_connection, $str);
    }
}