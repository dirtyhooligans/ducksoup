<?php
class Application
{
	public $base_dir;
	public $base_url;
	
	public function __construct()
	{
		$doc_root_check = preg_match("/applications\/(.*)\/public/", $_SERVER['DOCUMENT_ROOT'], $doc_root_match);
		$this->name = $doc_root_match[1];
		
	}
	
	public function setName($NAME)
	{
		$this->name = $NAME;
		$this->base_dir = BASE_DIR . '/applications/'.$this->name;
		$this->base_url = BASE_URL . '/app/'.$this->name;
	}
	
	
}
?>