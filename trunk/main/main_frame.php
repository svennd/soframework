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
		$mods 					= array(),
		$naf 					= array('.svn', '.', '..')
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
		
		// check if no hard stop file has been set
		$this->check_access();
		
		// save all known modules
		$this->known_modules = $this->get_all_modules();
	}
	
	// public loaded
	
	/**
	* load modules
	* @param mixed $modules
	*/
	public function load_modules ( $modules )
	{
		# more then one module load_modules( array('module1', 'module2') );
		if ( is_array($modules) )
		{
			foreach ( $modules as $module )
			{
				if ( in_array($module, $this->known_modules) )
				{
					$this->mods[] = $module;
					$this->module_handeling ( $module, 'construct' );
				}
				else
				{
					$this->log('unknown module requested : '. $module, 'error_log');
				}
			}
		}
		# only 1 module string like given load_modules('module1');
		else
		{
			if ( in_array($module, $this->known_modules))
			{
				$this->mods[] = $modules;
				$this->module_handeling ( $modules, 'construct' );
			}
			else
			{
				$this->log('unknown module requested : '. $module, 'error_log');
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
		foreach ( $this->mods as $module )
		{
			$this->module_handeling ( $module, 'destruct' );
		}
		
	}
	
	// internal loaded	

	/**
	* check if script can continue
	*/
	private function check_access ()
	{
		if (file_exists($this->path . 'main/index.exit'))
		{
			$var = file_get_contents($this->path . 'main/index.exit');
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
		// open dir
		if ( $handle = opendir($this->path . 'main/_modules/') )
		{
			while (false !== ($dir = readdir($handle))) {
				if ( is_dir( $this->path . 'main/_modules/' . $dir ) && !in_array($dir, $this->naf)) {
					$mods[] = $dir;
				}
			}
			closedir($handle);
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
		else
		{
			$this->log('unknown module loaded : ' .(string) $module , 'error_log');
		}
	}
		
	/**
	* load config
	* @param array $page_info
	*/
	private function load_config ($page_info) 
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
		
		# add global config file
		include($this->path . 'main/_config.php');
			
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
	* register a handeling
	* @param string $msg
	* @param string $file
	*/
	function log($msg , $file = 'admin_log')
	{
		$log_file = $this->path . 'main/_temp/_logs/' . $file . '.log';
		
		if ( is_writable($log_file) )
		{
			// try to open or make it, and set pointer to end of file
			$fp = fopen($log_file, 'a+');
			
			// write to file
			fwrite($fp, time() . (string) $msg . "\r\n");
			
			// close file
			fclose($fp);
		}
		# we got errors we cannot save so check what configs says
		# if no config is set, we go for a die();
		else
		{
			# config found & true so hide modus.
			if ( isset($config['production'] && $config['production'] )
			{
				return true;
			}
			else
			{
				return die('Could write to log file. The error loading to this issue was : <br/>' . $msg . '<br/>requested file : ' . $file);
			}
		}
	}
}

?>
