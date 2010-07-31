<?php
# plugin module - view system (template sytem)
# Svenn D'Hert 

switch ($mode)
{
	case "read_config" :
			$struct = array (
						"name"		=> "view system",
						"version"	=> "1.0.0",
						"author"	=> "Svenn D\'Hert"
						);
		break;
		
	case "construct" :	
		$core->view = new view($core);
		break;
	
	case "destruct" :
		# output the page.
		echo $core->view->push_output();		
		break;
}
?>