<?php
class Controller_App_Ducksoup extends Controller_App
{
	public function __construct($CORE) 
	{
		parent::__construct($CORE);
		
		$this->app = array('app' => 'ducksoup');
		
		$this->page_title =  $this->core->config->site_name . " | Home" ;
		
		$this->header_title = $this->core->config->site_name;
		
		$this->header_includes['css'][] = 'main.css';
	}

	public function main()
	{
		$this->page_title = $this->core->config->site_name . " | Home" ;
		
		$this->header_title = "Welcome";
		
		$this->header_includes['css'][] = 'main.css';
		
		$this->loadView('Header');
		$this->loadView('Default');
		$this->loadView('Footer');
	}
}

?>