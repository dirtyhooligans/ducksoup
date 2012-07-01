<?php
/**
 * 
 *
 *
 */
 
 class Core
 {
 	/**
     * Bootstrap
     *
     * @var Core_Bootstrap
     */
    protected $bootstrap;

    /**
     * Core environment
     *
     * @var array
     */
    public static $env;
    
    /**
     * Core config object
     *
     * @var array
     */
    public static $config;
    
    /**
     * Core properties object
     *
     * @var array
     */
    public static $properties;
    
    /**
     * Core main app
     *
     * @var string
     */
    public static $main_app;
    
    /**
     * Core environment
     *
     * @var string
     */
    public static $debug;
    
 	/**
     * Active URL
     *
     * @var string
     */
 	public $url;
 	
 	/**
     * Request, Post, Get Array
     *
     * @var string
     */
 	public $http_vars;
 	
 	/**
     * Request, Post, Get Array
     *
     * @var string
     */
 	public $request;
 	
 	/**
     * User Browser, OS/Platform Details object
     *
     * @var object (Browser);
     */
 	public $user_agent;
 	
 	/**
     * Global Application JavaScript
     *
     * @var string
     */
 	public $init_js;
 	
 	/**
     * Error
     *
     * @var string
     */
 	public $error;
 	
	/**
	 * 
	 */
	public function __construct($BASE_DIR, $URL_DATA, $POST)
	{
		ini_set('error_reporting', E_ALL & ~E_NOTICE);
		ini_set('display_errors', 'On');

		$this->error = new Error($this);
		
		$this->url_data   = $URL_DATA;
		
		$this->request    = str_replace( BASE_URL_PATH, '', $this->url_data['path']);
		
		$this->http_vars  = Bootstrap::buildHttpVariables($URL_DATA['query'], $POST);

		$this->env        = new Environment($BASE_DIR);
		
		$this->config     = $this->env->settings->core;
		
		$this->lang       = $this->env->lang;
		
		$this->main_app   = $this->config->default->app;
		
		$this->user_agent = new Browser();
		
		$this->properties = array();
		
		$this->properties['site_name'] = $this->config->site_name;
		$this->properties['copyright'] = "&copy " . date('Y') . " " . $this->config->copyright;
		
		$this->init_js    = $this->initJS();
		
		$this->debug      = $this->config->debug;
		
		$this->app = new Application();
		
		ini_set('session.save_path', BASE_DIR . '/tmp/sessions');
		
		session_start();
	}
	
	/**
	 * 
	 */
	public function run()
	{
		if ( $this->url_data['service'] == 'amf')
		{
			$arrModelParts = explode('.', $this->request);
			
			foreach($arrModelParts as $intIndex => $strValue) 
			{
				if($strValue)
					$arrModelPath[] = $strValue;
			}
			
			if(sizeof($arrModelPath) >= 1) 
			{	
				$strModelName = "Model";
				$strModelMethod = "";
				
				foreach($arrModelPath as $intIndex => $strValue) 
				{
					if($intIndex + 1 == sizeof($arrModelPath))
					{
						$strModelMethod = $strValue;
					} 
					elseif($strValue)
					{
						$strModelName .= $strModelName ? "_".ucfirst(strtolower($strValue)) : ucfirst(strtolower($strValue));
					}
				}
				
				$model_class_file = CORE_LIB_PATH . "/" . str_replace('_', '/', $strModelName ) . ".php";
				
				if ( $strModelName == 'Model_Applications' )
				{
					$strModelName = 'Model_App';
				}
				
				if ( strpos($strModelName, 'Model_Applications') )
				{
					$strModelName = str_replace('Model_Applications', 'Model_App', $strModelName);;
				}
				
				if ( $strModelName == 'Model_App' )
				{
					$strModelName = $strModelName. '_' . ucfirst(strtolower($strModelMethod));
					$strModelMethod = 'main';
					
					$model_class_file = APP_CLASS_DIR . "/" . str_replace('_', '/', $strModelName ) . ".php";
				}
				
				$model_app_class_file = APP_CLASS_DIR . "/" . str_replace('_', '/', $strModelName ) . ".php";
				
				$alt_model_app_class_dir = BASE_DIR . str_replace('applications/'.$this->main_app.'/library', 'applications/'.strtolower($arrModelParts[1]).'/library', str_replace(BASE_DIR, '', APP_CLASS_DIR));
				
				$alt_model_app_class_file = $alt_model_app_class_dir . "/" . str_replace('_', '/', $strModelName ) . ".php";
				
				if ( is_file( $model_app_class_file ) )
				{
					$model_class_file = $model_app_class_file;
				}
				elseif( is_file($alt_model_app_class_file) )
				{
					$this->app->setName(strtolower($arrModelParts[1]));
					$model_class_file = $alt_model_app_class_file;
				}
			
				if (! file_exists($model_class_file) )
				{
					// TODO: Create custom exception
					throw new Exception("Unable to load model ({$strModelName}) file: ".$model_class_file." app_class: ".APP_CLASS_DIR);
				}
				elseif(!method_exists($strModelName, $strModelMethod)) 
				{
					// TODO: Create custom exception
					throw new Exception("Method ({$strModelMethod}) does not exist in model ({$strModelName})");
				}
				else 
				{
					$objModel = new $strModelName($this);
				}
			} 
			else 
			{	
				$strModel = "Model_App_".ucfirst($this->main_app)."_".ucfirst(strtolower(DEFAULT_CONTROLLER));
				$objModel = new $strModel($this);
				$strModelMethod = DEFAULT_CONTROLLER_METHOD; 
			}
			
			$call_params = $this->url_data['params'];
			return call_user_func_array( array($objModel, $strModelMethod) , $call_params);	
			
			if (version_compare(PHP_VERSION, '5.3.0') >= 0) 
			{
				return call_user_func_array( $objModel->$strModelMethod , $call_params);	
			}
			else
			{
				return call_user_func_array( array($objModel, $strModelMethod) , $call_params);	
			}
		}
		else
		{
			
			$arrControllerParts = explode('/', $this->request);
			
			foreach($arrControllerParts as $intIndex => $strValue) 
			{
				if($strValue)
					$arrControllerPath[] = $strValue;
			}
			
			if(sizeof($arrControllerPath) >= 2) 
			{	
				$strControllerName = "Controller";
				$strControllerMethod = "";
				
				foreach($arrControllerPath as $intIndex => $strValue) 
				{
					if($intIndex + 1 == sizeof($arrControllerPath))
					{
						$strControllerMethod = $strValue;
					} 
					elseif($strValue)
					{
						$strControllerName .= $strControllerName ? "_" . ucfirst(strtolower($strValue)) : ucfirst(strtolower($strValue));
					}
				}
				
				$controller_class_file = CORE_LIB_PATH . "/" . str_replace('_', '/', $strControllerName ) . ".php";
								
				if ( $strControllerName == 'Controller_Applications' )
				{
					$strControllerName = 'Controller_App';
				}
				
				if ( strpos($strControllerName, 'Controller_Applications') )
				{
					$strControllerName = str_replace('Controller_Applications', 'Controller_App', $strControllerName);
				}
				
				if ( $strControllerName == 'Controller_App' )
				{
					$strControllerName = $strControllerName. '_' . ucfirst(strtolower($strControllerMethod));
					$strControllerMethod = DEFAULT_CONTROLLER_METHOD;
					
					$controller_class_file = APP_CLASS_DIR . "/" . str_replace('_', '/', $strControllerName ) . ".php";
				}
				
				$controller_app_class_file = APP_CLASS_DIR . "/" . str_replace('_', '/', $strControllerName ) . ".php";

                $alt_app = false;

                if ( $alt_app_check = preg_match("/App\/(\w+)/", $controller_app_class_file, $app_match) )
                {
                    $alt_app = strtolower($app_match[1]);
                }

				if ( is_file( $controller_app_class_file ) )
				{
					$controller_class_file = $controller_app_class_file;
				}
				else
				{
					if ( $alt_app )
					{
						$controller_app_class_file = str_replace('applications/'.$this->main_app, 'applications/'.$alt_app, $controller_app_class_file);
				
						if ( is_file ( $controller_app_class_file ) )
						{
							$controller_class_file = $controller_app_class_file;
							$this->app->setName($alt_app);
						}
					}	
					else
					{
						$main_app_controller = preg_replace("/Controller\/(\w+)/", '../../applications/'.$this->main_app.'/library/Controller/App/'.ucwords($this->main_app).'/$1', $controller_class_file);
						$main_app_controller = realpath($main_app_controller);
						
						if ( is_file( $main_app_controller ) )
						{
							$controller_class_file = $main_app_controller;
							$strControllerName = preg_replace("/Controller_(\w+)/", 'Controller_App_' . ucfirst($this->main_app) .'_$1', $strControllerName);
							//echo $strControllerName;
						}
					}
				}

				if (! file_exists($controller_class_file) )
				{
					$controller_alt_app_class_file = str_replace('/applications/'.$this->main_app, '', $controller_class_file);
					
					if ( file_exists($controller_alt_app_class_file) )
					{
						 $controller_class_file = $controller_alt_app_class_file;
					}
					else
					{
                        if ( (array)$this->env->specialMethods[$alt_app] === $this->env->specialMethods[$alt_app]  )
                        {
                            $strController = $this->getSpecialController($alt_app, $strControllerName,$strControllerMethod);
                            //die($strController);
                            $strControllerMethod = DEFAULT_CONTROLLER_METHOD;

                            $objController = new $strController($this);
                            $objController->$strControllerMethod();
                        }
                        else
                        {
                            $objController = new Controller_Errors_Page($this);
                            $objController->controller_not_found($strControllerName, $strControllerMethod, $controller_class_file, $controller_alt_app_class_file);
                        }
					}
				}
				elseif(!method_exists($strControllerName, $strControllerMethod))
				{
					if ( (array)$this->env->specialMethods[$alt_app] === $this->env->specialMethods[$alt_app]  )
                    {
                        $strController = $this->getSpecialController($alt_app, $strControllerName, $strControllerMethod);
                        
                        $strControllerMethod = DEFAULT_CONTROLLER_METHOD;

                        $objController = new $strController($this);
                        $objController->$strControllerMethod();
                    }
                    else
                    {
                        $objController = new Controller_Errors_Page($this);
					    $objController->method_not_found($strControllerName, $strControllerMethod, $controller_class_file, $controller_alt_app_class_file);
                    }
				}
				else 
				{
					$objController = new $strControllerName($this);
					$objController->$strControllerMethod();
				} 		
			} 
			else 
			{

				$strController = "Controller_App_".ucfirst(DEFAULT_APP)."_".ucfirst(strtolower(DEFAULT_CONTROLLER));
				$objController = new $strController($this);

				$strControllerMethod = DEFAULT_CONTROLLER_METHOD;
				$objController->$strControllerMethod();
			}
		}

		if ( $this->debug )
		{
			//Session::dump();
			//echo '<pre>'; print_r($this); echo '</pre>';
		}
	}

    private function getSpecialController($alt_app,$strControllerName, $strControllerMethod)
    {
        $strController = "Controller_App_".ucfirst($alt_app)."_".ucfirst(strtolower(DEFAULT_CONTROLLER));

        if (! empty($strControllerName) )
        {
            $controller_url = strtolower(str_replace('Controller_App_','',$strControllerName))."";
            $controllerCheck = $controller_url;

            $ctrl_count = 0;
            $max_ctrl_check = 25;

            while ( $controllerCheck != $alt_app )
            {
                $controllerCheck = preg_replace("/_[^_]+$/", "", $controllerCheck);

                $protected_method_check = str_replace($alt_app."_", '',$controllerCheck);

                if ( in_array($protected_method_check, $this->env->specialMethods[$alt_app]) )
                {

                    $this->http_vars[$protected_method_check] = str_replace($controllerCheck.'_','',$controller_url).'_'.$strControllerMethod;
                    $strController = "Controller_App_".str_replace(' ','_',ucwords(str_replace('_',' ',preg_replace("/_[^_]+$/", "",$controllerCheck))));
                    break;
                }

                if ( $ctrl_count >= $max_ctrl_check )
                    break;
                $ctrl_count++;
            }
        }
        else
        {
            if ( in_array($strControllerMethod, $this->env->specialMethods[$alt_app]) )
            {

                $this->http_vars[$strControllerMethod] = true;
                //$strController = "Controller_App_".str_replace(' ','_',ucwords(str_replace('_',' ',preg_replace("/_[^_]+$/", "",$controllerCheck))));
                break;
            }
        }
        return $strController;
    }
	
	/*
	 * 
	 * 
	 */
	public function initJS()
	{
		$return .= 'var base_url = "' . BASE_URL . '";' . "\n";
		
		if ( defined("APP_BASE_URL") )
		{
			$return .= 'var app_base_url = "' . APP_BASE_URL . '";' . "\n";
		}
		
		return $return;
	}
 }

?>