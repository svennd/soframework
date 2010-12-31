<?php
/**
* @package SoFramwork
* @copyright 2010 Svenn D'Hert
* @license http://www.gnu.org/licenses/gpl-2.0.txt GNU Public License
*/

/**
* mysql class
* @abstract
*/
final class mysql
{
	public 
		$core,
		$db_ready = false
		;
	
	/**
	* initialize
	* @param object $core
	* @param string $db_host database host
	* @param string $db_u_name user name
	* @param string $db_u_pass user password
	* @param string $db_name database name
	*/
	function __construct($core, $db_host, $db_u_name, $db_u_pass, $db_name)
	{
		// reference to the core object
		$this->core = $core;

		// connection
		if ( ! $this->db_ready = $this->connect(
											$db_host,
											$db_u_name,
											$db_u_pass,
											$db_name
										))
		{
			// no connection
			$this->core->log('DB error : cannot connect, ' . mysql_errno() . mysql_error(), 'error_log');
		}	
	}
	
	/**
	* connect to the database
	* @param string $host database host
	* @param string $user user name
	* @param string $password user password
	* @param string $db database name
	*/
	private function connect($host, $user, $password, $db )
	{
		// mysql connection
		$this->db_link = mysql_connect($host, $user, $password) or $this->core->log('DB error : ' . mysql_errno() . mysql_error(), 'error_log');
		if (!$this->db_link)
		{
			$this->return = false;
		}
		
		// database selection
		$db_selected = mysql_select_db($db, $this->db_link) or $this->core->log('DB error : ' . mysql_errno() . mysql_error(), 'error_log');
		if (!$db_selected)
		{
			return $this->return = false;
		}
		
		return true;
	}
	
	/**
	* escape vars
	* @param mixed
	*/
	public function esc ($v)
	{
		# recursive escaping
		return (is_array($v)) ? array_map(array($this, 'esc'), $v) : mysql_real_escape_string($v);
	}
	
	/**
	* do the query
	* @param string $query SQL
	* @param string $file file of request
	* @lijn int $lijn
	*/	
	public function sql ($query, $file, $lijn)
	{		
		# send the query
		$result = mysql_query($query) or $this->core->log('DB error : (' . $lijn . ', ' . $file . ') ' . mysql_errno() . mysql_error(), 'error_log');
				
		# dit verwijderd spaties & enters
		# vb : sql(' SELECT ...
		# sql('
		#		SELECT ...
		$query = trim(preg_replace('/\s+/', ' ', $query));
		
		# verwerk de resultaten
		if ( $result )
		{
			# select, show en explain geven "array" resource terug
			if ( preg_match('/^(SELECT|SHOW|EXPLAIN)/i', $query) )
			{
				# linux (correctly) sets 0 results as result, however we like to get a fail on the query then ;)
				if ( mysql_num_rows( $result == 0 )
				{
					return $this->result = false;
				}
				
				# fix ; in het geval deze al gezet is tijdens deze pagina.
				$this->result = array();
				
				# lees de resultaten uit
				while ( $d = mysql_fetch_array($result, MYSQL_ASSOC) )
				{
					$this->result[] = $d;
				}
				
				# clean up the request
				mysql_free_result( $result );
				
				if ( preg_match('/LIMIT\s?\r?\s*?(\s?0\s?,\s?1\s?|\s?1\s?);/i', $query) )
				{
					$this->result = ( isset($this->result['0']) ) ? $this->result['0'] : false;
				}

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
					return $this->result = mysql_affected_rows();
				}
			}
		}
		else
		{
			// result not valid
			$this->result = false;
			
			# save it for the developer :)
			$this->core->log('DB error : (' . $lijn . ', ' . $file . '), no valid result, ' . htmlspecialchars($query), 'error_log');
		}
	}
	
	/**
	* close connection when active
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
