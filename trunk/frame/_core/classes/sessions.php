<?php
#######################
#	sessions.php
#   Svenn D'Hert
########################

class session
{
	public
			$core,
			$id,
			$lifeTime = 3600, # 1 houre
			$contents = array()
			;
			
	private 
			$hash
			;
	
	function __construct($core)
	{
		# ref
		$this->core = $core;

		# make session
		session_start();
	
		# fetch browser info
		$userIP = !empty($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : ( !empty($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'] );
		$userAgent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : 'unknown';
		
		# unique for every user
		$this->hash = sha1($userIP . $userAgent . $_SERVER['SERVER_ADDR']);
		
		# delete old sessions
		$core->db->sql('
			DELETE
			FROM `user_sessions`
			WHERE
				`date_expire` <= NOW()
		;',__FILE__, __LINE__);
		
		# fetch cookie
		if ( isset($core->COOKIE_raw['core_session']) )
		{
			# cast
			$this->id = ( int ) $core->COOKIE_raw['core_session'];
		}
		
		# cookie is set ?
		if ( $this->id )
		{
			# get info from table
			$core->db->sql('SELECT `contents` FROM `user_sessions` WHERE `id` = ' . $this->id . ' AND	`hash` = "' . $this->hash . '" LIMIT 1;', __FILE__, __LINE__);

			if ( $r = $core->db->result_output )
			{
				# extract saved data from table
				$this->contents = unserialize($r[0]['contents']);
				
				if ( !is_array($this->contents) ) 
				{
					$this->contents = array($this->contents);
				}
			}
		}
		
		# no cookie set or no data found in database (to long ago)
		if ( !$this->id || !$r )
		{
			# nieuwe sessie maken
			$core->db->sql('
				INSERT
				INTO `user_sessions` 
					(
					`hash`,
					`contents`,
					`date`,
					`date_expire`
					)
				VALUES (
					"' . $this->hash . '",
					"' . $this->core->db->esc(serialize($this->contents)) . '",
					NOW(),
					DATE_ADD(NOW(), INTERVAL ' . ( int ) $this->lifeTime . ' SECOND)
					)
				ON DUPLICATE KEY UPDATE
					`contents`     = "' . $this->core->db->esc(serialize($this->contents)) . '",
					`date`        = NOW(),
					`date_expire` = DATE_ADD(NOW(), INTERVAL ' . ( int ) $this->lifeTime . ' SECOND)
				;',__FILE__, __LINE__);
				
			# insert geeft mysql__insert_id
			$this->id = $core->db->result_output;
		}

	}
	
	# get a item from array
	function get($k, $default = FALSE)
	{
		return isset($this->contents[$k]) ? $this->contents[$k] : $default;
	}
	
	# set item in array
	function put($k, $v = FALSE)
	{
		if ( is_array($k) )
		{
			foreach ( $k as $k2 => $v2 )
			{
				$this->contents[$k2] = $v2;
			}
		}
		else
		{
			$this->contents[$k] = $v;
		}
	}
	
	# clean content
	function reset()
	{
		$this->contents = array();
	}

	# save content to db
	function end()
	{
		# update database
		$this->core->db->sql('UPDATE `user_sessions` SET `contents` = "' . $this->core->db->esc(serialize($this->contents)) . '", `date_expire` = DATE_ADD(NOW(), INTERVAL ' . ( int ) $this->lifeTime . ' SECOND) WHERE `id` = ' . $this->id . ' LIMIT 1 ;',__FILE__, __LINE__);

		# set cookie
		# fix, this seems to bug a few things
		if ( !headers_sent() )
		{
			setcookie('core_session', $this->id, time() + $this->lifeTime, $this->core->absPath);
		}
		else
		{
			$this->core->error(1, "header is al verzonden", __FILE__, __LINE__);
		}
	}
}

?>