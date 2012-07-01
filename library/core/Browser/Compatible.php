<?php
class Compatible
{
	public $acceptable;
	
	public $rejected;
	
	public function __construct($CORE)
	{
		$this->core = $CORE;
		
		$this->acceptable = array();
		
		$this->acceptable[0]['platform'] = 'Apple';
		$this->acceptable[0]['browser']  = 'Firefox';
		$this->acceptable[0]['min_ver']  = '3.5.0';
		
		$this->acceptable[0]['platform'] = 'Apple';
		$this->acceptable[0]['browser']  = 'Safari';
		$this->acceptable[0]['min_ver']  = '4.0';
		
		$this->acceptable[0]['platform'] = 'Apple';
		$this->acceptable[0]['browser']  = 'Chrome';
		$this->acceptable[0]['min_ver']  = '5.0';
		
		$this->acceptable[0]['platform'] = 'Windows';
		$this->acceptable[0]['browser']  = 'Internet Explorer';
		$this->acceptable[0]['max_ver']  = '8.0';
		
		
		$this->rejected = array();
		
		$this->rejected[0]['platform'] = 'Windows';
		$this->rejected[0]['browser']  = 'Internet Explorer';
		$this->rejected[0]['max_ver']  = '6.0';
		
	}
	
	public function isCompliant()
	{
		$browser = new Browser();
		
		if( $browser->getBrowser() == Browser::BROWSER_FIREFOX && $browser->getVersion() >= 3 ) 
		{
			return 'passed';
		}
		
		if( $browser->getBrowser() == Browser::BROWSER_SAFARI && $browser->getVersion() >= 4 ) 
		{
			return 'passed';
		}
		
		if( $browser->getBrowser() == Browser::BROWSER_CHROME && $browser->getVersion() >= 5 ) 
		{
			return 'passed';
		}
		
		if( $browser->getBrowser() == Browser::BROWSER_IE && $browser->getVersion() >= 7 ) 
		{
			return 'maybe';
		}
		
		/*
		 * unsupported browsers/platform (will reject any user with the below browser/platform)
		 */
		if( $browser->getBrowser() == Browser::BROWSER_IE && $browser->getVersion() <= 6 ) 
		{
			return 'fail';
		}
		
		return 'uncertain';
	}
	
	public function wasNotified()
	{
		
		if ( Cookie::exists('compatible_notified') )
		{
			$last_notified =  Cookie::get('compatible_notified');
			
			// see if the user has been notified in the past week (604800 seconds)
			if ( ( mktime() - $last_notified ) <=  604800)
			{
				return 'true';
			}
		}
		
		return 'false';
	
	}

}