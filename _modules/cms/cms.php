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
		$local_path 		= '_modules/view/_view/', # default, change in main config!
		$bck_dir			= '_modules/cms/_bck/',
		$edit_file_types 	= array('tpl', 'html'),
		$compress			= true
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
		$this->local_path = (isset($this->core->cms_location)) ? $this->core->cms_location : $this->local_path;
		
		# check compression
		$this->compress = (isset($this->core->cms_compress)) ? $this->core->cms_compress : $this->compress ;
		
	}
	
	/*
	*
	*/
	public function edit_file ( $key, $new_content, $file ) 
	{
		# if load_file fails check if this also fails
		if (!$full_content = $this->load_file($file)) { die ('couldn\'t load file.'); }
		
		if ($this->backup($file, $full_content))
		{
			# <!-- begin:header -->
			# <!-- end:header -->
			$matched = preg_replace ('/<!--\sbegin:' . $key . '\s-->(.*?)<!--\send:' . $key . '\s-->/s', '<!-- begin:' . $key . ' -->' . $new_content . '<!-- end:' . $key . ' -->', $full_content, -1, $count);
			
			# if there are multiple matches something when wrong.
			if ($matched && $count == 1) 
			{
				# remove line feeds for code
				if ($this->compress)
				{
					$matched = preg_replace('/\t/', '', $matched);
					$matched = preg_replace('/\n/', '', $matched);
					$matched = preg_replace('/\r/', '', $matched);
				}
				
				# write content to new file
				$fh_content = fopen ($this->core->path . $this->local_path . $file, "wb+");
				
				# overwrite file
				fwrite($fh_content , $matched);
				
				# and close file
				fclose($fh_content);
				
				# return succes
				return true;
			}
			else
			{
				die('could not update file, regex failed.');
			}
		}
		else 
		{
			die('could not generate backup.');
		}
	}
	
	public function load_backup ($file, $timestamp)
	{
		# backup current
		if ($this->backup($file, $this->load_file($file)))
		{
			die('cannot create backup of current file.');
		}
		
		# load backup
		if (!file_exists($this->core->path . $this->bck_dir . $file . $timestamp . '.bck') )
		{
			die('cannot find backup.');
		}
		
		$file_content = file_get_contents ($this->core->path . $this->bck_dir . $file . $timestamp . '.bck');

		# write content to new file
		$fh_content = fopen ($this->core->path . $this->local_path . $file, "wb+");

		# overwrite file
		fwrite($fh_content , $content);

		# and close file
		fclose($fh_content);
		
		return true;		
	}
	
	/*
	*
	*/
	private function load_file ($file)
	{
		# check if file exists; check if valid extension
		if (!file_exists($this->core->path . $this->local_path . $file) ||  !in_array(pathinfo($this->core->path . $this->local_path . $file, PATHINFO_EXTENSION), $this->edit_file_types))
		{
			die('requested non existing file, or wrong type');
		}
		
		# load the file
		$file_content = file_get_contents ($this->core->path . $this->local_path . $file);
		
		# check if file is edit-able
		# <!-- edit:true -->
		if (!preg_match("/<!--\sedit:true\s-->/", $file_content))
		{
			die('file not edit-able');
		}
		
		return $file_content;
	}
	
	/*
	*
	*/
	private function backup ($file, $content)
	{
		# create current backup
		if (!file_exists($this->core->path . $this->local_path . $file)) {
			return false;
		}
				
		# open for writing
		$fh_backup = fopen ($this->core->path . $this->bck_dir . $file . "." . time() . ".bck", "wb+");
		
		# store
		fwrite($fh_backup , $content);
		
		# close
		fclose($fh_backup);
		
		return true;
	}	
}

?>