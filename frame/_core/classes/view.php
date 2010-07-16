<?php
#######################
#	file	: view.php
#   author 	: Svenn D'Hert
#	rev.	: 3
#	f(x)	: view system
########################

final class view
{
	public 
		$core,
		$variables = array(),
		$file_list = array(),
		$save_page = false
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
	
	public function save_page ( $bool )
	{
		$this->save_page = (bool) $bool;
	}
	
	# 'parse' $file
	public function use_page ($file)
	{
		# if we use the output buffer we save the content
		if ( $this->save_page )
		{
			# vars for the current file
			if ( !empty($this->variables) )
			{
				extract($this->variables);
			}
			
			if(file_exists($this->core->path . "frame/_template/" . $file . ".tpl"))
			{
				ob_start();
					include($this->core->path . "frame/_template/" . $file . ".tpl");
					
				$this->page .= ob_get_contents();
				
				ob_end_clean();
			}
		}
		# save the file and do it later ^_^
		else
		{
			$this->file_list[] = $file;
		}
	}
	
	# load the page at once
	private function load_full_page () 
	{
		# the variables $core->view-> variables get set here.
		if ( !empty($this->variables) )
		{
			extract($this->variables);
		}
		
		# load all the pages and output them
		foreach ( $this->file_list as $file )
		{
			if(file_exists($this->core->path . "frame/_template/" . $file . ".tpl"))
			{
				include($this->core->path . "frame/_template/" . $file . ".tpl");
			}
		}
	}
	
	public function push_output ()
	{
		return ( !empty($this->page) ) ? $this->page : $this->load_full_page();
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