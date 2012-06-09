<?php
/**
* @package SoFramwork
* @copyright 2010 Svenn D'Hert
* @license http://www.gnu.org/licenses/gpl-2.0.txt GNU Public License
*/

// no direct acces
if ( !isset($this) ){ exit('direct_acces_not_allowed'); }

$settings = array(
					'load_hook' 	=> 2,
					'unload_hook' 	=> 8
				);
				
$mode = (isset($mode)) ? $mode : '';

switch ($mode)
{
	// start the module
	case "construct" :
		
				// include the class file
				include $module_path . 'sessions.php';
				
				// 'boot' the class
				$this->session = new session($this);
		break;
	
	// end of database connection
	case "destruct" :
				$this->session->end();
		break;
}

?>