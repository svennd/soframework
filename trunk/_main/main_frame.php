<?php
/**
* @package SoFramwork
* @copyright 2010 Svenn D'Hert
* @license http://www.gnu.org/licenses/gpl-2.0.txt GNU Public License
*/

/**
* structural core class
* @abstract
*/
final class core
{
	public
		$path,
		$modules				= array(),
		$loaded_modules			= array()
		;
		
	private
		$ignore_dirs			= array('.', '..')
		;

	/**
	* initialize
	* @param array
	*/
	function __construct($path = "", $page_info = array())
	{
		$core 		= $this;
		$this->path = $path;
		
		# check if no hard exit file has been set
		$this->check_access();

		# bootstrap core
		$this->load_core();
			
		# parse page info
		$this->handle_page_info($page_info);
		
	}
	
	/**
	* load user_modules
	* @param mixed $user_modules
	*/
	public function load_modules ( $user_modules )
	{
		$user_modules = (!is_array($user_modules)) ? array($user_modules) : $user_modules;
	
		foreach ( $user_modules as $module )
		{
			if ( 
				is_dir( $this->path . '_main/core/' . $module ) && 
				!in_array($module, $this->ignore_dirs) &&
				is_file( $this->path . '_main/core/' . $module . '/boot.php' )
				) 
			{
				# if wanne add load & unload hooks to user_modules add include here
				$this->module[] = array(
						'module_name' 	=> $module,
						'module_type'	=> 'user',
						'module_load'	=> '',
						'module_unload'	=> '' 
						);
			}
			else
			{
				die('Unknown module requested : '. $module);
			}
		}
		
		print_r($this->module);
		array_multisort($this->module, SORT_ASC, SORT_NUMERIC);
		print_r($this->module);
	}
	
	/**
	* pseudo destructor
	* this cause the 'reall' php destructor has problems using global scope
	*/
	public function close ()
	{
		# referentie
		$core = $this;
		
		# unload all modules who are loaded
		foreach ( $this->loaded_modules as $module )
		{
			$this->module_handeling ( $module, 'destruct' );
		}

		foreach ( $this->core_modules as $module )
		{
			$this->module_handeling ( $module, 'destruct', true );
		}

	}

	/**
	* check if script can continue
	*/
	private function check_access ()
	{
		if (file_exists($this->path . '_main/index.exit'))
		{
			$var = file_get_contents($this->path . '_main/index.exit');
				echo htmlspecialchars($var);
			exit;
		}
		return true;
	}

	/**
	* handle modules
	* @param string $file
	* @param string $mode
	*/
	private function module_handeling( $module, $mode )
	{
		if ( is_file($this->path . '_modules/' . $module . '/boot.php') )
		{
			$core = $this;
			$module_path = $this->path . '_modules/' . $module . '/';
			include($module_path . 'boot.php');
		}
		else
		{
			die('unknown core module loaded : ' . $module);
		}
	}
		
	/**
	* handle page info array
	* @param array $page_info
	*/
	private function handle_page_info ($page_info)
	{
		# save page info
		if ( is_array($page_info) && !empty($page_info) )
		{
			# php 5 want all objects declared
			$this->_page = new stdClass();
			
			# put all info in the new class _page
			foreach ( $page_info as $k => $v )
			{
				$this->_page->{$k} = $v;
			}
			return true;
		}
		return false;
	}
	
	/**
	* undo magic_quotes
	*/	
	private function escape_input()
	{
		function undo_magic_quotes($v)
		{
			return is_array($v) ? array_map('undo_magic_quotes', $v) : stripslashes($v);
		}
		 
		if ( function_exists('get_magic_quotes_gpc') && get_magic_quotes_gpc() )
		{
			$_GET    = array_map('undo_magic_quotes', $_GET);
			$_POST   = array_map('undo_magic_quotes', $_POST);
			$_COOKIE = array_map('undo_magic_quotes', $_COOKIE);
		}
	}
	
	/**
	* load core 
	*/	
	private function load_core()
	{
		$core_modules = array();
		
		# add global config file
		if ( is_file($this->path . '_main/_config.php') )
		{
			include($this->path . '_main/_config.php');
		}
		else
		{
			# clearly something is not right
			die('Main config file could not be located.');
		}
		
		# main config array
		if ( isset($config) && is_array($config) )
		{
			# put in as vars in frame
			foreach ( $config as $k => $v )
			{
				$this->{$k} = $v;
			}
		}
		
		# undo magic_quotes
		$this->escape_input();
		
		# load core modules
		if ( $handle = opendir($this->path . '_main/core/') )
		{
			while (false !== ($dir = readdir($handle)))
			{
				if ( 
					is_dir( $this->path . '_main/core/' . $dir ) && 
					!in_array($dir, $this->ignore_dirs) &&
					is_file( $this->path . '_main/core/' . $dir . '/boot.php' )
					) 
				{
					$module_path = $this->path . '_main/core/' . $dir . '/';
					include ( $this->path . '_main/core/' . $dir . '/boot.php' );
					$this->module[] = array(
										'module_name' 	=> $dir,
										'module_type'	=> 'core',
										'module_load'	=> ((isset($settings['load_hook'])) ? $settings['load_hook'] : '' ),
										'module_unload'	=> ((isset($settings['unload_hook'])) ? $settings['unload_hook'] : '' )
										);
				}
			}
			closedir($handle);
		}
	}
}

?>