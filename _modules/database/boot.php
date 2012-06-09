<?php
/**
* @package SoFramwork
* @copyright 2010 Svenn D'Hert
* @license http://www.gnu.org/licenses/gpl-2.0.txt GNU Public License
*/

// no direct acces
if ( !isset($this) ) { exit('direct_acces_not_allowed'); }

// check if we got a config file
if ( !file_exists($core->path . '_modules/database/config.php') ){ exit('Please fill _main/core/database/config.default.php and rename to config.php'); }

$settings = array(
					'load_hook' 	=> 1,
					'unload_hook' 	=> 9
				);
				
$mode = (isset($mode)) ? $mode : '';
switch ($mode)
{	
	// start the module
	case "construct" :
		
				// include the config 
				include $core->path . '_modules/database/config.php';
				
				// include the class 
				include $core->path . '_modules/database/mysql.php';
								
				# boot class
				$this->db = new mysql($this, $host, $user, $password, $database);
		break;
	
	// end of database connection
	case "destruct" :
				$this->db->close_connection();
		break;
}

?>