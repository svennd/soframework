<?php
/**
* @package SoFramwork
* @copyright 2010 Svenn D'Hert
* @license http://www.gnu.org/licenses/gpl-2.0.txt GNU Public License
*/

/**
CREATE TABLE IF NOT EXISTS `dbcms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `last_edit` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 ;
*/

/**
* dbcms class
* @abstract
*/
final class dbcms
{
	public 
		$core
		;
		
	/**
	* initialize
	* @param object
	*/
	function __construct( $core )
	{
		// reference to the core object
		$this->core = $core;
		
		# check needs
		$this->check_basic_needs();
	}
	
	private function check_basic_needs()
	{
		if ( !isset($this->core->db->db_ready) || $this->core->db->db_ready == false )
		{
			die ('db was not ready or not enabled while trying to use dbcms module.');
		}
		return true;
	}
	
	/**
	* request content from a page
	* @param int $id id from table
	*/
	public function get_page( $id )
	{
		return $this->core->db->sql("SELECT * FROM `dbcms` WHERE `id` = '" . (int) $id . "' LIMIT 1;", __FILE__, __LINE__);
	}
	
	/**
	* add a new page to the database
	* @param string $name
	* @param text $content
	*/
	public function add_page ( $name, $content )
	{
		// cutting name if longer then 255
		if ( strlen($name) > 255 )
		{
			$name = substr($name, 0, 254);
		}
		return $this->core->db->sql("INSERT INTO `dbcms` (name, content) VALUES ('" . $this->core->db->esc($name) . "', '" . $this->core->db->esc($content) . "');", __FILE__, __LINE__);
	}
	
	public function edit_page ( $id, $name, $content )
	{
		// cutting name if longer then 255
		if ( strlen($name) > 255 )
		{
			$name = substr($name, 0, 254);
		}
		return $this->core->db->sql("UPDATE `dbcms` SET `name` = '" . $this->core->db->esc($name) . "',`content` = '" . $this->core->db->esc($content) . "' WHERE `id` = '" . $id . "' LIMIT 1;", __FILE__, __LINE__);
	}
}

?>