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
final class user
{
	#	Levels :
	#	banned = -1, guest = 0, user = 1, moderator = 2, admin = 3, developer = 4
	
	public
		$core,
		$salt = 'blaat123'
		;
	
	/**
	* initialize
	* @param object $core
	*/
	function __construct($core)
	{
		// reference to the core object
		$this->core = $core;
			
		# geen gebruikers info gevonden
		# default geeft sowieso bool terug
		# gast defineren
		if ( $core->session->get('user_id') === FALSE )
		{
			# gebruiker 'aanmaken' als gast
			$core->session->put(array(
										'user_id'       => 0,
										'user_name' 	=> 'Gast',
										'user_level'    => 0
										));
		}
	}
	
	/**
	* set salt to non default value
	* @param string $salt
	*/
	public function set_salt ($salt)
	{
		$this->salt = $salt;
	}
	
	/**
	* registrate new user
	* @param string $user
	* @param string $password
	* @param string $email
	*/
	public function registration ($user, $password, $email, $level = 1, $admin_register = false)
	{
		# check user already exist
		$this->core->db->sql('SELECT username FROM `user_data` WHERE `username` = "' . $this->core->db->esc($user) . '" LIMIT 1', __FILE__, __LINE__ );
		
		if ( count($this->core->db->result) == 0 )
		{
			$pass_hash = $this->make_pass_hash($user, $password);
			
			$this->core->db->sql("INSERT INTO 
				`user_data` (
						`username`,
						`level`,
						`pass_hash`,
						`email`,
						`reg_date`
						) VALUES (
						'" . $this->core->db->esc($user) . "', 
						" . $level . ", 
						'" . $pass_hash . "', 
						'" . $this->core->db->esc($email) . "',
						NOW()
						);",
						__FILE__, __LINE__);
						
			if ( $insert_id = $this->core->db->result )
			{
				if ( $admin_register == false )
				{
					if ( !$this->core->user->login ($user, $password) )
					{
						return false;
					}
				}
				return $insert_id;
			}
		}
		else
		{
			return false;
		}
		return false;
	}
	
	/**
	* user login
	* @param string $user
	* @param string $password
	*/
	public function login($user, $password)
	{
		# gebruiker is nog niet gekend
		# default geeft sowieso bool terug
		# als hij gast is
		if ( $this->core->session->get('user_level') !== FALSE )
		{
			# paswoord maken
			$pass_hash = $this->make_pass_hash($user, $password);
			$this->core->db->sql('SELECT id, username, level FROM `user_data` WHERE `username` = "' . $user . '" AND `pass_hash` = "' . $pass_hash . '" LIMIT 1;', __FILE__, __LINE__);

			# gebruiker ophalen
			# lazy evaluation (?)
			if ( isset($this->core->db->resul) && $r = $this->core->db->result )
			{
				# user is found, lets set up his data
				$this->core->session->put(array(
					'user_id'       	=> $r['id'],
					'user_name' 		=> $r['username'],
					'user_level'     	=> $r['level']
					));
				return true;
			}
			
			# user not found
			return false;
		}
		# user was logged in
		return false;
	}
	
	/**
	* user logout
	* @param string $name
	* @param string $password
	*/
	public function logout ()
	{	
		# alle inhoud verwijderen
		$this->core->session->reset();
		
		# opslaan
		$this->core->session->end();
	}
	
	/**
	* is user logged in
	* @return bool
	*/
	public function is_logged()
	{
		# mss een andere manier ?
		return ( $this->core->session->get('user_level') >= 1) ? true : false;
	}

	/**
	* what level is user
	* @return int
	*/
	public function is_level($level)
	{
		return ( $this->core->session->get('user_level') >= $level ) ? true : false;
	}
	
	/**
	* get user id
	* @return int
	*/
	public function get_user_id()
	{
		return $this->core->session->get('user_id');
	}
	
	/**
	* get username from id
	* @param int $id
	* @return string
	*/
	public function id_to_user($id)
	{
		$this->core->db->sql('SELECT username FROM `user_data` WHERE `id` = "' . $id . '" LIMIT 1;', __FILE__, __LINE__);
		return ( isset($this->core->db->result) ) ? $this->core->db->result['username'] : "gast" ;		
	}
	
	/**
	* is user logged in
	* @param string $user
	* @param string $password
	* @return string
	*/
	private function make_pass_hash ($user, $password)
	{
		return hash('sha256', $this->salt . strtolower($user) . $password);
	}
	
	/**
	* edit user info
	* @param string $user
	* @return bool
	*/
	public function edit_user_info ( $user_id, $user, $password, $email, $level, $extra_values = array())
	{
		if ( isset($password) && !isset($user) )
		{
			return false;
		}
		
		if ( !empty ($extra_values) )
		{
			$extra_data = '';
			foreach ($extra_value as $key => $value)
			{
				$extra_data .= '`' . $key . '` = "' . $value . '",';
			}
		}
		else
		{
			$extra_data = '';
		}
		
		$pass_hash = $this->make_pass_hash ( $user, $password );
		$this->core->db->sql ('
								UPDATE 
									`user_data`
								SET
									' . (isset ($user) ? '`username`= "' . mysql_real_escape_string($user) . '",' : '')  . '
									' . (isset ($password) ? '`pass_hash`= "' . mysql_real_escape_string($pass_hash) . '",' : '')  . '
									' . (isset ($email) ? '`email`= "' . mysql_real_escape_string($email) . '",' : '')  . '
									' . (isset ($level) ? '`level` = ' . $level . ',' : '')  . '
									' . $extra_data .'
									`edit_date` = NOW()
								WHERE
									user_data.id = ' . $user_id . '
								',
							__FILE__,
							__LINE__
								);
		return true;
		
	}
	
	/**
	* show all users
	* @return array
	*/
	public function show_all_users ( $limit = false )
	{
		if ( $limit )
		{
			return $this->core->db->sql('
								SELECT 
									user_data.id,
									user_data.username, 
									user_data.level, 
									user_data.reg_date, 
									user_data.last_login_date 
								FROM 
									user_data 
								LIMIT 0, ' . $limit . ';'
								, __FILE__, __LINE__);
		}
		else
		{
			return $this->core->db->sql('
								SELECT 
									user_data.id, 
									user_data.username, 
									user_data.level, 
									user_data.reg_date, 
									user_data.last_login_date 
								FROM 
									user_data 
								;'
								, __FILE__, __LINE__);
		}
	}
}

?>