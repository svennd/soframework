<?php
/**
* @package SoFramwork
* @copyright 2010 Svenn D'Hert
* @license http://www.gnu.org/licenses/gpl-2.0.txt GNU Public License
*/

/**
* view class
* @abstract
*/

class mysql
{
	public 
		$core,
		$db_ready = false
		;
	
	/**
	* initialize
	* @param object $core
	*/
	function __construct($core)
	{
		// reference to the core object
		$this->core = $core;

		// connection
		if ( ! $this->db_ready = $this->connect(
										$this->core->_database['db_host'], 
										$this->core->_database['db_u_name'], 
										$this->core->_database['db_u_pass'], 
										$this->core->_database['db_name']
										))
		{
			// no connection
			$core->log->save('MySQL error :'. mysql_error(), 'error_log');
		}
		
		# 'security', this info in not needed anymore.
		unset($this->core->_database);
		
	}
	
	/**
	* connect
	* @param string $host
	* @param string $user
	* @param string $password
	* @param string $db
	*/
	private function connect($host, $user, $password, $db )
	{
		// mysql connection
		$this->db_link = mysql_connect($host, $user, $password) or $core->log->save('MySQL error :'. mysql_error(), 'error_log');
		if (!$this->db_link)
		{
			return false;
		}
		
		// database selection
		$db_selected = mysql_select_db($db, $this->db_link) or $core->log->save('MySQL error :'. mysql_error(), 'error_log');
		if (!$db_selected)
		{
			return false;
		}
		
		return true;
	}
	
	/**
	* escape vars
	* @param mixed $v
	*/
	public function esc ($v)
	{
		if ( is_array($v) )
		{
			# recursie ^_^
			return array_map(array($this, 'esc'), $v);
		}
		# no array
		return mysql_real_escape_string($v);
	}
	
	/**
	* run a query
	* @param string $query
	* @param string $file
	* @param string $line
	* @return mixed
	*/
	public function sql ($query, $file, $lijn)
	{		
		# send the query
		$result = mysql_query($query) or $this->core->log->save( $query . mysql_errno() . mysql_error() . __FILE__ . __LINE__, 'error_log');
				
		# dit verwijderd spaties & enters
		$query = trim(preg_replace('/\s+/', ' ', $query));
		
		# verwerk de resultaten
		if ( $result )
		{
			# select, show en explain geven "array" resource terug
			if ( preg_match('/^(SELECT|SHOW|EXPLAIN)/i', $query) )
			{
				# fix ; in het geval deze al gezet is tijdens deze pagina.
				$this->result = array();
				
				# lees de resultaten uit
				while ( $d = mysql_fetch_array($result) )
				{
					$this->result[] = $d;
				}
				
				# clean up the request
				mysql_free_result( $result );
				
				if (preg_match('/LIMIT\s?0?\s?,?\s?1\s?;/i', $query))
				{
					$this->result = $this->result['0'];
				}
				
				# return
				return $this->result;
			}
			# delete, insert, (...) geven een bool terug
			else
			{
				if( preg_match('/^INSERT/i', $query) )
				{
					# id van de nieuwe rij
					return $this->result = mysql_insert_id();
				}
				else
				{
					# aantal aangepaste rijen, indien 0 failed.
					return $this->result =  mysql_affected_rows();
				}
			}
		}
	}

	/**
	* close connection with database
	* @param string $query
	* @param string $file
	* @param string $line
	* @return mixed
	*/
	public function close_connection ()
	{
		// close connection if made.
		if ( $this->db_ready)
		{
			mysql_close($this->db_link);
			$this->db_ready = false;
		}
	}
}

?>