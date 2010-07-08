<?php
#######################
#	main_frame.php
#   Svenn D'Hert
#######################

final class core
{
	public
		$path,
		$plugs = array()
		;

	function __construct($page_info = array())
	{
		// reference
		$core = $this;
		
		// set error handeling (php4)
		set_error_handler(array($core, 'error'));
		
		// load configuration, for page, and full framework
		$this->load_config($page_info);
		
		// load core modules
		$this->core_handeling( 'construct' );
	}
	
	# pseudo destructor
	public function close ()
	{
		# referentie
		$core = $this;
		
		# destroying user defined modules
		$this->destroy_modules();
		
		# destroying core handeling modules
		$this->core_handeling( 'destruct' );
	}
	
	# script level module loader
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
	
	# script level module destructor
	private function destroy_modules ( )
	{
		foreach ( $this->plugs as $module )
		{
			$this->module_handeling ( $module, 'destruct' );
		}
	}
	
	# loading the modules by script defined
	# does only load at pages where needed
	private function module_handeling( $file, $mode )
	{
		if ( is_file($this->path . 'frame/_modules/' . $file . '.php') )
		{
			$core = $this;
			if ( $mode == 'construct' )
			{
				include($this->path . 'frame/_modules/classes/' . $file . '.php');
			}
			include($this->path . 'frame/_modules/' . $file. '.php');
		}
	}
	
	# loading the main core, on each page
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
				if ( $mode == 'construct' )
				{
					include($this->path . 'frame/_core/classes/' . $module . '.php');
				}
				include($this->path . 'frame/_core/' . $module . '.php');
			}
		}
	}
	
	# load config file
	private function load_config ($page_info) 
	{
		# only var we really need
		$this->path = (isset ($page_info['PATH'])) ? $page_info['PATH'] : "./";

		if ( is_array($page_info) )
		{
			foreach ( $page_info as $v => $k )
			{
				$this->_page->{$k} = $v;
			}
		}
		
		# add it
		include($this->path . '_config.php');
			
		# main config array
		if ( isset($config) )
		{
			# put in as vars in frame
			foreach ( $config as $k => $v )
			{
				$this->{$k} = $v;
			}
			
			# make config unaccesable
			unset($config);
		}
	}

	// error handeling
	// http://www.phptuts.nl/view/35/6/
	// E_ERROR, E_PARSE cannot be fetched :(
	public static function error($i_err_level, $err_msg, $err_file = '', $err_line = '', $err_context = array())
	{
		$level_name = array (
						1		=> 'User defined', # hehe i'm lazy. really.
						2		=> 'Warning',
						8		=> 'Notice',
						256		=> 'Fatal error',
						512		=> 'Warning',
						1024	=> 'Notice',
						1062	=> 'Database error'
						);
		
		$s_err_level = ( isset($level_name[$i_err_level]) ) ? $level_name[$i_err_level] : 'Unknown error';
	
		# headers verzenden
		if ( !headers_sent() )
		{
			header('HTTP/1.1 503 Service Temporarily Unavailable');
		}
		
		echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
				<html>
				<head><title>A Problem has been found.</title>
				<style>
					#frame_error body {color: #000;font-family: monospace;font-size: 12px;}
					#frame_error div {color: #300;background: #FFC;border: 1px solid #DD7;padding: 1em 2em;}
					#frame_error a:link, #frame_error a:hover, #frame_error a:active, #frame_error a:visited {color: #AA4;}
				</style>
				</head>
				<body>
					<div><p>
							Oops! An error occured. <br/> message : '. $err_msg.'<br/> error : '. $s_err_level .'<br/> line : '. $err_line .' ('.$err_file.')
					</p></div>
				</body>
				</html>
			';
			exit;
	}
}

?>