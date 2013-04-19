<?php
/**
* @package SoFramework
* @copyright 2010 Svenn D'Hert
* @license http://www.gnu.org/licenses/gpl-2.0.txt GNU Public License
*/

# main config variable
# all variabels here are optional, they have defaults set in there class files;
# but they might be overriden by custom installation; It is proper to set this here;
# though you are free to addapt the class aswell, this location however will have higher priority

# database config is located seperatly for security reasons see : _modules/database/config.php
$config = array (
					# view files location
					// 'view_location' => 'public/', 
					
					# editable file locations
					// 'cms_location' => 'public/', 
					
					 # on editing it will remove \t\n\r from files
					// 'cms_compress' => true,
					
					# cms can unlock files (default:false, for safety)
					// 'cms_can_unlock' => true,
					
					# user module : set salt for pasword generation
					// 'user_salt' => 'Cs_8.s3-',
					
					# log module : location for saving log files
					// 'log_path' => '_main/',
				);
