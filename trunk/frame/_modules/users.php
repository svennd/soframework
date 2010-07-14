<?php
# plugin module - user system
# Svenn D'Hert

switch ($mode)
{
	case "read_config" :
			$struct = array (
							"name"		=> "User system",
							"version"	=> "1.0.0",
							"author"	=> "Svenn D\'Hert"
							);
		break;
		
	case "construct" :
		$core->user = new user($core);
		break;
}
?>