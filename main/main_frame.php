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
		$auto_load 				= false,
		$allow_structure_cache 	= true,
		$mods 					= array()
		;

	/**
	* initialize
	* @param array
	*/
	function __construct($page_info = array())
	{
		// reference
		$core = $this;
		
		// undo magic_quotes
		$this->escape_input();
		
		// load configuration, for page, and full framework
		$this->load_config($page_info);
		
		// load core modules
		$this->core_handeling( 'construct' );
	}
	
	/**
	* when auto_load is set to true, all available modules will be loaded
	*/	
	public function auto_load_on ()
	{
		$naf = array('.svn', '.', '..');
		$this->auto_load = true;
		
		// use cache file, if there is one
		$this->mods = (file_exists($this->path . 'main/_modules/mod.cache')) ? unserialize(file_get_contents($this->path . 'main/_modules/mod.cache')) : $this->get_all_modules();
		
		// load all modules 
		$this->load_modules($this->mods);
	}
	
	/**
	* pseudo destructor
	*/
	public function close ()
	{
		# referentie
		$core = $this;
		
		# destroying user defined modules
		$this->destroy_modules();
		
		# destroying core handeling modules
		$this->core_handeling( 'destruct' );
	}
	
	/**
	* load modules from script level
	* @param mixed $modules
	*/
	public function load_modules ( $modules )
	{
		// auto load has been set
		if ( $this->auto_load )
		{
			foreach ( $this->mods as $module )
			{
				$this->module_handeling ( $module, 'construct' );
			}
		}
		// not all modules have to be loaded
		else
		{
			# more then one module load_modules( array('module1', 'module2') );
			if ( is_array($modules) )
			{
				foreach ( $modules as $module )
				{
					$this->mods[] = $module;
					$this->module_handeling ( $module, 'construct' );
				}
			}
			# only 1 module string like given load_modules('module1');
			else
			{
				$this->mods[] = $modules;
				$this->module_handeling ( $modules, 'construct' );
			}
		}
	}
	
	/**
	* unload modules from script level
	*/
	private function destroy_modules ( )
	{
		foreach ( $this->mods as $module )
		{
			$this->module_handeling ( $module, 'destruct' );
		}
	}
	
	/**
	* get all available modules
	* @return array
	*/
	private function get_all_modules ()
	{
		// open dir
		if ( $handle = opendir($this->path . 'main/_modules/') )
		{
			//
			while (false !== ($dir = readdir($handle))) {
				if ( is_dir( $this->path . 'main/_modules/' . $dir ) && !in_array($dir, $naf)) {
					$mods[] = $dir;
				}
			}
			closedir($handle);
		}
		
		// save to cache files
		if ( $this->allow_structure_cache )
		{
			// open / make file
			$file = fopen($this->path . 'main/_modules/mod.cache', "w");
			
			// write to file, serialize is slow and cpu heavy process.
			fputs($file, serialize($mods));
			
			// close file
			fclose($file);
		}
		return (!empty($mods)) ? $mods : array();
	}
	
	/**
	* handle modules
	* @param string $file
	* @param string $mode
	*/
	private function module_handeling( $module, $mode )
	{
		if ( is_file($this->path . 'main/_modules/' . $module . '/boot.php') )
		{
			$core = $this;
			$module_path = $this->path . 'main/_modules/' . $module . '/';
			include($this->path . 'main/_modules/' . $module . '/boot.php');
		}
	}
	
	/**
	* load modules who are considerd 'important'
	* @param string $file
	* @param string $mode
	*/
	private function core_handeling( $mode )
	{
		# core modules should be set in _config.php
		if ( is_array($this->_core_modules[$mode]) )
		{
			# the reference
			$core = $this;
			foreach ($this->_core_modules[$mode] as $module)
			{
				$module_path = $this->path . 'main/_modules/' . $module . '/';
				include($this->path . 'main/_modules/' . $module . '/boot.php');
			}
		}
	}
	
	/**
	* load config
	* @param array $page_info
	*/
	private function load_config ($page_info) 
	{
		# only var we really need
		$this->path = (isset ($page_info['PATH'])) ? $page_info['PATH'] : "./";

		if ( is_array($page_info) && !empty($page_info) )
		{
			# php 5 want all objects declared
			$this->_page = new stdClass();
			
			foreach ( $page_info as $k => $v )
			{
				# already got that one
				if ( $k != 'PATH' )
					$this->_page->{$k} = $v;
			}
		}
		
		# add it
		include($this->path . 'main/_config.php');
			
		# main config array
		if ( isset($config) )
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
	* register a handeling
	* @param string $msg
	* @param string $file
	*/
	function log($msg , $file = 'admin_log')
	{
		// try to open or make it, and set pointer to end of file
		$fp = fopen($this->path . 'main/_logs/' . $file . '.log', 'a+');
		
		// write to file
		fwrite($fp, time() . (string) $msg . "\r\n");
		
		// close file
		fclose($fp);
	}
}

?>