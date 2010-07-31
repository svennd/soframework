<?php
# plugin module - mysql, database
# Svenn D'Hert

switch ($mode)
{
	case "read_config" :
			$struct = array (
						"name"		=> "MySQL database management",
						"version"	=> "1.0.0",
						"author"	=> "Svenn D\'Hert"
						);
		break;
		
	case "construct" :
		#	$this->_database
		#	komt van config file, zit in frame, hoeft geen parameter te zijn.
		$core->db = new mysql($core);

		break;
		
	case "destruct" :
		$core->db->close_connection();
		break;
}

?>