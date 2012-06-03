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
		$edit_able = array('cms_example')
		;
	
	/**
	* initialize
	* @param object
	*/
	function __construct( $core )
	{
		// reference to the core object
		$this->core = $core;
	}
	
	/**
	* loads content of page
	* @param string
	* @return string
	*/
	private function load ( $file )
	{
		if(file_exists($this->core->path . "main/_view/" . $file . ".tpl") && in_array($file, $this->edit_able))
		{
			return file_get_contents ($this->core->path . "main/_view/" . $file . ".tpl");
		}
		return false;
	}
	
	/**
	* get all edit-able fields from a page
	* @param string
	* @return array
	*/
	public function get_text_fields ( $file )
	{
		$file_content = $this->load ( $file );
		if ( preg_match_all("/<!--\s*?start_blok name=(.*?)\s*?-->(.*?)<!--\s*?end_blok\s*-->/is", $file_content, $r) )
		{
			return $r;
		}
		return false;
	}

	/**
	* shows file list of edit-able files
	* @return array
	*/
	public function get_file_list ()
	{
		return $this->edit_able;
	}

	/**
	* edit file
	* @param array (see note)
	* @param string $file file where to write the edits
	* @return bool
	* #################################################################################################
	* # note : 
	* #	content should be like this :
	* #	$content = array(
	* #					"old_txt" => array( 
	* #										'<!-- start_blok name=name -->old text<!-- end_blok -->',
	* #										'<!-- start_blok name=name -->old text 2<!-- end_blok -->'
	* #									) ,
	* #					"new_txt" => array( 
	* #										'<!-- start_blok name=name -->new text<!-- end_blok -->',
	* #										'<!-- start_blok name=name -->new text 2<!-- end_blok -->'
	* #									) ,
	* #					);
	* #################################################################################################
	*/
	public function edit_file ( $content, $file )
	{
		# lets check howmuch edits today
		$i = count ($content['old_txt']) - 1;
		
		# atleast more then 1
		if ( $i + 1 > 0 )
		{
			# get file content
			$file_content = $this->load($file);
			
			# before edit backup :)
			if ( $this->backup($file, $file_content) )
			{
				# do all the changes
				while ( $i > -1)
				{
					$string = preg_replace( '/' . $content['old_txt'][$i] . '/s' , $content['new_txt'][$i], $file_content );
					$i--;
				}
				
				# save up everything
				# open file for writing
				$file_open = fopen ($this->core->path . "main/_view/" . $file . ".tpl", "wb+");
				
				# overwrite file
				fwrite($file_open , $string);
				
				# and close file
				fclose($file_open);
				return true;
			}
		}
		return false;
	}
	
	
	/**
	* backup procedure
	* @param string
	* @param string
	* @return bool
	*/
	private function backup ( $file, $content )
	{
		if(in_array($file, $this->edit_able))
		{
			# open for writing
			$file = (file_exists($this->core->path . "main/_view/" . $file . ".tpl")) ? fopen ($this->core->path . "main/_temp/_bck/" . $file . "." . time() . ".bck", "wb+") : false;
			# store
			fwrite($file , $content);
			# close
			fclose($file);
			return true;
		}
		# for some reason no backup was able to be build
		return false;
	}
}
?>