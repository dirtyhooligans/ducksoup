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

	public function isZip($ZIP) 
	{
		$zip = trim($ZIP);

		if (! $this->db )
            $this->db = new Db_Mysqli($this->core, 'connect');

		$query = sprintf("
                    SELECT 
                        l.*
                    FROM 
                        %s.`location` l
                    WHERE 
                        l.`zip` = '%s'   
                    "    
                    , 
                    $this->db->config->name,
                    $zip
                    );
        
        $results = $this->db->Select(array("query" => $query, "db" => "connect"));
       
        if ($results['valid'])
        {
            return $results['records'][0];
        }
        else
        {
            // db error
            return false;
        }
	}

	public function isAreacode($AREACODE) 
	{
		$ac = trim($AREACODE);

		if (! $this->db )
            $this->db = new Db_Mysqli($this->core, 'connect');

		$query = sprintf("
                    SELECT 
                        l.*
                    FROM 
                        %s.`location` l
                    WHERE 
                        l.`area_code` = '%s'   
                    "    
                    , 
                    $this->db->config->name,
                    $ac
                    );
        
        $results = $this->db->Select(array("query" => $query, "db" => "connect"));
       
        if ($results['valid'])
        {
            return $results['records'][0];
        }
        else
        {
            // db error
            return false;
        }
	}

	public function saveLocation($DATA) 
	{
		if (! $this->db )
            $this->db = new Db_Mysqli($this->core, 'ducksoup');

        $data = $DATA;

		$query = sprintf("
                    INSERT INTO 
                        %s.`location`
                    SET 
                        `zip`                       = %d,
                        `city`                      = '%s',
                        `state`                     = '%s',
                        `county`                    = '%s',
                        `area_code`                 = %d,
                        `latitude`                  = '%s',
                        `longitude`                 = '%s',
                        `time_zone`                 = '%s',
                        `time_zone_offset`          = '%s',
                        `population`                = %d,
                        `population_2000`           = %d,
                        `white_population`          = %d,
                        `black_population`          = %d,
                        `hispanic_population`       = %d,
                        `asian_population`          = %d,
                        `hawaiian_population`       = %d,
                        `indian_population`         = %d,
                        `other_population`          = %d,
                        `male_population`           = %d,
                        `female_population`         = %d,
                        `median_age`                = '%s',
                        `male_median_age`           = '%s',
                        `female_median_age`         = '%s',
                        `households`                = %d,
                        `average_household_size`    = '%s',
                        `average_house_value`       = %d,
                        `average_household_income`  = %d,
                        `number_of_businesses`      = %d,
                        `number_of_employees`       = %d,
                        `annual_payroll`            = %d,
                        `land_area_sq_miles`        = '%s',
                        `water_area_sq_miles`       = '%s'
                    "    
                    , 
                    $this->db->config->name,
                    $data['zip'],
                    $data['city'],
                    $data['state'],
                    $data['county'],
                    $data['area_code'],
                    $data['latitude'],
                    $data['longitude'],
                    $data['time_zone'],
                    $data['time_zone_offset'],
                    $data['population'],
                    $data['2000_population'],
                    $data['white_population'],
                    $data['black_population'],
                    $data['hispanic_population'],
                    $data['asian_population'],
                    $data['hawaiian_population'],
                    $data['indian_population'],
                    $data['other_population'],
                    $data['male_population'],
                    $data['female_population'],
                    $data['median_age'],
                    $data['male_median_age'],
                    $data['female_median_age'],
                    $data['households'],
                    $data['average_household_size'],
                    $data['average_house_value'],
                    $data['average_household_income'],
                    $data['number_of_businesses'],
                    $data['number_of_employees'],
                    $data['annual_payroll'],
                    $data['land_area'],
                    $data['water_area']                   
                    );
        
        $results = $this->db->Insert(array("query" => $query, "db" => "connect"));
       
        if ($results['valid'])
        {
            return self::isZip($data['zip']);
        }
        else
        {
            // db error
            return Error::db(__METHOD__, 'DATABASE', 'error interacting with the database', $results);
        }
	}
}
?>