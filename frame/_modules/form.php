<?php
# plugin module - form validation
# Svenn D'Hert

switch ($mode)
{
	case "read_config" :
			$struct = array (
						"short"		=> "FV",
						"name"		=> "Form Validation",
						"version"	=> "1.0.0",
						"author"	=> "Svenn D\'Hert"
						);
		break;
		
	case "construct" :
		$core->form = new form($core);
		break;
}

?>