<?php
class Controller_Errors_Page extends Controller_Errors
{
	public function __construct($CORE) 
	{
		parent::__construct($CORE);
		
		$this->page_title = $this->page_title . ' | Page Not Found';
		
		
	}
	
	public function controller_not_found($CONTROLLER, $METHOD, $CONTROLLER_FILE, $APP_CONTROLLER_FILE)
	{
		header("HTTP/1.0 404 Not Found");
		
		$view_arr['notfound'] = 'controller';
		$view_arr['controller'] = $CONTROLLER;
		$view_arr['method'] = $METHOD;
		$view_arr['controller_file'] = $CONTROLLER_FILE;
		$view_arr['app_controller_file'] = $APP_CONTROLLER_FILE;
		
		$this->loadView('Errors_Header');
		$this->loadView('Errors_Page_Notfound', $view_arr);
		$this->loadView('Errors_Footer');
	}
	
	public function method_not_found($CONTROLLER, $METHOD, $CONTROLLER_FILE, $APP_CONTROLLER_FILE)
	{
		header("HTTP/1.0 404 Not Found");
		
		$view_arr['notfound'] = 'method';
		$view_arr['controller'] = $CONTROLLER;
		$view_arr['method'] = $METHOD;
		$view_arr['controller_file'] = $CONTROLLER_FILE;
		$view_arr['app_controller_file'] = $APP_CONTROLLER_FILE;
		
		$this->loadView('Errors_Header');
		$this->loadView('Errors_Page_Notfound', $view_arr);
		$this->loadView('Errors_Footer');
	}
}
?>