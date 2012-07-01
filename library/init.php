<?php
include( BASE_DIR . '/library/autoload.php' );

$active_url = $scheme.'://'.$_SERVER['HTTP_HOST'].$_SERVER["REQUEST_URI"];

$doc_root = $_SERVER['DOCUMENT_ROOT'];
$app_root = realpath(dirname(dirname(__FILE__)));



if ( ALT_APP )
{
	$app_root = str_replace('applications/'.$main_app, 'applications/'.$app_matches[2], $app_root);
	$doc_root = str_replace('applications/'.$main_app.'/public', '', $app_root);
}
else
{
	if ( count($app_root) >= count($doc_root) )
		$app_root = $doc_root;
}

defined("BASE_URL_PATH")
	|| define("BASE_URL_PATH", str_replace($doc_root, '', $app_root ));

defined("BASE_URL")
	|| define("BASE_URL", $scheme.'://'.$_SERVER['HTTP_HOST'].BASE_URL_PATH);

$_SESSION['ducksoup']['errors'] = array();

date_default_timezone_set('America/Los_Angeles');

ini_set('error_reporting', E_ALL & ~E_NOTICE);
ini_set('display_errors', 'On');
?>