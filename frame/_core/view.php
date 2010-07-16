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
		
		# if saved then we need to use the buffer :)
		if ( $core->view->save_page )
		{
			# remove tags from output
			$core->view->remove_tags();
		}
		# output the page.
		echo $core->view->push_output();

		
		break;
}
?>