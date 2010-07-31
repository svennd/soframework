<?php
# plugin module - logging system
# Svenn D'Hert 

switch ($mode)
{
	case "read_config" :
			$struct = array (
						"name"		=> "logging system",
						"version"	=> "1.0.0",
						"author"	=> "Svenn D\'Hert"
						);
		break;
		
	case "construct" :	
		$core->log = new log($core);
		break;
}
?>