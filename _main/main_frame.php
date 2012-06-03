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
		$known_modules			= array(),
		$loaded_modules			= array(),
		$core_modules			= array()
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
		
		# load config file in buffer
		$this->load_config();
		
		# bootstrap core modules
		$this->load_core();
		
		# undo magic_quotes
		$this->escape_input();
		
		# parse page info
		$this->handle_page_info($page_info);
		
		# search all valid modules
		$this->known_modules = $this->get_all_modules();
	}
	
	/**
	* load modules
	* @param mixed $modules
	*/
	public function load_modules ( $modules )
	{
		$modules = (!is_array($modules)) ? array($modules) : $modules;
	
		foreach ( $modules as $module )
		{
			if ( in_array($module, $this->known_modules))
			{
				$this->loaded_modules[] = $modules;
				$this->module_handeling ( $modules, 'construct' );
			}
			else
			{
				$this->log('Unknown module requested : '. $module, 'error_log');
			}
		}

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
	* get all available modules
	* @return array
	*/
	private function get_all_modules ()
	{
		# read all modules, if valid module, add to loadable list
		if ( $handle = opendir($this->path . '_modules/') )
		{
			while (false !== ($dir = readdir($handle)))
			{
				if ( 
						is_dir( $this->path . '_modules/' . $dir ) && 
						!in_array($dir, $this->ignore_dirs) &&
						is_file( $this->path . '_modules/' . $dir . '/boot.php' )
						) 
				{
					$mods[] = $dir;
				}
			}
			closedir($handle);
		}

		return (!isset($mods)) ? $mods : array();
	}
	
	/**
	* handle modules
	* @param string $file
	* @param string $mode
	*/
	private function module_handeling( $module, $mode, $is_core = false )
	{
		if ( $is_core )
		{
			if ( is_file($this->path . '_main/core/' . $module . '/boot.php') )
			{
				$core = $this;
				$module_path = $this->path . '_main/core/' . $module . '/';
				include($module_path . 'boot.php');
			}
			else
			{
				$this->log('unknown core module loaded : ' . $module , 'error_log');
			}
		}
		else
		{
			if ( is_file($this->path . '_modules/' . $module . '/boot.php') )
			{
				$core = $this;
				$module_path = $this->path . '_modules/' . $module . '/';
				include($module_path . 'boot.php');
			}
			else
			{
				$this->log('unknown module loaded : ' . $module , 'error_log');
			}
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
		}
	}
	
	/**
	* load config
	*/
	private function load_config () 
	{
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
	* load core modules
	*/	
	private function load_core()
	{
		$core_modules = array();
		
		// load core modules
		$mode = "core";
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
					include ( $this->path . '_main/core/' . $dir . '/boot.php' );
					$core_modules[$load_level] = $dir;
				}
			}
			closedir($handle);
		}
		
		// load based on load_level
		$mode = "construct";
		$this->core_modules = sort($core_modules);
		
		foreach ($this->core_modules as $core_module)
		{
			$core = $this;
			$module_path = $this->path . '_main/core/' . $core_module . '/';
			include ( $module_path . 'boot.php' );
		}
	}
}

?>