<?php
#######################
#	file	: form.php
#   author 	: Svenn D'Hert (idea from ElbertF)
#	rev.	: 1
#	f(x)	: form validation
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
					'email'   => '/^[a-z0-9._-]{1,50}@[a-z0-9_-]{1,50}\.[a-z.]{1,6}$/i'
					)
		;
	
	function __construct($core)
	{
		// reference to the core object
		$this->core = $core;
	}
	
	
	# wip
}
?>