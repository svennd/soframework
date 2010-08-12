<?php
/**
* @package SoFramwork
* @copyright 2010 Svenn D'Hert
* @license http://www.gnu.org/licenses/gpl-2.0.txt GNU Public License
*/

// no direct acces
if ( !isset($this) ){ exit('direct_acces_not_allowed'); }

/**
* view class
* @abstract
*/
final class admin
{
	public 
		$core;
		
	private
		$user_is_admin = false;
		;
		
	function __construct ( $core )
	{
		$this->core = $core;
		$admin_level = (int) $core->session->get('user_level');
		if ( $admin_level < 2 )
		{	
			$core->log($core->session->get('user_name') . ' attempted accesing admin modules' ,'error_log');
			die('Hacking attempt.');
		}
		if ( $admin_level > 1 )
		{
			$this->user_is_admin = true;
		}
	}
	
	private function load_admin_level_functions ()
	{
		if (!$this->user_is_admin)
		{
			$core->log('attempt use function load_admin_level_functions' ,'error_log');
			// euh true ? :)
			return true;
		}
		
	}
}
?>