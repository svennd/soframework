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
		$local_path 		= 'public/', 			# edit directory (this could be anywhere you place your files)
		$bck_dir			= '_modules/cms/_bck/',	# backup directory
		$edit_file_types 	= array('tpl', 'html'),	# don't wanne edit php files ever !
		$compress			= true,					# remove /r /n from code, every bit saves !
		$can_unlock			= false					# default we can't "unlock" 
		;
	
	/**
	* initialize
	* @param object $core
	*/
	function __construct($core)
	{
		# reference to the core object
		$this->core = $core;
		
		# change location of cms_location
		$this->local_path = (isset($this->core->cms_location)) ? $this->core->cms_location : $this->local_path;
		
		# check compression
		$this->compress = (isset($this->core->cms_compress)) ? $this->core->cms_compress : $this->compress;
		
		# check if we can unlock or not
		$this->can_unlock = (isset($this->core->cms_can_unlock)) ? $this->core->cms_can_unlock : $this->can_unlock;
		
	}
	
	/*
	* edit the file
	* @param array $key
	* @param array $new_content
	* @param string file
	*/
	# need to make multiple keys possible
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
	
	/*
	* load backup from past
	* @param string $file
	* @param string $timestamp
	*/
	public function load_backup ($file, $timestamp)
	{
		# backup current
		if (!$this->backup($file, $this->load_file($file)))
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
	* lock the file so it cannot be editted
	* @param string $file
	*/
	public function lock ($file, $final = false)
	{
		# backup current
		if (!$this->backup($file, $this->load_file($file)))
		{
			die('cannot create backup of current file.');
		}
		
		# content
		$content = $this->load_file($file);
		
		# note : once a file is open you can't file_get_contents it anymore
		$fh_content = fopen ($this->core->path . $this->local_path . $file, "wb+");
		
		# remove line feeds for code (important since it might be finilized here)
		if ($this->compress)
		{
			$content = preg_replace('/\t/', '', $content);
			$content = preg_replace('/\n/', '', $content);
			$content = preg_replace('/\r/', '', $content);
		}
		
		# if final we can remove all tags
		if ($final)
		{
			fwrite($fh_content, preg_replace ('/<!--(.*?)-->/s', '', $content));
		}
		# if not final we can set the edit value to false
		else 
		{
			fwrite($fh_content, preg_replace ('/<!--\sedit:true\s-->/s', '<!-- edit:false -->', $content));
		}
		
		# and close file
		fclose($fh_content);
		
		return true;
	}
	
	/*
	* unlock the file, this is a bit of a brute force way; might also not be the savest way
	*
	*/
	public function unlock ($file)
	{	
		# this needs to be set for safety purpose
		if (!$this->can_unlock) {die('cannot unlock');}
		
		# content
		# check if file exists; check if valid extension
		if (!file_exists($this->core->path . $this->local_path . $file) ||  !in_array(pathinfo($this->core->path . $this->local_path . $file, PATHINFO_EXTENSION), $this->edit_file_types))
		{
			die('requested non existing file, or wrong type');
		}

		$content = file_get_contents ($this->core->path . $this->local_path . $file);
		
		# backup current
		if (!$this->backup($file, $content))
		{
			die('cannot create backup of current file.');
		}
		
		# note : once a file is open you can't file_get_contents it anymore
		$fh_content = fopen ($this->core->path . $this->local_path . $file, "wb+");
				
		# overwrite file
		fwrite($fh_content, preg_replace ('/<!--\sedit:false\s-->/s', '<!-- edit:true -->', $content));

		# and close file
		fclose($fh_content);
		
		return true;
	}
	
	/*
	* load file
	* @param string $file
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
	* backup before doing anything!
	* @param string $file
	* @param mixed $content - original content
	*/
	private function backup ($file, $content)
	{
		# create current backup
		if (!file_exists($this->core->path . $this->local_path . $file))
		{
			return false;
		}

		# open for writing
		$fh_backup = fopen ($this->core->path . $this->bck_dir . basename($file) . "." . time() . ".bck", "wb+");
		
		# store
		fwrite($fh_backup , $content);
		
		# close
		fclose($fh_backup);
		
		return true;
	}	
}

?>