<?php
#######################
#	formvalid.php
#   Svenn D'Hert
########################

class form
{
	public 
		$core
		;
		
	private 
		$regex = array(
					'bool'    => '/^.*$/',
					'empty'   => '/^$/',
					'int'     => '/^-?[0-9]{1,256}$/',
					'string'  => '/^.{1,256}$/',
					'text'    => '//',# parse everything this one gave a bug : /^.{1,}$/
					'email'   => '/^[a-z0-9._-]{1,120}@[a-z0-9_-]{1,120}\.[a-z.]{1,6}$/i'
					)
		;
	
	function __construct($core)
	{
		// reference to the core object
		$this->core = $core;
	}
	
	// valideerd forms
	function valid_form($form)
	{
		$errors = array();
		
		foreach ($form as $naam => $type)
		{
			if ( isset($this->core->POST_raw[$naam]) )
			{
				if ( preg_match($this->regex[$type], $this->core->POST_raw[$naam]) )
				{
					$this->core->post_valid[$naam] = $this->core->POST_raw[$naam];
				}
				else
				{
					$errors[] = $naam;
				}
			}
			else
			{
				$errors[] = $naam . ' - niet ingegeven';
			}
		}
		# errors stop here
		if ( count($errors) > 0 )
		{
			$this->core->template->user_error(
											'Post : Failed', 
											'Oei, er zijn enkele problemen met de ingegeven waarden. Gelieve deze te controleren : ',
											$errors
										);
			return 0;
		}
		else
		{
			return 1;
		}
	}
}
?>