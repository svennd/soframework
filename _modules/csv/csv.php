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
		
		# cache directory
		if (!$this->check_write_info(getcwd() . "/cache/"))
		{
			die("Make sure /cache/ is writable (chmod 777) -stopped execution-");
		}
	}
	
	/**
	* get the header
	*/
	public function get_header($datafile)
	{
		if (!$this->check_file($datafile)) { die("file not found :" . htmlspecialchars($datafile)); }
		
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
	* $max_read_lines can be set to false for ignoring this parameter;
	*/
	public function read($datafile, $tab_delim = "\t", $max_read_lines = false, $offset_lines = 0)
	{
		if (!$this->check_file($datafile)) { die("file not found :" . htmlspecialchars($datafile)); }
		if (!is_bool($max_read_lines) && !is_numeric($max_read_lines)) { die("parameter error @csv module, not a bool/int"); }
		if (!is_numeric($offset_lines)) { die("parameter error @csv module, not a bool/int"); }
		
		$fh_datafile = fopen(getcwd() . "/" . $datafile, "r");
		if ($fh_datafile)
		{
			$line = 0;
			$line_saved = 0;
			while (($data = fgetcsv($fh_datafile, 1000, $tab_delim)) !== FALSE) {
				if ($offset_lines > $line) { continue; } 
				if ($max_read_lines && $max_read_lines <= $line_saved) { break; }
				
				$r[] = $data;
				$line_saved++;
			}
		}
		fclose($fh_datafile);
		
		return $r;
	}
	
	/**
	* create csv file
	* ugly function, should check stuff more. (.xls for easy handling, its .csv)
	*/
	public function create($name, $data)
	{		
		foreach ($data as $k => $v)
		{
			$lines .= implode("\t", $v) . "\n";
		}
		
		$fh = fopen(getcwd() . '/cache/' . $name. ".xls", 'w+') or die("can't open file");
		fwrite($fh, $lines);
		fclose($fh);
		
		return "cache/" . $name. ".xls";
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
		
	/**
	* Check if the dir is writable
	*/
	private function check_write_info ($path) 
	{
		if (is_writable($path))
		{
			return true;
		}
		return false;
	}
}

?>