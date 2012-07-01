<?php
/*
 * 
 * 
 */

class Controller 
{	
	public static $core;
	/*
	 * 
	 * 
	 */
	private $strViewPath = "";
	
	/*
	 * 
	 * 
	 */
	public static $page_title;
	
	public static $header_title;
	
	public static $user;
	
	public $app;
	
	public $header_includes;
	
	public $compatible;
	
	public $protocol;
	
	public function __construct($CORE)
	{
		$this->core = $CORE;
		
		$this->page_title = $this->core->config->site_name;
		
		$this->strViewPath = dirname(__FILE__).'/View';
		
		$this->header_includes = array();
		
		if ( strpos( $_SERVER['SERVER_PROTOCOL'],'HTTPS' ) )
			$this->protocol = 'https';
		else
			$this->protocol = 'http';

		$this->core->init_js .= 'var base_rtmp_uri = "rtmp://' . $_SERVER['HTTP_HOST'] . '"' . "\n";
		
		if ( Auth::verify() && !empty($_SESSION['u']['id']) )
		{
			$this->user = Model_User::getUser($_SESSION['u']['id']);
			
			$this->core->init_js .= 'var active_user_id = "'.$this->user['id'].'";' . "\n";
		}
	}
	
	public function loadView($strView, $arrParams = array()) 
	{
		$viewParams = $arrParams;
		
		$boolLoaded = false;
		
		$view_file = $this->strViewPath . "/" . str_replace('_', '/', $strView ) . ".php";
		
		if(is_dir( BASE_DIR . '/applications/' . $this->core->main_app ))
		{
			$main_app_file = BASE_DIR . '/applications/' . $this->core->main_app . "/library/View/" . str_replace('_', '/', $strView ) . ".php";
			if ( is_file($main_app_file) )
			{
				$view_file = $main_app_file;
			}
		}
		
		if (! empty( $this->app['app'] ) && is_dir( BASE_DIR . '/applications/' . $this->app['app'] ))
		{
			
			$app_view_file = BASE_DIR . '/applications/' . $this->app['app'] . "/library/View/" . str_replace('_', '/', $strView ) . ".php";
			
		}

		if ( @include($app_view_file) )
		{
			$boolLoaded = true;
		}
		else if(@include($view_file))
		{
			$boolLoaded = true;
		}
		
		if(!$boolLoaded) 
		{
              // TODO: create custom exception
			throw new Exception("View not found {$strView}");
		}

	}
	
	public function redirect($strUrl) 
	{
		header("Location: " . $strUrl);
		exit();
	}
	
}
?>