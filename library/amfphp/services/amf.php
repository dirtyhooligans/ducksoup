<?php
defined("BASE_DIR") 
	|| define("BASE_DIR", realpath(dirname(__FILE__)).'/../..');

require_once( BASE_DIR . '/library/init.php' );

class amf
{
	public function call($CALL, $PARAMS = false)
	{
		if(! empty( $CALL ) )
		{
			$call = $CALL;
		}
		
		if ( preg_match("/([App]+)\.([A-Za-z]+)\.(.*)/", $call, $call_app) )
		{
			
			defined("APP_BASE_DIR") 
				|| define("APP_BASE_DIR", realpath(dirname(__FILE__)).'/../../../applications/'.strtolower($call_app[2]));
				
			define("APP_CLASS_DIR", APP_BASE_DIR.'/library');
			
			//print_r($call_app);
			//die(APP_CLASS_DIR);
		}
		//die($call);
		if( $PARAMS )
		{
			$params = $PARAMS;
			
			if (! is_array($params) )
			{
				$params[0] = $params;
			}
		}
		else
		{
			$params = array();
		}
		
		$active_call = parse_url($active_url);
		
		$active_call['path'] = $call;
		
		$active_call['service'] = 'amf';
		
		$active_call['params'] = $params;
		
		$core = new Core(
				 BASE_DIR, 
				 $active_call, 
				 $_POST
				);

		return $core->run();
		
		/*
		if (version_compare(PHP_VERSION, '5.3.0') >= 0) 
		{
			return call_user_func_array( $call_class.'::'.$call_function , $call_params);	
		}
		else
		{
			return call_user_func_array( array($call_class, $call_function) , $call_params);	
		}
		*/
		
	}
	
}