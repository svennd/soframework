<?php
/**
* @package SoFramwork
* @copyright 2010 Svenn D'Hert
* @license http://www.gnu.org/licenses/gpl-2.0.txt GNU Public License
*/

/**
* import class
* @abstract
*/
final class core
{
	public
		$path,
		$plugs = array()
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
		# more then one module load_modules( array('module1', 'module2') );
		if ( is_array($modules) )
		{
			foreach ( $modules as $module )
			{
				$this->plugs[] = $module;
				$this->module_handeling ( $module, 'construct' );
			}
		}
		# only 1 module string like given load_modules('module1');
		else
		{
			$this->plugs[] = $modules;
			$this->module_handeling ( $modules, 'construct' );
		}
	}
	
	/**
	* unload modules from script level
	*/
	private function destroy_modules ( )
	{
		foreach ( $this->plugs as $module )
		{
			$this->module_handeling ( $module, 'destruct' );
		}
	}
	
	/**
	* handle modules
	* @param string $file
	* @param string $mode
	*/
	private function module_handeling( $file, $mode )
	{
		if ( is_file($this->path . 'main/_model/_modules/' . $file . '.php') )
		{
			$core = $this;
			include($this->path . 'main/_model/_modules/' . $file. '.php');
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
				# this method seems way faster then include_once();
				( $mode == 'construct' ) ?  include ($this->path . 'main/_model/_modules/classes/' . $module . '.php') : '';
				include($this->path . 'main/_model/_modules/' . $module . '.php');
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
}

?>