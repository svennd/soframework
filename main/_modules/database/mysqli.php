<?php
/**
* @package SoFramwork
* @copyright 2010 Svenn D'Hert
* @license http://www.gnu.org/licenses/gpl-2.0.txt GNU Public License
*/

/**
* view class
* @abstract
*/
final class mysqli
{
	public 
		$core,
		$db_ready = false
		;
	
	private
		$mysqli
		;
	/**
	* initialize
	* @param object $core
	*/
	function __construct($core)
	{
		# reference to the core object
		$this->core = $core;
		
		# if only one database is used
		if ( is_array($this->core->_database) )
		{
			$db = $this->core->_database;
			$this->mysqli = new mysqli($db['db_host'], $db['db_u_name'], $db['db_u_pass'], $db['db_name']);
			if ( $this->mysqli->connect_error )
			{
			  die('Connect Error (' . $mysqli->connect_errno . ') '. $mysqli->connect_error);
			}
			$this->db_ready = true;
		}
		else
		{
			foreach ($this->core->_database as $connection)
			{
				$this->mysqli = new mysqli($connection['db_host'], $connection['db_u_name'], $connection['db_u_pass'], $connection['db_name']);
				if ( $this->mysqli->connect_error )
				{
				  die('Connect Error (' . $mysqli->connect_errno . ') '. $mysqli->connect_error);
				}
			}
			$this->db_ready = true;
		}

		# 'security', this info in not needed anymore.
		unset($this->core->_database);
		
	}
		
}

?>