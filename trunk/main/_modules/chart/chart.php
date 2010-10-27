<?php
/**
* @package SoFramwork
* @copyright 2010 Svenn D'Hert
* @license http://www.gnu.org/licenses/gpl-2.0.txt GNU Public License
*/

/**
* chart class
* @abstract
*/
final class chart
{
	private 
		$path,
		$img_path,
		$known_types = array(
								'line' 		=> 'line', 
								'balk'		=> 'balk'
							)
		;
	
	function __construct ( $core, $module_path )
	{
		$this->sCore = $core;
		$this->path = $core->path;
		$this->module_path = $module_path;
		$this->img_path = $module_path . 'img/';
	}
	
	function load( $type )
	{
		if ( in_array ( $type, $this->known_types) )
		{
			include $this->module_path . 'structure/' . $this->known_types[$type] . '.php';
		}
		$chart = $this;
		$this->{$type} = new $type($this, $this->img_path);
	}
	
	function reload_all ()
	{
		// to lazy to make this really dynamic
		$allowed_files = array("bar_example", "line_example");
		
		// give a secure core
		$core = $this->sCore;
		
		// settings loaded
		include $this->module_path . 'build_info/setting.php';
		
		if ($handle = opendir($this->module_path . 'build_info/'))
		{
			while (false !== ($file = readdir($handle)))
			{
				if (in_array($file, $allowed_files))
				{	
					// run the scripts
					include $this->module_path . 'build_info/' . $file;
				}
			}
			closedir($handle);
		}
	}
}
?>