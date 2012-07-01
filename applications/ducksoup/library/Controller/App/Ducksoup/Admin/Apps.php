<?php
class Controller_App_Ducksoup_Admin_Apps extends Controller_App_Ducksoup_Admin
{
	public function __construct($CORE) 
	{
		parent::__construct($CORE);


		$this->page_title =  $this->core->config->site_name . " | Admin | Apps" ;
		
		$this->header_title = $this->core->config->site_name;
		
		$this->header_includes['css'][] = 'main.css';

	}

	public function create()
	{
		$this->page_title .= " | Create";

		$this->loadView('Header');
		echo 'create app';
		$this->loadView('Footer');
	}
	
}	
?>