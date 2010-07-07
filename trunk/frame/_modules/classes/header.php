<?php
#######################
#	header.php
#   Svenn D'Hert
########################

class header
{
	public 
		$core,
		$page_info_full
		;
	
	
	function __construct($core, $page_info)
	{
		// reference to the core object
		$this->core = $core;
		
		// alle header informatie die niet gegeven is laden.
		$this->page_info_full = $this->load_missing($page_info);
		
		# f
		$this->page_info_full += array(
										"I_USER_LOGGED" => ($this->core->user->is_logged()) ? true : false,
										"USERNAME" 		=> $this->core->session->get('user_name'),
										"USERID"		=> $this->core->session->get('user_id') # == $this->core->user->get_user_id()
										);
		# /f
	}
		
	// loads missing header info from backup var
	function load_missing($page_info)
	{
		$full_page_info = array();

		foreach ($this->core->_page_head as $key => $value)
		{
			$full_page_info[$key] = (isset($page_info[$key])) ? $page_info[$key] : $this->core->_page_head[$key];			
		}
		
		# function fix
		if ( isset($page_info['AJAX']) )
		{
			$full_page_info['ADDAPT_HEADER'] = $page_info['AJAX'];
		}
		else
		{
			$full_page_info['ADDAPT_HEADER'] = "";
		}
		return $full_page_info;
		
	}
	
}
?>