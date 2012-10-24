<?php
class Error
{
	public $core;
	
	public function __construct($CORE)
	{
		$this->core = $CORE;
	}
	
	public function db($SOURCE, $MESSAGE, $DESCRIPTION, $RESULTS = false)
	{
		return self::error('DATABASE', $SOURCE, $MESSAGE, $DESCRIPTION, $RESULTS);
	}
	
	public function user($MESSAGE, $DESCRIPTION)
	{
		return self::error('USER', $SOURCE, $MESSAGE, $DESCRIPTION);
	}

	public function standard($SOURCE, $MESSAGE, $DESCRIPTION)
	{
		return self::error('STANDARD', $SOURCE, $MESSAGE, $DESCRIPTION);
	}

	public function auth($SOURCE, $MESSAGE, $DESCRIPTION)
	{
		return self::error('AUTH', $SOURCE, $MESSAGE, $DESCRIPTION);
	}

	public static function error($TYPE, $SOURCE, $MESSAGE, $DESCRIPTION, $DATA = false) 
	{
		$err = array(
			'error' 	=> true, 
			'type' 		=> $TYPE, 
			'source' 	=> strtolower(str_replace('_', '.', $SOURCE)), 
			'code' 		=> $MESSAGE, 'description' => $DESCRIPTION,
			'data' 		=> $DATA
			);

		$_SESSION['ducksoup']['errors'][] = $err;
		return $err;
	}
}