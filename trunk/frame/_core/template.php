<?php
# plugin module - template system
# Svenn D'Hert

switch ($mode)
{
	case "read_config" :
			$struct = array (
						"name"		=> "Template system",
						"version"	=> "1.0.0",
						"author"	=> "Svenn D\'Hert"
						);
		break;
		
	case "construct" :	
		$core->template = new template($core);
		break;
	
	case "destruct" :
		# output the page.
		echo $core->template->push_output();
		break;
}
?>