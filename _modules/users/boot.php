<?php
/**
* @package SoFramwork
* @copyright 2010 Svenn D'Hert
* @license http://www.gnu.org/licenses/gpl-2.0.txt GNU Public License
*/

// no direct acces
if ( !isset($this) ){ exit('direct_acces_not_allowed'); }

$settings = array(
					'load_hook' 	=> 3,
				);
				
$mode = (isset($mode)) ? $mode : '';
switch ($mode)
{
	// start the module
	case "construct" :
		
				// include the class file
				include $module_path . 'users.php';
				
				// 'boot' the class
				$this->user = new user($this);
		break;
}

?>