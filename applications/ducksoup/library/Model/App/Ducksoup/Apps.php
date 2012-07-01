<?php
class Model_App_Ducksoup_Apps extends Model_App_Ducksoup
{
  public function __construct($CORE)
  {
    parent::__construct($CORE);
  
  }
  
  public function getApps()
  {
    $dir = realpath(BASE_DIR);
    $dir = $dir.'/applications';
    if (is_dir($dir))
    {
      if ($dh = opendir($dir)) {
        while (($file = readdir($dh)) !== false) 
        {
          if ( $file != '.' && $file != '..' && $file != '.svn' && $file != '.DS_Store')
          {
            echo "<strong>" . ucwords($file) . "</strong>"; if ( $file == $this->core->main_app ) { echo ' [ MAIN APP ]';} echo "<br>\n";
            echo "&nbsp; " . $dir . '/' . $file . "<br>\n";
          }
            
        }
        closedir($dh);
      }
    }
    
  }
  
}
?>