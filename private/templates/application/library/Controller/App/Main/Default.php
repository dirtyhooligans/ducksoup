<?php
class Controller_App_Main_Default extends Controller_App_Main 
{
	public function __construct($CORE) 
	{
		parent::__construct($CORE);
	}
	
	public function main() 
	{
		/*
		if(! Auth::verify() )
		{
			header("Location: " . BASE_URL . "/user/login");
			exit();
		}
		*/
		
		if ( $this->compatible == 'fail' ) 
		{
			header("Location: " . BASE_URL . "/help/compatible?decision=".$this->compatible);
			exit();
		}
		elseif( $this->compatible == 'uncertain' ||  $this->compatible == 'maybe' )
		{
			if ( Compatible::wasNotified() == 'false' )
			{
				header("Location: " . BASE_URL . "/help/compatible?decision=".$this->compatible);
				exit();
			}
		}
				
		$this->page_title = $this->core->config->site_name . " | Home" ;
		
		$this->header_title = "Welcome";
		
		$this->loadView('Header');
		$this->loadView('Default');
		$this->loadView('Footer');
	}
	
}

?>