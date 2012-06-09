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
		$unload_modules			= array()
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
		# save execution path
		$this->path = $path;
		
		# check if no hard exit file has been set
		$this->check_access();

		# bootstrap core
		$this->load_core();
			
		# parse page info
		$this->handle_page_info($page_info);
		
	}
	
	/**
	* load modules
	* @param mixed $modules
	*/
	public function load_modules ( $modules )
	{
		$modules = (!is_array($modules)) ? array($modules) : $modules;
	
		# referentie
		$core = $this;
			
		foreach ( $modules as $module )
		{
			# check if the module exist & valid
			# load into buffer
			if ( 
				is_dir( $this->path . '_modules/' . $module ) && 
				!in_array($module, $this->ignore_dirs) &&
				is_file( $this->path . '_modules/' . $module . '/boot.php' )
				) 
			{
				$core = $this;
				include ( $this->path . '_modules/' . $module . '/boot.php' );
				$need_to_loads[] = array(
						'module_name' 	=> $module,
						'module_load'	=> (isset($settings['load_hook'])? $settings['load_hook'] : '999'),
						'module_unload'	=> (isset($settings['unload_hook'])? $settings['unload_hook'] : '999') 
						);
			}
			else
			{
				die('Unknown module requested : '. $module);
			}
		}
				
		foreach ($need_to_loads as $key => $value)
		{
			$module_load[$value['module_load']] = $value['module_name'];
			$this->unload_modules[$value['module_unload']] = $value['module_name'];
		}
		
		ksort($module_load, SORT_NUMERIC);
		
		foreach ($module_load as $k => $load_module)
		{
			$this->module_handeling ( $load_module, 'construct' );
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
		
		ksort($this->unload_modules, SORT_NUMERIC);
		
		foreach ($this->unload_modules as $k => $unload_module)
		{
			$this->module_handeling ( $unload_module, 'destruct' );
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
	* function is able to fail if nothing is given
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
		
		return true;
	}
}