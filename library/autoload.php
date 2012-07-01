<?php
define("CLASS_DIR", dirname(__FILE__) . '/core');

function __autoload($className) 
{
	$class_name = str_replace('_', '/', $className);
	
	
	
	if ( defined( "APP_CLASS_DIR" ) && preg_match("/^(Model_App|Controller_App)_([^_]+)/", $className) )
	{	
		//die($className);
		$app_class_dir = APP_CLASS_DIR;
		
		if ( preg_match("/applications\/(.*)\/library/", $app_class_dir, $main_app_matches) ) 
		{
			
			//Model_App_Mobileab 
			//Model_App_Mobileab_Settings 
			
			$main_class_app = $main_app_matches[1];
			if ( preg_match("/Model_App_([^_]+)/", $className, $alt_app_matches) ) 
			{
				if (! empty($alt_app_matches[1]) )
				{
					if ( $main_class_app != strtolower($alt_app_matches[1]) )
					{
						$app_class_dir = BASE_DIR . str_replace('applications/'.$main_class_app.'/library','applications/'.strtolower($alt_app_matches[1]).'/library', str_replace(BASE_DIR,'',$app_class_dir));
					//	$_SESSION['autoload3'][] = $app_class_dir;
					}
				}
				
			}
		}
			
		$app_folder = appClassFolder($class_name, $app_class_dir);
		
		if($app_folder)
	    {
	        require_once($app_folder.$class_name.".php");	
		}
		else
		{
			$folder = classFolder($class_name);

		    if($folder)
		    {
		        require_once($folder.$class_name.".php");	
			}
			else
			{
				echo "Application Class '".$class_name."' was NOT FOUND at: ".$app_folder.$class_name . " <br> ---- <br> ";
				echo "Class '".$class_name."' was NOT FOUND at: ".$folder.$class_name;
			}
		}
	}
	else
	{
		$folder = classFolder($class_name);
	
	    if($folder)
	    {
	        require_once($folder.$class_name.".php");	
		}
		else
		{
			$ext_folder = searchAppsDirectories($class_name);
			
			if ( $ext_folder )
			{
				require_once($ext_folder.$class_name.".php");	
			}
			else
			{
				echo "Application Class '".$class_name."' was NOT FOUND at: ".$ext_folder.$class_name . " <br> ---- <br> ";
				echo "Class '".$class_name."' was NOT FOUND at: ".$folder.$class_name;
			}
		}
	}
	
}

function classFolder($className, $sub = "/") 
{

    $dir = dir(CLASS_DIR.$sub);
    
    if(file_exists(CLASS_DIR.$sub.$className.".php"))
        return CLASS_DIR.$sub;

    while(false !== ($folder = $dir->read())) {
        if($folder != "." && $folder != "..") {
            if(is_dir(CLASS_DIR.$sub.$folder)) {
                $subFolder = classFolder($className, $sub.$folder."/");
               
                if($subFolder)
                    return $subFolder;
            }
        }
    }
    $dir->close();
    
    return false;
}

function appClassFolder($className, $class_dir, $sub = "/") 
{
    $dir = dir($class_dir.$sub);
    
    if(file_exists($class_dir.$sub.$className.".php"))
        return $class_dir.$sub;

    while(false !== ($folder = $dir->read())) {
        if($folder != "." && $folder != "..") {
            if(is_dir($class_dir.$sub.$folder)) {
                $subFolder = classFolder($className, $sub.$folder."/");
               
                if($subFolder)
                    return $subFolder;
            }
        }
    }
    $dir->close();
    
    return false;
}

function searchAppsDirectories($className, $sub = "/") 
{
	$apps_base_dir = BASE_DIR . "/applications";
	
	$apps_dir = dir($apps_base_dir.$sub);
	
	while(false !== ($app_dir = $apps_dir->read())) 
	{
		if($app_dir != "." && $app_dir != "..") 
		{
			$app_lib_dir = $apps_base_dir.$sub.$app_dir.$sub.'library';
			
			if ( is_dir($app_lib_dir) )
			{
				if ( is_file( $app_lib_dir . $sub . $className . '.php') )
				{
					return $app_lib_dir.$sub;
				}
			}
		}
	}
	
	$apps_dir->close();
	    
    return false;
}

function scanDirectory ($directory) 
{
        // strip closing '/'
        if (substr($directory, -1) == '/') 
        {
                $directory = substr($directory, 0, -1);
        }

        if (!file_exists($directory) || !is_dir($directory) || !is_readable($directory)) 
        {
            return array();
        }

        $dirH = opendir($directory);
        $scanRes = array();

        while(($file = readdir($dirH)) !== FALSE) 
        {

            // skip pointers
            if ( strcmp($file , '.') == 0 || strcmp($file , '..') == 0) 
            {
                continue;
            }

            $path = $directory . '/' . $file;

            if (!is_readable($path)) 
            {
                continue;
            }

            // recursion
            if (is_dir($path)) 
            {
                $scanRes = array_merge($scanRes, $this->scanDirectory($path));

            } 
            elseif (is_file($path)) 
            {
                $className = explode('.', $file);
                if ( strcmp($className[1], 'class') == 0 && strcmp($className[2], 'php') == 0 ) 
                {
                    $scanRes[$className[0]] = $path; 
                }
            }
        }

        return $scanRes;
}



?>