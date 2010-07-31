<?php
/**
* @package SoFramwork
* @copyright 2010 Svenn D'Hert
* @license http://www.gnu.org/licenses/gpl-2.0.txt GNU Public License
*/

/**
* log class
* @abstract
*/
final class log
{
	public 
		$core
		;
	
	/**
	* initialize
	* @param object $core
	*/
	function __construct($core)
	{
		$this->core = $core;
	}
	
	/**
	* register a user/admin handeling
	* @param string $msg
	* @param string $file
	*/
	function save($msg , $file = 'admin_log')
	{
		// try to open or make it, and set pointer to end of file
		$fp = fopen($this->core->path . 'main/_model/_logs/' . $file . '.log', 'a+');
		
		// write to file
		fwrite($fp, time() . '&nbsp;&rsaquo;&nbsp;' . (string) $msg . "\r\n");
		
		// close file
		fclose($fp);
	}
	
	/**
	* show registered msg's
	* @param string $file
	* @return array or bool
	*/
	function show_log ($file = 'admin_log')
	{
		if ( file_exist($this->core->path . 'main/_model/_logs' . $file . '.log') )
		{
			$fp = fopen ( $this->core->path . 'main/_model/_logs' . $file . '.log', 'rb' );
			$contents = fread($fp, filesize($this->core->path . 'main/_model/_logs' . $file . '.log'));
			fclose($fp);
			return $contents;
		}
		return false;
	}
}

?>