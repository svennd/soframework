<?php
/**
* @package SoFramwork
* @copyright 2010 Svenn D'Hert
* @license http://www.gnu.org/licenses/gpl-2.0.txt GNU Public License
*/

/**
* cms class
* @abstract
*/
final class cms
{
	public 
		$core
		;
		
	private
		$local_path = '_modules/view/_view/', # default, change in main config!
		$edit_file_types = array('tpl', 'html'),
		;
	
	/**
	* initialize
	* @param object $core
	*/
	function __construct($core)
	{
		// reference to the core object
		$this->core = $core;
		
		# change location of cms_location
		if (isset($this->core->cms_location))
		{
			$this->local_path = $this->core->cms_location;
		}
		
	}
	
	/*
	*
	*/
	public function edit_file ( $key, $new_content, $file ) 
	{
		# if load_file fails check if this also fails
		if (!$full_content = $this->load_file($file)) { die ('wrong file'); }
		
		# <!-- begin:header -->
		# <!-- end:header -->
		preg_replace ('<!--\sbegin:' . $key . '\s-->(.*?)<!--\send:' . $key . '\s-->', '<!--\sbegin:' . $key . '\s-->' . $new_content . '<!--\send:' . $key . '\s-->', $full_content);
		
	}
	
	public function load_backup ()
	{
		# backup current
		# load backup
	}
	
	/*
	*
	*/
	private function load_file ($file)
	{
		# check if file exists; check if valid extension
		if (!file_exists($this->core->path . $this->core->local_path . $file) ||  !in_array(pathinfo($this->core->path . $this->core->local_path . $file, PATHINFO_EXTENSION), $this->edit_file_types))
		{
			die('requested non existing file, or wrong type');
		}
		
		# load the file
		$file_content = file_get_contents ($this->core->path . $this->core->local_path . $file);
		
		# check if file is edit-able
		# <!-- edit:true -->
		if (!preg_match("/<!--\sedit:true\s-->/", $file_content))
		{
			return false;
		}
		
		return $file_content;
	}
	
	/*
	*
	*/
	private function backup ($file)
	{
		# create current backup
		if (!file_exists($this->core->path . $this->core->local_path . $file)) {
			return false;
		}
		# copy the current file
		
		# if succes
		return true;
	}	
}

?>