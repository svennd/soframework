<?php

final class cms
{
	public 
		$core
		;
		
	private 
		$edit_able = array('vermogen', 'werkwijze', 'resultaten', 'mail_send_page', 'index', 'forexprof', 'forex', 'contact_form', 'disclaimer')
		;
		
	function __construct( $core )
	{
		// reference to the core object
		$this->core = $core;
	}
	
	# gets the wanted file
	private function load ( $file )
	{
		if(file_exists($this->core->path . "main/_view/" . $file . ".tpl") && in_array($file, $this->edit_able))
		{
			return file_get_contents ($this->core->path . "main/_view/" . $file . ".tpl");
		}
		return false;
	}
	
	# get all editable fields from a file (this is a slow proces)
	public function get_text_fields ( $file )
	{
		$file_content = $this->load ( $file );
		if ( preg_match_all("/<!--\s*?start_blok name=(.*?)\s*?-->(.*?)<!--\s*?end_blok\s*-->/is", $file_content, $r) )
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

	private function backup ( $file, $content )
	{
		if(in_array($file, $this->edit_able))
		{
			# open for writing
			$file = (file_exists($this->core->path . "main/_view/" . $file . ".tpl")) ? fopen ($this->core->path . "main/_view/_bck/" . $file . "." . time() . ".bck", "wb+") : false;
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