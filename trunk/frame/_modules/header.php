<?php
# plugin module - template system
# Svenn D'Hert

switch ($mode)
{
	case "read_config" :
			$struct = array (
						"init"		=> 99,
						"end"		=> 1,
						"short"		=> "HD",
						"depend" 	=> array("SS", "MDB", "US"),
						"name"		=> "Header",
						"version"	=> "1.0.0",
						"author"	=> "Svenn D\'Hert"
						);
		break;
		
	case "init" :
		if ( !isset($this->page_info['IN_CORE']) )
		{
			$core->header = new header($core, $this->page_info);
			
			// header output
			$core->template->output_page($core->header->page_info_full, "header");
			
		}
		break;

}
?>