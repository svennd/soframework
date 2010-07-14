<?php
#######################
#	file	: user_handeling.php
#   author 	: Svenn D'Hert
#	rev.	: 1
#	f(x)	: basic user handeling
########################

class user_handeling
{
	function __construct($core)
	{
		$this->core = $core;
	}
	
	function send ( $page = 'index.php' )
	{
		# header attempt
		if ( !headers_sent() )
		{
			header('Location: ' . $page);
		}
		
		# if header is send, then send the complete page, dammit !
		$this->core->close();

		# javascript attempt
		echo '<script type="text/javascript"> window.location.href="' . $page . '</script>";';
		
		# meta attempt
		echo '<noscript><meta http-equiv="refresh" content="0;url=' . $page . '" /></noscript>';
		
		# wait 2 sec
		// sleep (2);
		
		# the text attempt. ^_^
		echo 'Leuke, pagina hé, maar kun je toch een <a href="' . $page . '">hier klikken</a>, dan kunnen we weer verder doen, dankje !';
		
		# no script beyond this point. 
		exit;
	}
}
?>