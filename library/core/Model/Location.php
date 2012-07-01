<?php
class Model_Location extends Model
{
	public function __construct($CORE)
	{
		parent::__construct($CORE);
	}
	
	public function getLocationDataByZip($ZIP)
	{
		$zip = (int) $ZIP;
		
		$this->db = new Db_Mysqli($this->core, 'ducksoup');
		
		$query = sprintf("
						SELECT
							z.`zip`,
							z.`city`,
							z.`state`,
							z.`latitude`,
							z.`longitude`,
							z.`timezone`,
							z.`dst`,
							z.`country`
						FROM
							%s.zipcodes z
						WHERE
							z.zip = %d",
						$this->db->name,
						$zip
		);
		
		$results = $this->db->Select(array("query" => $query));
       
        if ($results['valid'])
        {
            if (! empty($results['records'][0]['zip']) )
			{
				return $results['records'][0];
			}
			else
			{
				return array('error' => true, 'message' => 'No Data Found.');
			}		
		}
		else
		{
			return $results['error'];
		}
	}
}
?>