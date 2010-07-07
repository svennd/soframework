<?php
#######################
#	file	: cache.php
#   author 	: Svenn D'Hert
#	rev.	: 1
#	f(x)	: sql caching system
########################

final class cache
{		
	# path could be provided by core, tho I don't wanne include the complete core for only the path.
	public function save ($name, $content, $path = './')
	{
		# file name
		$file_name = $name . '.cache';
					
		# open / make file
		$file = fopen($path . 'frame/_cache/' . $file_name, "w");
		
		# write to file, serialize is slow and cpu heavy process.
		fputs($file, serialize($content));
		
		# close file
		fclose($file); 
	}
	
	public function get ($name, $path = './') 
	{
		# file name
		$file = $name . '.cache';
		
		# check if file exists and check last edit time
		if (file_exists($path . 'frame/_cache/' . $file) && filemtime($path . 'frame/_cache/' . $file) > (time() - 60 * 60 * 24)) 
		{
			# we found it :)
			return unserialize(file_get_contents($path . 'frame/_cache/' . $file));
		}
		else
		{
			# no file found, considerd to make new
			return false;
		} 
	}
	
	public function clean ()
	{
		while( false !== ($filename = readdir('./frame/_cache/')) )
		{
			if( $filename != "." && $filename != ".." )
			{
				$filename = $directory. "/". $filename;

				if( @filemtime($filename) < (time()-$seconds_old) )
				{
					@unlink($filename);
				}
			}
		}
	}
}
?>