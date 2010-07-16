<?php
#######################
#	_config.php
#   Svenn D'Hert
########################

// configuratie
$config = array (
					"_core_modules"	=> array(
												'construct' => array("mysql", "view", "sessions"),
												'destruct' 	=> array("view", "sessions", "mysql"),
											),
					"_database" 	=> array(												# database settings (MySQL plugin based)
										"host" 				=> "localhost",						# database host name
										"username" 			=> "root",							# database user name
										"paswoord" 			=> "",								# database password
										"database" 			=> "koten"							# database name
									)
					);
					
?>