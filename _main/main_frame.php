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
		$path 					= '',
		$module_unload_sequence	= array()
		;

	/**
	* initialize
	* @param array
	*/
	function __construct($page_info = array())
	{
		# save execution path
		$this->path = (isset($page_info['path'])) ? $page_info['path']: '';
		
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
		# make sure its an array (in case only 1 module was needed)
		$modules 		= (!is_array($modules)) ? array($modules) : $modules;
		
		# init some arrays
		$sort_load 		= array();
		$sort_unload 	= array();
		$nosort_load 	= array();
		$nosort_unload 	= array();
		
		# referentie
		$core = $this;
			
		foreach ( $modules as $module )
		{
			# check if the module exist & valid
			# load into buffer
			if ( is_dir( $this->path . '_modules/' . $module ) && is_file( $this->path . '_modules/' . $module . '/boot.php' )) 
			{
				$core = $this;
				$settings = array();
				include ( $this->path . '_modules/' . $module . '/boot.php' );
				
				# check load hook number
				if ( isset($settings['load_hook']))
				{
					if ( isset($sort_load[$settings['load_hook']]) )
					{
						die('duplicate load_hook, loading module :' . $module);
					}
					$sort_load[$settings['load_hook']] = $module;
				}
				# doesn't mather when we load
				else				
				{
					$nosort_load[] = $module;
				}
				
				# check unload hook number
				if ( isset($settings['unload_hook']))
				{
					if ( isset($sort_unload[$settings['unload_hook']]) )
					{
						die('duplicate unload_hook, unloading module :' . $module);
					}
					$sort_unload[$settings['unload_hook']] = $module;
				}
				# doesn't mather when we load
				else				
				{
					$nosort_unload[] = $module;
				}
			}
			else
			{
				die('Unknown module requested : '. $module);
			}
		}
		
		sort($sort_load);
		$load_sequence = array_merge($sort_load, $nosort_load);
				
		rsort($sort_unload);
		$this->module_unload_sequence = array_merge($sort_unload, $nosort_unload);
		
		foreach ($load_sequence as $k => $load_module)
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
		
		foreach ($this->module_unload_sequence as $k => $unload_module)
		{
			$this->module_handeling ( $unload_module, 'destruct' );
		}
	}
	
	/**
	* handle page info array
	* @param array $page_info
	* function is able to fail if nothing is given
	*/
	public function handle_page_info ($page_info_import)
	{
		$this->page_info 	= new stdClass();
	
		# save page info
		if ( is_array($page_info_import) && !empty($page_info_import) )
		{
			# put all info in the new class page
			foreach ( $page_info_import as $k => $v )
			{
				$this->page_info->{$k} = $v;
			}
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
	* load core 
	*/	
	private function load_core()
	{
		$core_modules = array();
		
		# read global config file
		if ( is_file($this->path . '_main/_config.php') )
		{
			include($this->path . '_main/_config.php');

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