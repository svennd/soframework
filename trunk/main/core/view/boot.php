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
				include $module_path . 'view.php';
				
				// 'boot' the class
				$this->view = new view($this);
		break;
	
	// end of database connection
	case "destruct" :
				$this->view->push_output();
		break;
}

?>