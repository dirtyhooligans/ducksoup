<?php
/**
 * 
 *
 *
 */
 
 class Environment
 {
 	/**
     * environment settings
     *
     * @var array
     */
    protected $settings;
    
    /**
     * environment locale
     *
     * @var array
     */
    protected $locale;
    
    /**
     * user environment
     *
     * @var boolean
     */
    protected $user;
    
    /**
     * environment
     *
     * @var string
     */
    protected $location;
    
    /**
     * web server user
     *
     * @var string
     */
    protected $www_user;

    /**
     * application methods to default to main
     *
     * @var array
     */
    protected $specialMethods;
	/**
	 * 
	 */
 	public function __construct($BASE_URL)
 	{
		$hostname          = trim(strtolower($_SERVER['HTTP_HOST']));

		$user_domain_info  = preg_match("/([a-zA-Z0-9\.\-]+)\.(.*)\.([a-zA-Z]{2,4})/i", $hostname, $user_domain_match);
		
		$user_domain       = $user_domain_match[1];
		
		$config_file = BASE_DIR.'/configs/'.$user_domain.'.config';
		
		if ( is_file($config_file) )
			$this->user = true;
		else 
		{
			$doc_root_check = preg_match("/applications\/(.*)\/public/", $_SERVER['DOCUMENT_ROOT'], $doc_root_match);
           
			$app_dir = $doc_root_match[1];
           
			$doc_root_app_config  = BASE_DIR . '/applications/'.$app_dir.'/configs/' . $user_domain . '.config';
           // die($doc_root_app_config);
			if ( is_file($doc_root_app_config))
				$this->user = true;
			
			
			//echo $doc_root_app_config; die();
		}

        /**
         * TODO:
         * this will need to be used to allow functionality from an ip hostname
         * 
         * if ( preg_match("/([0-9]{1,3})\.([0-9]{1,3})\.([0-9]{1,3})\.([0-9]{1,3})/", $hostname, $ip_matches) )
		 * $user_domain = 'local';
         */

 	 	switch ($hostname)
        {
			case "localhost":
			  // Fall through
			case $_SERVER['SERVER_ADDR']:
			
				$env = 'local';
				
				break;
				
			case $this->user:
			
				$env = $user_domain;
				
				break;			
			
            default:
				
            	//$this->config = 'production';
            	$env = 'production';
            	
                break;
        }
        
        if (! empty($app_dir) && $this->user )
			$this->init($env, $app_dir);
		else
	        $this->init($env);
        
 	}
 	
 	public function init($ENVIRONMENT, $APP=false)
 	{
 		$this->location = $ENVIRONMENT;
 		
 		$this->www_user = exec('whoami');

		if ( $APP )
		{
			$config_file = BASE_DIR . '/applications/'.$APP.'/configs/' . $this->location . '.config';
		}
		else
 			$config_file = BASE_DIR . '/configs/' . $this->location . '.config';	
			
 		if ( defined("APP_BASE_DIR") )
 		{
 			$app_config_file = APP_BASE_DIR . '/configs/' . $this->location . '.config';
 		
 			if ( is_file($app_config_file) )
 			{
 				$config_file = $app_config_file;
 			}
 		}
         
 		$this->settings = new Config_File($config_file);
		
 		$main_app_config_file = BASE_DIR . '/applications/' . $this->settings->core->default->app. '/configs/' . $this->location . '.config';
 		
 		if ( is_file($main_app_config_file) )
 		{
 			$this->settings = new Config_File($main_app_config_file);
 		}

        if ( $this->settings->core->apps )
        {
            if ( $this->settings->core->apps->ignore_404 )
            {
                $methods = explode(',', $this->settings->core->apps->ignore_404);
                $total_methods = count($methods);
                $method_array = array();
                for ( $m=0; $m<$total_methods; $m++ )
                {
                    $app_method = explode('.', trim($methods[$m]));
                    $method_array[$app_method[0]][] = $app_method[1];
                }
                $this->specialMethods = (array) $method_array;
               // echo '<pre>'; print_r($this->specialMethods); echo '</pre>';
            }
            //die();
        }

 		$lang_file = BASE_DIR . '/lang/' . $this->settings->core->lang . '.ini';
 		
 		$this->lang = new Config_File($lang_file);

 		defined("CORE_LIB_PATH")
 			|| define("CORE_LIB_PATH", BASE_DIR . '/' . $this->settings->core->library->path);
 			
 		defined("DEFAULT_APP")
 			|| define("DEFAULT_APP", $this->settings->core->default->app);	
 			
 		defined("DEFAULT_CONTROLLER")
 			|| define("DEFAULT_CONTROLLER", $this->settings->core->default->controller);
 		
 		defined("DEFAULT_CONTROLLER_METHOD")
 			|| define("DEFAULT_CONTROLLER_METHOD", $this->settings->core->default->method);
		//LOGIN_SESSION_TIMEOUT
		//DOMAIN
 	}
 	
 	public function __get($property)
    {
        if ( isset( $this->$property ) )
        {
            return $this->$property;
        }
        else
        {
            return false;
        }
    }
 	
}
?>