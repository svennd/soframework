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
		$lifetime = 60 * 60 * 24 # 1 day
		;
		
	/**
	* initialize
	* @param object $core
	*/
	function __construct($core)
	{
		// reference to the core object
		$this->core = $core;
	}
	
	/**
	* save array to file
	* @param string $name
	* @param array $content
	*/
	public function save ($name, $content)
	{
		# open / make file
		$file = fopen($this->core->path . 'main/_model/_cache/' . $name . '.cache', "w");
		
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
		if (file_exists($this->core->path . 'main/_model/_cache/' . $name . '.cache') && filemtime($this->core->path . 'frame/_cache/' . $name . '.cache') > (time() - 60 * 60 * 24)) 
		{
			# we found it :)
			return unserialize(file_get_contents($path . 'frame/_cache/' . $name . '.cache'));
		}
		return false;
	}
	
	/**
	* clean the cache
	* @param string $name
	* @return string
	*/	
	public function clean ()
	{
		while( false !== ($filename = readdir($this->core->path . 'main/_model/_cache/')) )
		{
			if( $filename != "." && $filename != ".." )
			{
				$filename = $directory. "/". $filename;

				if( @filemtime($filename) < (time() - $this->lifetime) )
				{
					@unlink($filename);
				}
			}
		}
	}
}
?>