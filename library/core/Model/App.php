<?php
class Model_App extends Model
{	
	public static $app_config;
	
	public function __construct($CORE)
	{
		parent::__construct($CORE);
		
		$config_file = APP_BASE_DIR . '/configs/'.$this->core->env->location.'.config';
		
		if ( is_file($config_file) )
			$this->app_config = new Config_File($config_file);
		else
			$this->app_config = $this->core->config;
	}
	
}

?>