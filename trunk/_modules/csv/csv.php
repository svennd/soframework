<?php
/**
* @package SoFramwork
* @copyright 2010 Svenn D'Hert
* @license http://www.gnu.org/licenses/gpl-2.0.txt GNU Public License
*/

/**
* csv class
* @abstract
*/
final class csv
{
	public
			$core
			;
			
	/**
	* initialize
	* @param object $core
	*/
	function __construct($core)
	{
		# ref
		$this->core = $core;
	}
	
	/**
	* get the header
	*/
	public function get_header($datafile)
	{
		if (!$this->check_file($datafile)) { die("file not found."); }
		
		$fh_datafile = fopen(getcwd() . $datafile, "r");
		if ($fh_datafile)
		{
			$header = fgetcsv($fh_datafile, 1000, "\t");
		}
		fclose($fh_datafile);
		
		# remove empty values
		return array_filter($header);
	}

	/**
	* get the full file
	*/
	public function read($datafile, $max_read_lines = 1000, $offset_lines = 0)
	{
		if (!$this->check_file($datafile)) { die("file not found."); }
		
		$fh_datafile = fopen(getcwd() . "/" . $datafile, "r");
		if ($fh_datafile)
		{
			$line = 0;
			$line_saved = 0;
			while (($data = fgetcsv($fh_datafile, 1000, " ")) !== FALSE) {
				if ($offset_lines > $line) { continue; } 
				if ($max_read_lines <= $line_saved) { break; }
				
				$r[] = $data;
				$line_saved++;
			}
		}
		fclose($fh_datafile);
		
		return $r;
	}
	
	
	/**
	* Check if the file exists;
	*/
	private function check_file ($file) 
	{
		if (is_file(getcwd() .'/'. $file))
		{
			return true;
		}
		return false;
	}
}

?>