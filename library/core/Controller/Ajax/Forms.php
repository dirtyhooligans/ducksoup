<?php
class Controller_Ajax_Forms extends Controller_Ajax
{
	public function __construct($CORE)
	{
		parent::__construct($CORE);
	}
	
	public function login()
	{
		$this->loadView('Forms_Login');
	}
	
	public function forgot()
	{
		$this->loadView('Forms_Forgot');
	}
	
	public function activate()
	{
		$this->loadView('Forms_Activate');
	}
	
	public function register()
	{
		$this->loadView('Forms_Register');
	}
}