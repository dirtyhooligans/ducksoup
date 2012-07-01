<?php
class Version
{
	public function getVersion()
	{
		$return .= '0.1.0';
		
		$rev = '@REV@';
		
		if ( $rev != '@REV@' && !empty($rev) )
		{
			$return .= '.'.$rev;
		}
		else
		{
			$return .= '  r('.self::getRevision().')';
		}
		return $return;
	}
	
	public function getRevision()
	{
		$svn = @file(BASE_DIR.'/.svn/entries');
		$rev = (int) $svn[3];

		return $rev;
	}
	
	public function getAppVersion()
	{
		$return .= '0.1.0';
		
		$rev = '@REV@';
		
		if ( $rev != '@REV@' && !empty($rev) )
		{
			$return .= '.'.$rev;
		}
		else
		{
			$return .= '.'.self::getAppRevision();
		}
		return $return;
	}
	
	public function getAppRevision()
	{
		$svn = @file(APP_BASE_DIR.'/.svn/entries');
		$rev = (int) $svn[3];

		return $rev;
	}
}
?>