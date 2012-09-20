<?php
defined("BASE_DIR") 
	|| define("BASE_DIR", realpath(dirname(__FILE__).'/..'));
	
if ( strpos( $_SERVER['SERVER_PROTOCOL'],'HTTPS' ) )
{
	$scheme = 'https';
}
else
{
	$scheme = 'http';
}
$url = '';
if ( isset( $_REQUEST['url'] ) )
	$url = $_REQUEST['url'];

$alt_app = false;

if (! empty( $default_app_class_dir ) && !empty($APP_BASE_DIR) )
{
	$main_app_base_dir_find = preg_match("/applications\/(\w+)/", $APP_BASE_DIR, $main_app_matches);
	$main_app = strtolower($main_app_matches[1]);
	if ( $app_check = preg_match("/(app|application|applications)\/(\w+)/", $url, $app_matches) )
	{
		if ( $main_app != $app_matches[2] )
		{
			$APP_BASE_DIR = BASE_DIR . '/applications/' . $app_matches[2];
	
			$alt_app = true;
			$app_base_dir = str_replace('applications/'.$main_app, 'applications/'.$app_matches[2], $APP_BASE_DIR);
			define("APP_CLASS_DIR", realpath($app_base_dir . '/library'));
		}
		else
		{	
			$app_base_dir = realpath($APP_BASE_DIR);
			define("APP_CLASS_DIR", realpath($APP_BASE_DIR.'/library'));
		}
		
	}
	else
	{
		$app_base_dir = realpath($APP_BASE_DIR);
		define("APP_CLASS_DIR", realpath($APP_BASE_DIR.'/library'));
	}
}
else
{
	$app_base_dir = realpath($APP_BASE_DIR);
		define("APP_CLASS_DIR", realpath($APP_BASE_DIR.'/library'));
}

defined("ALT_APP") 
			|| define("ALT_APP", $alt_app);
			
defined("APP_BASE_DIR") || define("APP_BASE_DIR", $APP_BASE_DIR);

if ($match_resource_ext = preg_match("/\.(txt|css|js|html|htm|jpg|gif|swf|png|otf|ttf|mp4|m4v|mov|avi|mp3|wav|dmg|exe|zip)$/", $url, $ext) )
{
	$resource_file = str_replace($scheme . '://' . $_SERVER['HTTP_HOST'], '', $url);
	$resource_file =  str_replace('app/', 'applications/', $resource_file);
	$resource_file =  BASE_DIR . '/'.  preg_replace("/applications\/(\w+)/", 'applications/$1/public', $resource_file);
	$base_resource_file = preg_replace("/applications\/(\w+)/", '', $resource_file);
	
	//die($resource_file . " <br />\n" . $base_resource_file);
	
	if (! is_file($base_resource_file) )
		$base_resource_file = str_replace(BASE_DIR, BASE_DIR . '/public', $base_resource_file);
	
	$base_resource_file = str_replace('public//public', 'public', $base_resource_file);
	
	$resource_file = str_replace('public/public', 'public', $resource_file);
	$base_resource_file = str_replace('public/public', 'public', $base_resource_file);
	
	//die($resource_file . " <br />\n" . $base_resource_file);
	
	switch ($ext[1])
	{
		
		case "txt": $ctype="text/plain"; 
			break;
		case "js" :
			$ctype="text/javascript";
			break;
		case "html": $ctype="text/html"; 
		case "htm": $ctype="text/html"; 
			break;
		case "css": $ctype="text/css"; 
			break;
		case "pdf": $ctype="application/pdf"; break;
		case "exe": $ctype="application/octet-stream"; break;
		case "zip": $ctype="application/zip"; break;
		case "doc": $ctype="application/msword"; break;
		case "xls": $ctype="application/vnd.ms-excel"; break;
		case "ppt": $ctype="application/vnd.ms-powerpoint"; break;
		case "gif": $ctype="image/gif"; break;
		case "png": $ctype="image/png"; break;
		case "jpeg":
		case "jpg": $ctype="image/jpg"; break;
		case "swf": $ctype="application/x-shockwave-flash"; break;
		case "ttf":
		case "otf": $ctype="application/x-font-opentype"; break;
		
		case "m4v": $ctype="video/x-m4v"; break;	
		case "avi": $ctype="video/avi"; break;	
		case "mov": $ctype="video/quicktime"; break;	
			
			
		default: $ctype="application/force-download"; 
	}

	header("Date: ".gmdate("D, j M Y H:i:s e", time()));
	header("Cache-Control: max-age=2592000");
	header("Last-Modified: ".gmdate("D, j M Y H:i:s e", $info['mtime']));
	header("ETag: ".sprintf("\"%x-%x-%x\"", $info['ino'], $info['size'], $info['mtime']));
	header("Accept-Ranges: bytes");
	header("Expires: ".gmdate("D, j M Y H:i:s e", $info['mtime']+2592000));
	header("Content-Type: text/plain"); // note: this was text/html for some reason?

	ob_start();
	//ob_start("ob_gzhandler");
	//echo file_get_contents($file);
	
	
	if ( is_file( $resource_file ) )
	{
		//die($ctype . ' ' . $resource_file);
		header('Content-type: ' . $ctype );
		//readfile($resource_file);
		ob_start("ob_gzhandler");
		echo file_get_contents($resource_file);

		ob_end_flush();
		header('Content-Length: '.ob_get_length());
		ob_end_flush();

		die();
	}
	elseif ( is_file($base_resource_file) ) 
	{
		header('Content-type: ' . $ctype );
		//readfile($base_resource_file);
		ob_start("ob_gzhandler");
		echo file_get_contents($base_resource_file);
		
		ob_end_flush();
		header('Content-Length: '.ob_get_length());
		ob_end_flush();
		die();
	}
	else
	{
		header('Content-type: text/plain' );
	}

}
else
{
	
}

require_once( BASE_DIR . '/library/init.php' );

if ( defined("APP_BASE_DIR") )
{	
//echo $app_base_dir . '<br>';
	defined("APP_BASE_URL") 
		|| define("APP_BASE_URL", $scheme . '://' . $_SERVER['HTTP_HOST'] . BASE_URL_PATH . str_replace(BASE_DIR, '', str_replace('applications', 'app', $app_base_dir)));
		
		//echo '1:'.str_replace('applications', 'app', $app_base_dir) . '<br>2:' . APP_BASE_URL . '<br>3:';
}

//echo '1:' .$app_base_dir . '<br>4:' . BASE_URL_PATH . '<br>5:' . APP_BASE_DIR . '<br>6:' . BASE_DIR;

$core = new Core(
				 BASE_DIR, 
				 parse_url($active_url), 
				 $_POST
				);
$core->run();
?>