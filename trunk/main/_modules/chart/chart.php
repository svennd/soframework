<?php
/**
* @package SoFramwork
* @copyright 2010 Svenn D'Hert
* @license http://www.gnu.org/licenses/gpl-2.0.txt GNU Public License
*/

/**
* chart class
* @abstract
*/
final class chart
{
	private 
		$path,
		$known_types = array(
								'line' 		=> 'line', 
								'circle' 	=> 'circle_chart',
							)
		;
	
	function __construct ( $core_path )
	{
		$this->path = $core_path;
	}
	
	function load( $type )
	{
		if ( in_array ( $type, $this->known_types) )
		{
			include $this->path . 'structure/' . $this->known_types[$type] . '.php';
		}
		$chart = $this;
		$this->chart = new chart($this);
	}
}
?>