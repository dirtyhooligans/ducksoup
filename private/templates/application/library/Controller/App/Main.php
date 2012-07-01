<?php
class Controller_App_Main extends Controller_App
{
	public function __construct($CORE) 
	{
		parent::__construct($CORE);
		
		$this->app = array('app' => 'main');
		
		$this->page_title =  $this->core->config->site_name . " | Home" ;
		
		$this->header_title = $this->core->config->site_name;
		
		$this->header_includes['css'][] = 'main.css';
	}	
}

?>