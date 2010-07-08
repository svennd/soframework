<?php
# plugin module - form validation
# Svenn D'Hert

switch ($mode)
{
	case "read_config" :
			$struct = array (
						"short"		=> "UH",
						"name"		=> "User Handeling",
						"version"	=> "1.0.0",
						"author"	=> "Svenn D\'Hert"
						);
		break;
		
	case "construct" :
		$core->user_handeling = new user_handeling($core);
		break;
}
?>