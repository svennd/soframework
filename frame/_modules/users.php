<?php
# plugin module - user system
# Svenn D'Hert

switch ($mode)
{
	case "read_config" :
			$struct = array (
							"init" 		=> 3,
							"short"		=> "US",
							"depend" 	=> array("MDB", "SS"),
							"name"		=> "User system",
							"version"	=> "1.0.0",
							"author"	=> "Svenn D\'Hert"
							);
		break;
		
	case "init" :
		$core->user = new user($core);
		break;
}
?>