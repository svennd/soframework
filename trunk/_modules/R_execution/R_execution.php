<?php
/**
* @package SoFramwork
* @copyright 2010 Svenn D'Hert
* @license http://www.gnu.org/licenses/gpl-2.0.txt GNU Public License
*/

/**
* R execution class
* @abstract
*/
final class R
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
		
		# R directory
		if (!$this->check_write_info(getcwd() . "/R/"))
		{
			die("Make sure R/ is writable (chmod 777) -stopped execution-");
		}
		
		# R cache directory
		if (!$this->check_write_info(getcwd() . "/R/R_cache/"))
		{
			die("Make sure R/R_cache/ is writable (chmod 777) -stopped execution-");
		}
		
		# R data directory
		if (!$this->check_write_info(getcwd() . "/R/R_data/"))
		{
			die("Make sure R/R_data/ is writable (chmod 777) -stopped execution-");
		}
	}
	/**
	* get the possible columns (header needed)
	*/
	public function get_header($datafile)
	{
		if (!$this->check_file("/R_data/". $datafile)) { echo "datafile not found."; return false; }
		$fh_datafile = fopen(getcwd() . '/R/R_data/'. $datafile, "r");
		if ($fh_datafile)
		{
			$header = fgetcsv($fh_datafile, 1000, "\t");
		}
		fclose($fh_datafile);
		
		# remove empty values
		return array_filter($header);
	}
	
	/*
	* execute the script
	*/
	public function run_script($scriptN, $datafile, $rows_A, $rows_B)
	{
		# check if script is there
		if (!$this->check_file($scriptN)) { echo "script not found."; return false; }
		if (!$this->check_file("R_data/". $datafile)) { echo "datafile not found."; return false; }
		
		$script 	= getcwd(). '/R/' . $scriptN;
		$count_A 	= count($rows_A);
		$count_B 	= count($rows_B);
		
		# transform the rowNames to rowID's
		$row_id_A = array();
		$row_id_B = array();
		$header = $this->get_header($datafile);
		foreach ($rows_A as $rname)
		{
			$row_id_A[] = array_search($rname, $header);
		}
		foreach ($rows_B as $rname)
		{
			$row_id_B[] = array_search($rname, $header);
		}
		
		# should not be unique, just id to find if already run
		$jobid 		= $count_A . "_" . $count_B . "_" . implode($row_id_A) . "_" . implode($row_id_B);
		$exec_field = "Rscript " . $script . " " . $jobid . " " . $datafile . " " . $count_A . " " . $count_B . " " . implode(" ", $row_id_A) . " " . implode(" ", $row_id_B). "  > " . getcwd() . "/R/R_log/" . $jobid . " &";
		
		# need to run?
		if (!$this->check_file('R_cache/complete_'. $jobid))
		{
			exec ($exec_field);
			# run
			return array('status' => 2, 'jobid' => $jobid);
		}
		# already run
		return array('status' => 1, 'jobid' => $jobid);
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
	
	/**
	* Check if the file to be executed exists;
	*/
	private function check_file ($file) 
	{
		if (is_file(getcwd(). '/R/' . $file))
		{
			return true;
		}
		return false;
	}
}

?>