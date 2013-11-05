<?php
/**
* @package SoFramwork
* @copyright 2010 Svenn D'Hert
* @license http://www.gnu.org/licenses/gpl-2.0.txt GNU Public License
*/

/**
CREATE TABLE IF NOT EXISTS `user_data` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) DEFAULT NULL,
  `pass_hash` varchar(255) DEFAULT NULL,
  `level` tinyint(1) DEFAULT NULL,
  `email` varchar(40) DEFAULT NULL,
  `reg_date` datetime DEFAULT NULL,
  `edit_date` datetime DEFAULT NULL,
  `last_login_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

*/

/**
* user class
* @abstract
*/
final class user
{
	#	Levels (example) :
	#	banned = -1, guest = 0, user = 1, moderator = 2, admin = 3, developer = 4
	
	public
		$core
		;
	
	private 
		$salt = 'xTf.32G'
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
		
		# if a core salt is set use that. (recommended)
		$this->salt = (isset($this->core->user_salt)) ? $this->core->user_salt : $this->salt;
	}
	
	/**
	* set salt to non default value
	* @param string $salt
	*/
	public function set_salt ($salt)
	{
		$this->salt = $this->salt . $salt;
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
		$this->core->db->sql('SELECT username FROM `user_data` WHERE `username` = "' . $this->core->db->esc($user) . '" LIMIT 1;', __FILE__, __LINE__ );
		
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
						die('user plugin : new user could not login : ' . htmlspecialchars( $user ));	
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
			if ( $this->core->db->result )
			{
				$r = $this->core->db->result;
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
	}
	
	/**
	* is user logged in
	* @return bool
	*/
	public function is_logged()
	{
		# we check user_level
		# we could also check if get(user_name) != guest
		# or get(user_id) != 0
		return ( $this->is_level(1)) ? true : false;
	}

	/**
	* what level is user
	* @return bool
	*/
	public function is_level($level)
	{
		return ( $this->core->session->get('user_level') >= $level ) ? true : false;
	}

	/**
	* is the user banned
	* @return bool
	*/
	public function is_banned($level)
	{
		return ( $this->core->session->get('user_level') == -1 ) ? true : false;
	}
	
	/**
	* get user id
	* @return int
	*/
	public function get_user_id()
	{
		return (int) $this->core->session->get('user_id');
	}
	
	/**
	* get user name
	* @return string
	*/	
	public function get_user_name()
	{
		return $this->core->session->get('user_name');
	}
	
	/**
	* get user level
	* @return int
	*/	
	public function get_user_level()
	{
		return (int) $this->core->session->get('user_level');
	}
	
	/**
	* is user logged in
	* @param string $user
	* @param string $password
	* @return string
	*/
	private function make_pass_hash ($user, $password)
	{
		# this is just an attempt on making password stronger
		# it would be nice if $this->salt (set at beginning of this file)
		# is set to another value
		# optionally we can use user->set_salt('my_extra_salt') on a per-user base
		return hash('sha256', $this->salt . strtolower($user) . $password);
	}
}

?>