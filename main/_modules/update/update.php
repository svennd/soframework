<?php
/**
* @package SoFramwork
* @copyright 2010 Svenn D'Hert
* @license http://www.gnu.org/licenses/gpl-2.0.txt GNU Public License
*/

/**
* update class
* @abstract
*/
final class update
{

	public
		$core,
		$newest_addons 	= array(),
		$addon_list		= array()
		;
	
	private
		$acces_granted = false,	
		$update_server = 'http://localhost/update.txt'
		;
	
	/**
	* initialize
	* @param object $core
	*/
	function __construct($core)
	{
		// reference to the core object
		$this->core = $core;
			
		if ( isset( $core->db->db_ready ) && $core->db->db_ready )
		{
			if ( $core->user->is_level(3) )
			{
				$this->acces_granted = true;
			}
		}
	}

	function check_new ()
	{
		# checking if our method will work
		if ( ini_get('allow_url_fopen') == false )
		{
			return false;
		}
		
		# get the update file
		$update_list = file( $this->update_server );
		
		foreach ( $update_list as $addon )
		{
			$a = explode('|', $addon);
			$this->newest_addons[$a['0']] = array(
													'name' 			=> $a['0'],
													'version'	 	=> $a['1'],
													'version_name' 	=> $a['2']
												);
		}
		
		# get installed addons
		$installed_addons = $this->core->db->sql("SELECT * FROM `sys_modules`;", __FILE__, __LINE__);
		foreach( $installed_addons as $addon )
		{
			$this->addon_list[] = array (
										'name'					=> $addon['name'],
										'current_version'		=> $addon['version'],
										'current_version_name'	=> $addon['version_name'],
										'latest_version'		=> $this->newest_addons[$addon['name']]['version'],
										'latest_version_name'	=> $this->newest_addons[$addon['name']]['version_name'],
									);
		}
		return $this->addon_list;
	}
}

?>