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
		$file_list = array()
		;
	
	private
		$page,
		$local_path = '_view/' # default, change in main config!
		;
	
	/**
	* initialize
	* @param object $core
	*/
	function __construct($core)
	{
		# reference to the core object
		$this->core = $core;
		
		# change location of view_location
		if (isset($this->core->view_location))
		{
			$this->local_path = $this->core->view_location;
		}
		
		# set page info to template
		$this->set( 'page_info', $this->core->page_info);
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
	* set variables, proper way
	* @param string $name
	* @param mixed $value
	*/
	public function set($name, $value)
	{
		$this->variables[$name] = $value;
	}
	
	/**
	* process the requested page
	* @param string $file
	*/
	public function use_page ($file)
	{
		$this->file_list[] = $file;
	}
	
	/**
	* run the page
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
			if(file_exists($this->core->path . $this->local_path . $file . ".tpl"))
			{
				include($this->core->path . $this->local_path . $file . ".tpl");
			}
			elseif (file_exists($this->core->path . $this->local_path . $file . ".html"))
			{
				include($this->core->path . $this->local_path . $file . ".html");
			}
			else
			{
				die('couldn\t find a file needed for view module :' . htmlspecialchars($file));
			}
		}
	}
	
	/**
	* generate output
	* @return string
	*/
	public function push_output ()
	{
		return $this->load_full_page();
	}
}

?>