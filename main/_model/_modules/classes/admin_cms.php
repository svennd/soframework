<?php
#######################
#	file	: admin_cms.php
#   author 	: Svenn D'Hert
#	rev.	: 1
#	f(x)	: basic cms function
########################

class admin_cms
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
		#$this->get_text_fields( $this->load('index'));
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
		if ( preg_match_all("/<!--\s*?TEXT\s*?-->(.*?)<!--\s*?TEXTEND\s*-->/is", $file_content, $r) )
		{
			return $r;
		}
		return false;
	}
	
	# 
	public function show_edit_page ( $file )
	{
		$content = $this->load( $file );
		$text_fields = $this->get_text_fields( $content );
		$count = count( $text_fields['1'] );
		
		$i = 0;
		while ( $i < $count )
		{
			# adding the edit fields
			$content = preg_replace(
									'/<!--\s*?TEXT\s*?-->(.*?)<!--\s*?TEXTEND\s*-->/is', 
									'<div id="hide" class="link' . $i . '">
									 $1&nbsp;
									 <a href="#" class="link' . $i . '"><img src="' . $this->core->path . 'frame/_template/images/ico_edit.png" alt="edit" /></a>
									 </div>
									 <div id="text" class="toggle-item-link' . $i . '"><textarea name="edit_field[]">$1</textarea></div>'
									, $content
									, 1); # only once a time
			$i++;
		}
		
		# i know this aint the best nor propper way, but hey it works.
		$content = '<form action="?save_page" method="POST" id="page_edit">' . $content . '</form>';
		
		return $this->remove_php($content);
	}
	
	# this should be checked again, but since this is a fixed ( = private) i din't feel the need to implement this
	public function get_file_list ()
	{
		return $this->edit_able;
	}
	
	# removes php from template as it is never editable nor can it be parsed apropiate
	private function remove_php( $string )
	{
		return preg_replace('/<\?p?h?p?(.*?)\?>/is', '' , $string);
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
}
?>