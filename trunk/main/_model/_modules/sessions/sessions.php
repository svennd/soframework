<?php
# plugin module - sessions
# Svenn D'Hert

switch ($mode)
{
	case "read_config" :
			$struct = array (
						"name"		=> "Session handeling",
						"version"	=> "1.0.0",
						"author"	=> "Svenn D\'Hert"
						);
		break;
		
	case "construct" :
			$core->session = new session($core);
		break;
	
	case "destruct" :
			# this is done at the end of the parser time, there is still no output at this point!
			$core->session->end();
		break;
}
?>