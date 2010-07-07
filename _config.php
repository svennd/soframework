<?php
#######################
#	_config.php
#   Svenn D'Hert
########################

// configuratie
$config = array (
					"_core_modules"	=> array(
												'construct' => array("mysql", "template", "sessions"),
												'destruct' 	=> array("template","sessions", "mysql"),
											),
					"_database" => array(													# database settings (MySQL plugin based)
										"host" 				=> "localhost",						# database host name
										"username" 			=> "root",							# database user name
										"paswoord" 			=> "",								# database password
										"database" 			=> ""							# database name
									)
					);
					
?>