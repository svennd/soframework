<?php
# plugin module - user system
# Svenn D'Hert

switch ($mode)
{
	case "read_config" :
			$struct = array (
							"name"		=> "CMS admin",
							"version"	=> "1.0.0",
							"author"	=> "Svenn D\'Hert"
							);
		break;
		
	case "construct" :
		$core->cms = new cms($core);
		break;
		
	case "destruct" :
		# this will remove the tags from html
		# just to not tell the end-user to much info ;)
		$core->cms->remove_tags();
	break;
}
?>