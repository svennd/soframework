<?php
/**
* @package SoFramwork
* @copyright 2010 Svenn D'Hert
* @license http://www.gnu.org/licenses/gpl-2.0.txt GNU Public License
*/

/**
* cache class
* @abstract
*/
final class cache
{		
	public
		$lifetime = 86400
		;
		
	/**
	* initialize
	* @param object $core
	*/
	function __construct($core)
	{
		# reference to the core object
		$this->core = $core;
		
		# clean up old files
		$this->clean();
	}
	
	/**
	* save array to file
	* @param string $name
	* @param array $content
	* @return bool
	*/
	public function save ($name, $content)
	{		
		# check if a same filename doesn't exist yet
		if (  is_file($this->core->path . 'main/_temp/_cache/' . $name . '.cache') )
		{
			# write to error_log
			$this->core->log('dubbele cache naam : ' . $name , 'error_log');
			
			# stop save here
			return false;
		}
		
		# open / make file
		$file = fopen($this->core->path . 'main/_temp/_cache/' . $name . '.cache', "w");
		
		# write to file, serialize is slow and cpu heavy process.
		fputs($file, serialize($content));
		
		# close file
		fclose($file); 
	}
	
	/**
	* get the file
	* @param string $name
	* @return string
	*/
	public function get ($name) 
	{
		# check if file exists and check last edit time
		if (file_exists($this->core->path . 'main/_temp/_cache/' . $name . '.cache') && filemtime($this->core->path . 'main/_temp/_cache/' . $name . '.cache') > (time() - 60 * 60 * 24)) 
		{
			# we found it :)
			return unserialize(file_get_contents($this->core->path . 'main/_temp/_cache/' . $name . '.cache'));
		}
		return false;
	}
	
	/**
	* clean the cache
	* @param string $name
	*/	
	public function clean ()
	{
		$handle = opendir($this->core->path . 'main/_temp/_cache');
		while( false !== ($filename = readdir($handle)) )
		{
			if( $filename != "." && $filename != ".." )
			{
				$filename = $this->core->path . "main/_temp/_cache/". $filename;

				if ( is_file($filename) && filemtime($filename) < (time() - $this->lifetime)  )
				{
					unlink($filename);
				}
			}
		}
	}
}
?>