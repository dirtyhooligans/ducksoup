<?php 

class Files
{
	
	public function scanDirectory ($directory) 
	{
        // strip closing '/'
        if (substr($directory, -1) == '/') 
        {
                $directory = substr($directory, 0, -1);
        }

        if (!file_exists($directory) || !is_dir($directory) || !is_readable($directory)) 
        {
            return array();
        }

        $dirH = opendir($directory);
        $scanRes = array();

        while(($file = readdir($dirH)) !== FALSE) 
        {

            // skip pointers
            if ( strcmp($file , '.') == 0 || strcmp($file , '..') == 0) 
            {
                continue;
            }

            $path = $directory . '/' . $file;

            if (!is_readable($path)) 
            {
                continue;
            }

            // recursion
            if (is_dir($path)) 
            {
                $scanRes = array_merge($scanRes, self::scanDirectory($path));

            } 
            elseif (is_file($path)) 
            {
            	$scanRes[str_replace(BASE_DIR . '/applications/', '', $directory)][] = $file;
                //$className = explode('.', $file);
                //if ( strcmp($className[1], 'class') == 0 && strcmp($className[2], 'php') == 0 ) 
                //{
                //    $scanRes[$className[0]] = $path; 
                //}
            }
        }

        return $scanRes;
	}
	
	function copyDirectory($SRC, $DEST) 
	{ 
		$src = $SRC;
		$dest = $DEST;
		
	    $dir = opendir($src); 
	    
	    if ( mkdir($dest) )
	    {
	    	while(false !== ( $file = readdir($dir)) ) 
	    	{ 
		        if (( $file != '.' ) && ( $file != '..' ) && ( $file != '.svn' ) && ( $file != '.DS_Store' )) 
		        { 
		            if ( is_dir($src . '/' . $file) ) 
		            { 
		            	$copy_sub_dir = self::copyDirectory($src . '/' . $file, $dest . '/' . $file);
		            	
		                if (! $copy_sub_dir )
		                {
		                	return 'failed to copy '.$copy_sub_dir;
		                }
		            } 
		            else 
		            { 
		                copy($src . '/' . $file, $dest . '/' . $file); 
		            } 
		        } 
		    } 
		    closedir($dir);
		    return true;
	    } 
	    else
	    {
	    	closedir($dir);
	    	return false;
	    }
	     
	} 
	
	public function getDirectoryContentsAsArray($SRC)
	{
		$src = $SRC;
		
		$dir = opendir($src); 
		
		$files_array = array();
			
		while(false !== ( $file = readdir($dir)) ) 
    	{ 
	        if (( $file != '.' ) && ( $file != '..' ) && ( $file != '.svn' ) && ( $file != '.DS_Store' )) 
	        { 
	            if ( is_dir($src . '/' . $file) ) 
	            { 
	            	$sub_dir = self::getDirectoryContentsAsArray($src . '/' . $file);
	            	$files_array = array_merge($files_array, $sub_dir);
	            } 
	            else 
	            { 
	                $files_array[] = $src . '/' . $file; 
	            } 
	        } 
    	}
	    closedir($dir);
	    
	    return $files_array;
	}
	
	public function zipDirectory($SRC, $ZIP_FILE) 
	{ 
		$src = $SRC;
		$zip_file = $ZIP_FILE;
		
	    $dir = opendir($src); 
	  		
		$zip = new ZipArchive();
		$zip->open($zip_file, ZIPARCHIVE::OVERWRITE);
		
		$files_array = self::getDirectoryContentsAsArray($src);
		
		$total_files = count($files_array);
		
		for( $i = 0; $i < $total_files; $i++ )
			$zip->addFromString(str_replace($src, '', $files_array[$i]) , file_get_contents($files_array[$i]));
		
		$zip->close();
	    
	    return $files_array;	     
	} 
	
	public function getExt($FILE)
	{
		return preg_replace('/^.*\.([^.]+)$/D', '$1', $FILE);
	}
	
	public function cleanFilename($FILE)
	{
		return preg_replace("/[^a-zA-Z0-9\-_\.]/", "", $FILE);
	}
	
	public function getFileAsString($FILE)
	{
		$file = $FILE;
		
		if (! is_file($file) )
			return false;
		
		return file_get_contents($file);
	}
	
}

?>