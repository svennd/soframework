<?php
/**
* @package SoFramwork
* @copyright 2010 Svenn D'Hert
* @license http://www.gnu.org/licenses/gpl-2.0.txt GNU Public License
*/

// no direct acces
if ( !isset($this) ){ exit('direct_acces_not_allowed'); }
print_r(get_defined_vars());
// check if we got a config file
if ( !file_exists($module_path . 'config.php') ){ exit('Please fill _main/core/database/config.default.php and rename to config.php'); }

switch ($mode)
{
	case "core" :
				$load_level = 1;
		break;
		
	// start the module
	case "construct" :
		
				// include the config 
				include $module_path . 'config.php';
				
				// include the class file
				if ( file_exists($module_path . 'structure/' . $db_type . '.php') )
				{
					include $module_path . 'structure/' . $db_type . '.php';
				}
				
				// 'boot' the class
				// calling class like this requires php 5.2 alternative you could rename all database structure classes to
				// db, and set here new db (...);
				$this->db = new $db_type($this, $host, $user, $password, $database);
		break;
	
	// end of database connection
	case "destruct" :
				$this->db->close_connection();
		break;
}

?>