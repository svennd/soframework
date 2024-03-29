<?php
/**
* @package SoFramwork
* @copyright 2010 Svenn D'Hert
* @license http://www.gnu.org/licenses/gpl-2.0.txt GNU Public License
*/

/**
* filehandler class
* @abstract
*/
final class filehandler
{
	public
			$method = 'dir',
			$dir	= 'code/'
			;
						
	/**
	* initialize
	* @param object $core
	*/
	function __construct($core)
	{
		# ref
		$this->core = $core;
	}
	
	public function read_directory($subdir = '')
	{
		# check existance
		if (!is_dir($this->core->path . $this->dir . $subdir))
		{
			die('not a valid directory has been requested');
		}
		
		# attempt to open for read
		if ( $handle = opendir($this->core->path . $this->dir . $subdir))
		{
			
		} 
		else 
		{
			die('Can\'t open the directory -permissions wrong-');
		}
	}
	
	public function create_new_file() 
	{
	}
	
	public function delete_file()
	{
	}
	
	public function edit_file()
	{
	}
	
	/** 
	* destructor
	*
	*/
	public function end()
	{
	}
}

?>