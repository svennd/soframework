<?php
/**
* @package SoFramwork
* @copyright 2010 Svenn D'Hert
* @license http://www.gnu.org/licenses/gpl-2.0.txt GNU Public License
*/

/**
* view class
* @abstract
*/
final class view
{
	public 
		$core,
		$variables = array(),
		$file_list = array(),
		$user_buffer = false
		;
	
	private
		$page
		;
	
	/**
	* initialize
	* @param object $core
	*/
	function __construct($core)
	{
		// reference to the core object
		$this->core = $core;
	}
	
	/**
	* set variables
	* @param string $name
	* @param mixed $value
	*/
	public function __set($name, $value)
	{
		$this->variables[$name] = $value;
	}
	
	/**
	* use the buffer or instand output
	* @param bool $bool
	*/
	public function user_buffer ( $bool )
	{
		$this->user_buffer = (bool) $bool;
	}
	
	/**
	* process the requested page
	* @param string $file
	*/
	public function use_page ($file)
	{
		# if we use the output buffer we save the content
		if ( $this->user_buffer )
		{
			# vars for the current file
			if ( !empty($this->variables) )
			{
				extract($this->variables);
			}
			
			if(file_exists($this->core->path . "main/_view/" . $file . ".tpl"))
			{
				ob_start();
					include($this->core->path . "main/_view/" . $file . ".tpl");
					
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
	
	/**
	* no buffer used, include files
	*/
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
			if(file_exists($this->core->path . "main/_view/" . $file . ".tpl"))
			{
				include($this->core->path . "main/_view/" . $file . ".tpl");
			}
		}
	}
	
	/**
	* generate output
	* @return string
	*/
	public function push_output ()
	{
		# remove additional html content if possible
		return ( !empty($this->page) ) ? preg_replace( '/<!--(.+?)-->/', '', $this->page) : $this->load_full_page();
	}
}

?>