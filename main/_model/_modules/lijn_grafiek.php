<?php
# plugin module - line grafiek system
# Svenn D'Hert

switch ($mode)
{
	case "read_config" :
			$struct = array (
							"name"		=> "Line grafiek",
							"version"	=> "1.0.0",
							"author"	=> "Svenn D\'Hert"
							);
		break;
		
	case "construct" :
		$core->lijn_grafiek = new lijn_grafiek($core);
		break;
}
?>