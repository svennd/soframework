<?php
#######################
#	file	: view.php
#   author 	: Svenn D'Hert
#	rev.	: 2
#	f(x)	: view system
########################

final class view
{
	public 
		$core,
		$variables
		;
	
	private
		$page
		;
		
	function __construct($core)
	{
		// reference to the core object
		$this->core = $core;
	
	}
	
	# adding new vars to the view space
	# i don't really like this way (magic), tho I don't see a other option at current.
	# works like : 
	# $core->view->test = 123;
	# on view level looks like :
	# $test;
	public function __set($name, $value)
	{
		$this->variables[$name] = $value;
	}
	
	# 'parse' $file
	public function use_page ($file)
	{
		# this makes sure, user doesn't get anything we don't want.
		if ( !empty($this->variables) )
		{
			extract($this->variables);
		}
		
		# load file
		if(file_exists("frame/_template/" . $file . ".tpl"))
		{
			ob_start();
			include("frame/_template/" . $file . ".tpl");
			$output = ob_get_contents();
			ob_end_clean();
			
			# return content
			$this->page .= $output;
		}
		# maybe an error would be nice tho
		return false;
	}
	
	# pust output to screen
	public function push_output ()
	{
		return ( !empty($this->page) ) ? $this->page : $this->core->error( 1, "Pagina kon is niet aangemaakt", __FILE__, __LINE__);
	}
	
	# removes visible ads in end-user output
	public function remove_tags ()
	{
		if ( isset($this->page))
		{	
			$this->page = preg_replace( '/<!--(.+?)-->/', '', $this->page); 
		}
	}
}

?>