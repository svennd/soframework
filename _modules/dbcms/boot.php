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
		
				// include the class file
				include $module_path . 'dbcms.php';
				
				// 'boot' the class
				$this->cms = new dbcms($this, $module_path);
		break;
}


// note : this module will overwrite the file based cms when used both. (they cannot be used both)
?>