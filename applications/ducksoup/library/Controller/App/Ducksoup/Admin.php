<?php
class Controller_App_Ducksoup_Admin extends Controller_App_Ducksoup
{
	public function __construct($CORE) 
	{
		parent::__construct($CORE);
		
		Auth::verify();
	}
	
	public function apps()
	{
		$this->loadView('Header');
		$this->loadView('Admin_Apps');
		$this->loadView('Footer');
	}

	public function settings()
	{
		$this->loadView('Header');
		echo 'settings';
		$this->loadView('Footer');
	}
}	
?>