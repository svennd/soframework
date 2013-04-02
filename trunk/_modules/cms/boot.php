<?php
/**
* @package SoFramwork
* @copyright 2010 Svenn D'Hert
* @license http://www.gnu.org/licenses/gpl-2.0.txt GNU Public License
*/

// no direct acces
if ( !isset($this) ){ exit('direct_acces_not_allowed'); }

$mode = (isset($mode)) ? $mode : '';
switch ($mode)
{
	// start the module
	case "construct" :
		
				// include the class file
				include $module_path . 'cms.php';
				
				// 'boot' the class
				$this->cms = new cms($this);
		break;
}

?>