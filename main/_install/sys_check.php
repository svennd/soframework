<?php
/**
* @package SoFramwork
* @copyright 2010 Svenn D'Hert
* @license http://www.gnu.org/licenses/gpl-2.0.txt GNU Public License
*/


$_missing 	= array();
$_found		= array();

/**
* checking files
*/

// check config files

	// install config
	if( !file_exists('./config.php') )
	{
		if ( !file_exists('./config.default.php') )
		{
			$_missing['install_config'] = '/main/_install/config.php is not found';
		}
		else
		{
			$_missing['install_config'] = '/main/_install/config.php is not found, an example can be found at /main/_install/config.default.php';
		}
	}
	else
	{
		$_found['install_config'] = 'install configuration file found.';
	}

	// main_frame config
	if( !file_exists('../_config.php') )
	{
		if ( !file_exists('../_config.default.php') )
		{
			$_missing['main_config'] = '/main/_config.php is not found';
		}
		else
		{
			$_missing['main_config'] = '/main/_config.php is not found, an example can be found at /main/config.default.php';
		}
	}
	else
	{
		$_found['main_config'] = 'main frame configuration file found.';
	}

// check other files

	// main_frame class
	if( !file_exists('../main_frame.php') )
	{
		$_missing['main_frame'] = '/main/main_frame.php is not found, this file is main control structure, please redownload SoFramework';
	}
	else
	{
		$_found['main_frame'] = 'main frame class file found.';
	}

/**
*	checking folders
*/

	if ( !is_dir('../_modules/') )
	{
		$_missing['modules_dir'] = '/main/_modules is not a read-able folder. This should contain all modules. Please redownload SoFramework to work properly.';
	}
	else
	{		
		// check howmuch modules are located inside modules
		if ( $handle = opendir('../_modules/') )
		{
			$modules = 0;
			while (false !== ($dir = readdir($handle)))
			{
				if ( is_dir( '../_modules/' . $dir ) && file_exists('../_modules/' . $dir . '/boot.php') )
				{
					$modules++;
				}
			}
			closedir($handle);
		}
		
		$_found['modules_dir'] = ($modules == 0 ) ? 'modules dir is found. No modules have been found.': ($modules > 1) ? 'modules dir is found, there are ' . $modules . ' modules available.' : 'modules dir is found, there is one module found.';
		
		// check for the needed folder for view module
		if ( is_dir ('../_modules/view/') )
		{
			if ( !is_dir('../_view/') )
			{
				$_missing['view_dir'] = '/main/_view is not a read-able folder. This folder is used by the original view module, who has been found in the modules folder.';
			}
			else
			{
				$_found['view_dir'] = '/main/_view dir has been located, and can be used by view module.';
			}
		}
		

	}
?>