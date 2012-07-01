<?php
/* 
defined("APP_BASE_DIR") 
	|| define("APP_BASE_DIR", realpath(dirname(__FILE__).'/..'));
*/
if ( empty($APP_BASE_DIR) )
	$APP_BASE_DIR = realpath(dirname(__FILE__).'/..');
$default_app_class_dir = $APP_BASE_DIR.'/library';
	
require_once( realpath(dirname(__FILE__) . '/../../../public/index.php') );
?>