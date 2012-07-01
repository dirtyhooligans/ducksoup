<?php
/**
 * 
 *
 *
 */
 
 class Bootstrap
 {
	/**
	 * 
	 */
 	public function headerInclude($FILE_DATA)
 	{
 		$file_data = $FILE_DATA;
 		
 		
 		
 		if ( is_array( $file_data ))
 		{
 			$total_file_types = count($file_data);
 			
 			foreach( $file_data as $file_type_key => $file_type )
 			{
 				$total_files = count($file_data[$file_type_key]);
 				
 				for ($f = 0; $f < $total_files; $f++ )
 				{
 					
 					switch($file_type_key)
 					{
 						case 'js':
 						
 							$return .= str_replace('//js', '/js', self::includeJS($file_data[$file_type_key][$f]) );
 							
 							break;
 							
 						case 'css':
 							
 							$return .= str_replace('//css', '/css', self::includeCSS($file_data[$file_type_key][$f]));
 							
 							break;
 							
 						default:
 							
 							break;
 					}
 					
 				}
 			}
 		}
		
		return $return;		
 	}
 	
 	public function includeJS($FILE)
    {
    	$markup = '';
    	
    	$file = trim(str_replace("\n", "", $FILE));
    	
    	$file_url = false;
    	
    	if ( defined("APP_BASE_DIR") )
    	{
    		if ( is_file( APP_BASE_DIR .'/public/js/' .$file ) )
    		{
    			$file_url = APP_BASE_URL .'/js/' .$file;
    		}
    		else
    		{
	    		if ( is_file( BASE_DIR .'/public/js/' .$file ) )
	    		{
	    			$file_url = BASE_URL .'/js/' .$file;
	    		}
    		}
    	}
    	else
    	{
    		if ( is_file( BASE_DIR .'/public/js/' .$file ) )
    		{
    			$file_url = BASE_URL .'/js/' .$file;
    		}
    	}
    	
    	if ( $file_url )
    	{
    		if ( $this->core->config->prevent_cache )
    			$file_url = $file_url . '?x=' . mktime();
    			
    		$markup = '<script type="text/javascript" src="'. $file_url. '"></script>'."\n";
    	}
    	
    	return $markup;
	}

    public function includeCSS($FILE)
	{
		$markup = false;
		
		$file = trim(str_replace("\n", "", $FILE));
    	
    	$file_url = false;
    	
    	if ( defined("APP_BASE_DIR") )
    	{
    		
    		if ( is_file( APP_BASE_DIR .'/public/css/' .$file ) )
    		{
    			$file_url = APP_BASE_URL .'/css/' .$file;
    		}
    		else
    		{
	    		if ( is_file( BASE_DIR .'/public/css/' .$file ) )
	    		{
	    			$file_url = BASE_URL .'/css/' .$file;
	    		}
    		}
    	}
    	else
    	{
    		if ( is_file( BASE_DIR .'/css/' .$file ) )
    		{
    			$file_url = BASE_URL .'/css/' .$file;
    		}
    	}
    	
    	if ( $file_url )
    	{
        	$markup = '<link rel="stylesheet" type="text/css" href="'.$file_url.'"/>'."\n";
    	}
    	    
    	return $markup;
	}
 	
	/**
	 * 
	 */
	public function buildHttpVariables($REQUEST, $POST)
	{
		parse_str($REQUEST, $request);
		
		$post = $POST;
		
		$return = array();
		
		if(! empty( $request['params'] ) )
		{
			$return = self::getParams($request['params']);
		}
		
		foreach($request as $var => $value)
		{
			if( $var != 'params' )
			{
				$return[$var] = $value;
			}
		}
		
		foreach($post as $var => $value)
		{
			if( $var != 'params' )
			{
				$return[$var] = $value;
			}
		}
		
		return $return;
	}
	
	/**
	 * 
	 * 
	 */
	public function getParams($PARAMS)
	{
		
		// ?params=key:value;
		if(!empty($PARAMS))
		{
			//$params_format = preg_replace("/(.*):(.*);/", "$1|=|$2|##|", $PARAMS, -1, $param_count);
			$params_format = preg_replace("/([A-Za-z0-9-_]+):([A-Za-z0-9-_\.\%]+);/", "$1|=|$2|##|", $PARAMS, -1, $param_count);
			
			$params = preg_split("/\|##\|/", $params_format);
			
			for($p = 0; $p < $param_count; $p++)
			{
				$tmp_param = preg_split("/\|=\|/", $params[$p]);
				$return[$tmp_param[0]] = $tmp_param[1];
				$tmp_param = '';
			}
			
			return $return;
			
		}
		else
		{
			return false;
		}
	}
	
	/**
	 * 
	 */
	public function makeParams($PARAMS)
	{
		$params = $PARAMS;
		
		if(! empty($params) ) 
		{
			$params_format = preg_replace("/([A-Za-z0-9-_]+):([A-Za-z0-9-_]+);/", "$1|=|$2|##|", $params, -1, $param_count);
			
			$params = preg_split("/\|##\|/", $params_format);
			
			for($p = 0; $p < $param_count; $p++)
			{
				$tmp_param = preg_split("/\|=\|/", $params[$p]);
				
				//if( $p == 0 ) $return .= ',';
				$return .= ", '".$tmp_param[0]."=".$tmp_param[1]."'";
				//if( $p == $param_count ) $return .= ' ';
				
				$tmp_param = '';
			}
			return $return;
		}
		else
		{
		 	return '';	
		}
	}
 }
?>