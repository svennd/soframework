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
final class db_mysql
{
	public 
		$core,
		$db_ready 	= false,
		$result		= false
		;
	
	/**
	* initialize
	* @param object $core
	* @param string $db_host database host
	* @param string $db_u_name user name
	* @param string $db_u_pass user password
	* @param string $db_name database name
	*/
	function __construct($core)
	{
		// reference to the core object
		$this->core = $core;

		// include the config 
		include $core->path . '_modules/database/config.php';

		// connection
		if ( ! $this->db_ready = $this->connect(
											$host,
											$user,
											$password,
											$database
										))
		{
			// no connection
			die('DB error : cannot connect, ' . mysql_errno() . mysql_error());
		}	
	}
		
	/**
	* connect to the database
	* @param string $host database host
	* @param string $user user name
	* @param string $password user password
	* @param string $db database name
	*/
	public function connect($host, $user, $password, $db )
	{
		// mysql connection
		$this->db_link = mysql_connect($host, $user, $password) or die('DB error : ' . mysql_errno() . ' ' . mysql_error());
		if (!$this->db_link)
		{
			return $this->result = false;
		}
		
		// database selection
		$db_selected = mysql_select_db($db, $this->db_link) or die('DB error : ' . mysql_errno() . ' ' . mysql_error());
		if (!$db_selected)
		{
			return $this->result = false;
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
	* @param int $lijn
	* @param string result type
	*/	
	public function sql ($query, $file = 'unkown', $lijn = 'unknown', $method = 'BOTH')
	{		
		# send the query
		$result = mysql_query($query) or die('DB error : (' . $lijn . ', ' . $file . ') ' . mysql_errno() . ' ' . mysql_error() . ' could not execute query, ' . htmlspecialchars($query));
		// print $query;
		
		# dit verwijderd meerdere spaties en vervangt door 1
		# vb : sql('     SELECT     *  FROM   table ... --> sql(' SELECT * FROM table
		$query = trim(preg_replace('/\s+/', ' ', $query));
		
		# verwerk de resultaten
		if ( $result )
		{
			# select, show en explain geven "array" resource terug
			if ( preg_match('/^(SELECT|SHOW|EXPLAIN)/i', $query) )
			{
				# if query returns 0 lines but is valid
				if ( mysql_num_rows( $result ) == 0 )
				{
					return $this->result = false;
				}
				
				# clean out, in case a value have been set before, 
				# but we reached this point anyway.
				$this->result = array();
				
				# result will be array(0 => value, field => value, 1 => value, field => value, 2 => value, field => value);
				if ($method == "BOTH")
				{
					while ( $d = mysql_fetch_array($result, MYSQL_BOTH) )
					{
						$this->result[] = $d;
					}
				}
				# result will be array(0 => value, 1 => value, 2 => value);
				else if ($method == "NUM")
				{
					while ( $d = mysql_fetch_array($result, MYSQL_NUM) )
					{
						$this->result[] = $d;
					}
				}
				# result will be (field => value, field => value, field => value);
				else
				{
					while ( $d = mysql_fetch_array($result, MYSQL_ASSOC) )
					{
						$this->result[] = $d;
					}
				}
				
				# clean up the request
				mysql_free_result( $result );
				
				# in case its a 1 value only return as non-array
				if ( in_array($method, array("NUM", "BOTH")) && preg_match('/LIMIT\s?\r?\s*?(\s?0\s?,\s?1\s?|\s?1\s?);/i', $query) )
				{
					$this->result = ( isset($this->result['0']) ) ? $this->result['0'] : false;
				}
				
				return $this->result;
			}
			# delete, insert, (...) geven een bool/int terug
			else
			{
				# insert : return insert id
				# insert/update : rows adapted
				$this->result = (preg_match('/^INSERT/i', $query)) ? mysql_insert_id() : mysql_affected_rows();
				
				return $this->result;
			}
		}
		else
		{
			// result not valid
			$this->result = false;
			
			# save it for the developer :)
			die('DB error : (' . $lijn . ', ' . $file . '), no valid result, ' . htmlspecialchars($query));
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
