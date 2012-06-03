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
		// reference to the core object
		$this->core = $core;
	}
	
	/**
	* register a handeling
	* @param string $msg
	* @param string $file
	*/
	function log($msg, $file = 'admin_log')
	{
		# since we write this to file & screen
		$msg = htmlentities($msg);
		
		# if production variable is set to false
		if ( !$config['production'] )
		{
			echo $msg . '<br/>';
		}
		
		$log_file = $this->path . 'main/_temp/_logs/' . $file . '.log';
			
		if ( is_writable($log_file) )
		{
			// try to open or make it, and set pointer to end of file
			$fp = fopen($log_file, 'a+');
			
			// write to file
			fwrite($fp, date('h:i:s A m - d - y') . ' : ' . $msg . "\r\n");
			
			// close file
			fclose($fp);
		}
		# we got errors we cannot save so check what configs says
		# if no config is set, we go for a die();
		else
		{
			# if it doesn't exist attempt to create (recursion)
			if ( !file_exists ($log_file) )
			{
				$create = fopen($log_file, "w+") or die("We could not write/make a log file, please make main/_temp/_log chmod 777");
					fclose($create);
				$this->log ($msg, $file);
				$this->log ('created log file : '. $file, 'create_log');
			}
			else
			{
				# config found & true so hide modus.
				if ( isset($config['production']) && $config['production'] )
				{
					return true;
				}
				else
				{
					return die('We could not write/make a log file, please make main/_temp/_log chmod 777. The error leading to this issue was : <br/>' . $msg . '<br/> requested file : ' . $file . '<br/>');
				}
			}
		}
	}
}

?>