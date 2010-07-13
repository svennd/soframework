<?php
#######################
#	admin.php
#   Svenn D'Hert
########################

class cms
{
	public 
		$core
		;
		
	private 
		$edit_able = array('index', 'header')
		;
		
	function __construct( $core )
	{
		// reference to the core object
		$this->core = $core;
		$this->get_text_fields( $this->load('index'));
	}
	
	# gets the wanted file
	private function load ( $file )
	{
		if(file_exists($this->core->path . "frame/_template/" . $file . ".tpl") && in_array($file, $this->edit_able))
		{
			return file_get_contents ($this->core->path . "frame/_template/" . $file . ".tpl");
		}
		return false;
	}
	
	# get all editable fields from a file (this is a slow proces)
	private function get_text_fields ( $file_content )
	{
		if ( preg_match_all("/<!-- TEXT -->(.*?)<!-- TEXTEND -->/is", $file_content, $r) )
		{
			return $r;
		}
		return false;
	}
	
	# this should be checked again, but since this is a fixed ( = private) i din't feel the need to implement this
	public function get_file_list ()
	{
		return $this->edit_able;
	}
	
	# edit the file, and text
	# content should be like this :
	# $content = array(
	#					"old_txt" => array( 
	#										'<!-- TEXT -->old text<!-- TEXTEND -->',
	#										'<!-- TEXT -->old text 2<!-- TEXTEND -->'
	#									) ,
	#					"new_txt" => array( 
	#										'<!-- TEXT -->new text<!-- TEXTEND -->',
	#										'<!-- TEXT -->new text 2<!-- TEXTEND -->'
	#									) ,
	#					);
	# $file should contain a edit_able filename
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
					$string = preg_replace( '/' . $content['old_txt'][$i] . '/' , $content['new_txt'][$i], $file_content );
					$i--;
				}
				
				# save up everything
				# open file for writing
				$file_open = fopen ($this->core->path . "frame/_template/" . $file . ".tpl", "wb+");
				
				# overwrite file
				fwrite($file_open , $string);
				
				# and close file
				fclose($file_open);
			}
		}
	}
	
	private function backup ( $file, $content )
	{
		if(in_array($file, $this->edit_able))
		{
			# open for writing
			$file = (file_exists($this->core->path . "frame/_template/" . $file . ".tpl")) ? fopen ($this->core->path . "frame/_template/_bck/" . $file . "." . time() . ".bck", "wb+") : false;
			# store
			fwrite($file , $content);
			# close
			fclose($file);
			return true;
		}
		# for some reason no backup was able to be build
		return false;
	}
	
	# removes visible ads in end-user output
	public function remove_tags ()
	{
		if ( isset($this->core->template->page))
		{	
			$this->core->template->page = preg_replace( '/<!--(.+?)-->/', '', $this->core->template->page); 
		}
	}
}
?>