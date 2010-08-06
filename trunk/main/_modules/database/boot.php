<?php
/**
* @package SoFramwork
* @copyright 2010 Svenn D'Hert
* @license http://www.gnu.org/licenses/gpl-2.0.txt GNU Public License
*/

// no direct acces
if ( !isset($this) ){ exit('direct_acces_not_allowed'); }

// check if we got a config file
if ( !file_exist($this->module_path . 'config.php') ){ exit('Please fill in /main/_modules/database/config.php, there is an example file config.default.php.'); }

switch ($mode)
{
	// start the module
	case "construct" :
		
				// include the class file
				if ( file_exist($this->module_path . 'structure/' . $db_type . '.php') )
				{
					include $this->module_path . 'structure/' . $db_type . '.php';
				}
				
				// 'boot' the class
				$this->db = new db($this, $host, $user, $password, $database);
		break;
	
	// end of database connection
	case "destruct" :
				$this->db->close_connection();
		break;
}

?>