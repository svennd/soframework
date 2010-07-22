<?php
#######################
#	file	: mysql.php
#   author 	: Svenn D'Hert
#	rev.	: 1
#	f(x)	: mysql handeling
########################

class mysql
{
	public 
		$core,
		$db_ready = false
		;
	
	function __construct($core)
	{
		// reference to the core object
		$this->core = $core;

		// connection
		if ( ! $this->db_ready = $this->connect(
										$this->core->_database['host'], 
										$this->core->_database['username'], 
										$this->core->_database['paswoord'], 
										$this->core->_database['database']
										))
		{
			// no connection
			$this->core->error(mysql_errno(), mysql_error(), __FILE__, __LINE__);
		}
		
		# 'security', this info in not needed anymore.
		unset($this->core->_database);
		
	}
	
	private function connect($host, $user, $password, $db )
	{
		// mysql connection
		$this->db_link = mysql_connect($host, $user, $password) or $this->core->error(mysql_errno(), mysql_error(), __FILE__, __LINE__);
		if (!$this->db_link)
		{
			return false;
		}
		
		// database selection
		$db_selected = mysql_select_db($db, $this->db_link) or $this->core->error(mysql_errno(), mysql_error(), __FILE__, __LINE__);
		if (!$db_selected)
		{
			return false;
		}
		
		return true;
	}
	
	# escape function
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
	
	public function sql ($query, $file, $lijn)
	{		
		# send the query
		$result = mysql_query($query) or $this->core->error(mysql_errno(), mysql_error(), __FILE__, __LINE__);
				
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
				# fix ; in het geval deze al gezet is tijdens deze pagina.
				$this->result_output = array();
				
				# lees de resultaten uit
				while ( $d = mysql_fetch_array($result) )
				{
					$this->result_output[] = $d;
				}
				
				# clean up the request
				mysql_free_result( $result );
				
				# return
				return $this->result_output;
			}
			# delete, insert, (...) geven een bool terug
			else
			{
				if( preg_match('/^INSERT/i', $query) )
				{
					# id van de nieuwe rij
					return $this->result_output = mysql_insert_id();
				}
				else
				{
					# aantal aangepaste rijen, indien 0 failed.
					return $this->result_output =  mysql_affected_rows();
				}
			}
		}
	}
	
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