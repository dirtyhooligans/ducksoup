<?php
class Controller_Errors extends Controller
{
	public function __construct($CORE) 
	{
		parent::__construct($CORE);
		
		$this->page_title = $this->core->config->site_name . ' | Error';
	}
}
?>