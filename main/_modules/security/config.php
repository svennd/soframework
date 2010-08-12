<?php
/**
* @package SoFramwork
* @copyright 2010 Svenn D'Hert
* @license http://www.gnu.org/licenses/gpl-2.0.txt GNU Public License
*/

// use url detection in $_GET, $_POST variabele
$detection = true;

// action when a url has been detected
// 	0 : log, no action
// 	1 : log, and kill current script (die())
//	2 : log, ban user temp. (not yet implemented)
$detection_action = 1;
?>