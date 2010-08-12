<?php
/**
* @package SoFramwork
* @copyright 2010 Svenn D'Hert
* @license http://www.gnu.org/licenses/gpl-2.0.txt GNU Public License
*/

/**
* remote execution detector
*/
function remote_execution($v)
{
	if ( is_array ($v) )
	{
		return array_map('remote_execution', $v);
	}
	else
	{
		preg_match('/:\/\//', $v, $url_found);
		if ( !empty( $url_found ) )
		{
			$this->log('detected url in $_ variabele' . addslashes($url_found['0']) ,'error_log');
			if ( $detection_action => 1 )
			{
				die('attempted_hack_detected');
			}
		}
		return $v;
	}
}


// get the $_GET variabele
if ( !empty($_GET) )
{
	$_GET = array_map('remote_execution', $_GET);
}
// get the $_POST variabele
if ( !empty($_POST) )
{
	$_POST = array_map('remote_execution', $_POST);
}
?>