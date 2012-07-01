<?php
class Controller_Admin extends Controller 
{
	public function __construct($CORE) 
	{
		parent::__construct($CORE);
		
	}
	
	public function apps()
	{
		$this->loadView('Header');
		$this->loadView('Admin_Apps');
		$this->loadView('Footer');
	}
}
?>