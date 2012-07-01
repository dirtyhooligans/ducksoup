<?php
class Controller_App extends Controller
{	
	public static $app_config = false;
	
	public function __construct($CORE)
	{
		parent::__construct($CORE);
		
		$config_file = APP_BASE_DIR . '/configs/'.$this->core->env->location.'.config';
		
		if ( is_file( $config_file ) )
			$this->app_config = new Config_File($config_file);
			
		defined("APP_BASE_DIR") 
				|| define("APP_BASE_DIR", BASE_DIR . '/applications/' . $this->core->main_app  );
				
		defined("APP_BASE_URL") 
			|| define("APP_BASE_URL", $this->protocol.'://'.$_SERVER['HTTP_HOST'] .BASE_URL_PATH . str_replace(str_replace('public/..', '', BASE_DIR), '', str_replace('public/..', '', str_replace('applications', 'app', APP_BASE_DIR))));
		
		defined("APP_CLASS_DIR") 
				|| define("APP_CLASS_DIR", APP_BASE_DIR.'/library');
	}
	
}

?>