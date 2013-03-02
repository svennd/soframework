<?php
/**
* @package SoFramwork
* @copyright 2010 Svenn D'Hert
* @license http://www.gnu.org/licenses/gpl-2.0.txt GNU Public License
*/

/**
* form class
* @abstract
*/
final class form
{
	public
			$result = array();
		
	private 
		$regex = array(
						'bool'    => '/^.*$/',
						'empty'   => '/^$/',
						'int'     => '/^-?[0-9]{1,256}$/',
						'string'  => '/^.{1,256}$/',
						'text'    => '//',
						'email'   => '/^[a-z0-9._-]{1,120}@[a-z0-9_-]{1,120}\.[a-z.]{1,6}$/i'
						);
	
	/**
	* initialize
	* @param object $core
	*/
	function __construct($core, $form)
	{
		foreach ($form as $naam => $type)
		{
			if ( isset($_POST[$naam]) )
			{
				$this->result[$naam] = ( preg_match($this->regex[$type], $_POST[$naam]) ) ? $_POST[$naam] : false;
			}
			else
			{
				$this->result[$naam] = "NaN";
			}
		}
	}
}

?>