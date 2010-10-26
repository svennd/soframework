<?php
/**
* @package SoFramwork
* @copyright 2010 Svenn D'Hert
* @license http://www.gnu.org/licenses/gpl-2.0.txt GNU Public License
*/

/**
* remote execution detector
*/
function remote_execution($v, $core)
{
	global $detection_action;

	if ( is_array ($v) )
	{
		return array_map('remote_execution', $v);
	}
	else
	{
		preg_match('/:\/\//', $v, $url_found);
		if ( !empty( $url_found ) )
		{
			$core->log('detected url in $_POST or $_GET variabele\n GET :' . implode(',', $_GET) . "\n POST :" . implode(',', $_POST) .'\n' ,'error_log');
			if ( $detection_action >= 1 )
			{
				die('attempted_hack_detected');
			}
		}
		return $v;
	}
}


// If the array argument contains string keys then the returned array will contain string keys if and only if exactly one array is passed. 
// If more than one argument is passed then the returned array always has integer keys. 

// get the $_GET variabele
if ( !empty($_GET) )
{
	// $_GET = array_map('remote_execution', $_GET , array_fill(0 , count($_GET), $core) );
}
// get the $_POST variabele
if ( !empty($_POST) )
{
	// $_POST = array_map('remote_execution', $_POST , array_fill(0 , count($_POST), $core) );
}
?>