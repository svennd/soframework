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
				include $this->module_path . 'chart.php';
				
				// 'boot' the class
				$this->chart = new chart($this);
		break;
}

?>