<?php
/**
* @package SoFramwork
* @copyright 2010 Svenn D'Hert
* @license http://www.gnu.org/licenses/gpl-2.0.txt GNU Public License
*/

// no direct acces
if ( !isset($this) ){ exit('direct_acces_not_allowed'); }

switch ($mode)
{
	// start the module
	case "construct" :
				
				// include configuration for module
				include $module_path . 'config.php';
				
				if ( $detection )
				{
					// include the check file
					include $module_path . 'input_check.php';
				}
				
		break;
}

?>