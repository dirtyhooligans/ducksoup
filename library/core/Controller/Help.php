<?php
class Controller_Help extends Controller 
{
	public function __construct($CORE) 
	{
		parent::__construct($CORE);
	}
	
	public function main()
	{
		$this->loadView('Header');
		$this->loadView('Help_Default');
		$this->loadView('Footer');
	}
	/*
	public function compatible()
	{
		Cookie::set('compatible_notified', mktime());
		
		$this->loadView('Help_Compatible');
	}
	*/
}