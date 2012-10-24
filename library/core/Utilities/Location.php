<?php 

class Utilities_Location
{
	public $core;
	
	public function __construct($CORE)
	{
		$this->core = $CORE;
	}

	public function lookupZipcode($ZIP) {

		$isZip = Model_Location::isZip($ZIP);

		if ( is_array($isZip) )
		{
			return $isZip;
		}
		else
		{
			$zip_url = 'http://www.zipareacode.net/zip-code-'.$ZIP.'.htm';
			$zip_contents = file_get_contents($zip_url);

			if ( strpos('<h1>404 Error!</h1><p>File not found!</p>', $zip_contents ) != -1 )
			{

				$zip_data = preg_match("/<div id=\"content\">(.*?)<div id=\"panelright\">/s", $zip_contents, $zip_src);

				$zip_data = preg_match("/<div id=\"panelleft\">(.*)/", $zip_src[1], $zip_src);

				$zip_data = preg_match("/<table[^<]+?>(.*)<\/table>/", $zip_src[1], $zip_src);

				$zip_src = preg_replace("/<tr[^<]+?>/", "", $zip_src[1]);

				$zip_src = preg_replace("/<tr>/", "", $zip_src);

				$zip_src = preg_replace("/<\/tr>/", "--|--", $zip_src);

				$zip_src = preg_replace("/<td[^<]+?>/", "", $zip_src);

				$zip_src = preg_replace("/<td>/", "", $zip_src);

				$zip_src = preg_replace("/<\/td>/", "$$$", $zip_src);

				$zip_parts = explode('--|--', $zip_src);

				$fields['zip'] = (int) $ZIP;

				foreach($zip_parts as $zip_part) 
				{
					$zip_part_data = explode('$$$', $zip_part);
				
					$total_values = count($zip_part_data);

					if (! empty($zip_part_data[1]) ) 
					{
						$key = str_replace(':', '', trim($zip_part_data[0]));
						$key = str_replace('  ', ' ', strtolower($key));
						$key = str_replace(' ', '_', strtolower($key));

						$value = $zip_part_data[1];
						if ( strpos($value, '<a href') != -1 ) {
							$value = preg_replace("/<a[^<]+?>(.*)<\/a>/", "$1", $value);
						}

						if ( $key != 'area_code' )
							$value = str_replace(',', '', $value);
						
						$value = str_replace('$', '', $value);
						$value = str_replace('sq mi', '', $value);
						
						
						$fields[$key] = trim($value);
					}				
				}

				if (! empty($fields['time_zone']) )
				{
					$tz = explode('GMT', $fields['time_zone']);
					$fields['time_zone_offset'] = trim($tz[1]) . ':00';
				}
					
				//return $fields;
				return Model_Location::saveLocation($fields);
			}
			else
			{
				return false;
			}
		}

	}
}
?>