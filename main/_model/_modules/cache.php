<?php
# plugin module - sql cache system
# Svenn D'Hert

switch ($mode)
{
	case "read_config" :
			$struct = array (
							"name"		=> "SQL cache system",
							"version"	=> "1.0.0",
							"author"	=> "Svenn D\'Hert"
							);
		break;
		
	case "construct" :
		$core->cache = new cache();
		break;
}
?>