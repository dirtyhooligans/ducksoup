<?php

class Db
{
	protected $db;
	
	protected $_connection = null;
	
	public static $core;
	
	public static $config;
	
	protected $adapter;
	
	protected $host;
	
	protected $name;
	
	protected $user;
	
	protected $pass;
	
	protected $port;
	
	public function __construct($CORE, $DB = 'main')
	{
		$this->core = $CORE;
		
		$this->config = $this->core->config->db->$DB;
		
		$this->adapter = strtolower($this->config->adapter);
		
		$this->host = $this->config->host;
        $this->user = $this->config->user;
        $this->pass = $this->config->pass;
        $this->name = $this->config->name;
		$this->port = $this->config->port;
		
		require_once BASE_DIR . '/library/adodb_lite/adodb-exceptions.inc.php';
		require_once BASE_DIR . '/library/adodb_lite/adodb.inc.php';
		
		$this->db = ADONewConnection($this->adapter);
		$this->db->debug = true;
		$this->db->port = $db->port;
		
        $this->connect();
	}
	
	protected function connect()
    {

    	if ($this->_connection) 
    	{
            return;
        }
    	try
		{
			
			$this->_connection = $this->db->Connect($this->host, $this->user, $this->pass, $this->name);
		}
		catch (exception $e)
		{
			echo 'catch';
		    var_dump($e);
		}
        //$this->_connection = $this->db->Connect($this->host, $this->user, $this->pass, $this->name);
        //echo $this->db->IsConnected();
        //echo $this->_connection;
        //echo 'here';
    }
}
?>