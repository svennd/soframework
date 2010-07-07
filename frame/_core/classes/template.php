<?php
#######################
#	file	: template.php
#   author 	: Svenn D'Hert
#	rev.	: 1
#	f(x)	: template system
########################

final class template
{
	public 
		$core
		;
	
	protected
		$page
		;
	
	function __construct($core)
	{
		// reference to the core object
		$this->core = $core;
	
	}
	
	// haalt de inhoud van de pagina op
	private function load ($file)
	{
		if(file_exists($this->core->path . "frame/_template/" . $file . ".tpl"))
		{
			return file_get_contents ($this->core->path . "frame/_template/" . $file . ".tpl");
		}
		return false;
	}
	
	// haalt de pagina op en verwerkt de pagina, public
	public function output_page ($page, $vars = array())
	{
		# this is so ugly ... but is needed bcous otherwise sessions are already made while page is already send
		# header already send error...
		$this->page .= (is_array($vars)) ? $this->parse ($vars, $this->load($page)) : $this->load($page);

	}
	
	// verwerk de gegeven variabelen met de inhoud
	private function parse ($vars, $content)
	{
		if(!$content)
		{
			$this->core->error(1, "bestand kon niet worden geladen", __FILE__, __LINE__);
		}
		###################### IF / ELSE ##################
		#
		#					develop :)
		#
		#$found = preg_match_all ('/<!--[\s|]IF[\s|][\(|][\s|](.*?)[\s|](==|!=)[\s|](.*?)[\s|][\)|][\s|]-->(.*?)(<!-- ELSE -->(.*?)<!--[\s|]ENDIF[\s|]-->|<!--[\s|]ENDIF[\s|]-->)/is', $content, $match);
		$found = preg_match_all ('/<!--[\s|]IF[\s|][\(|][\s|]([a-z_]*?)[\s|](==|!=)[\s|]([a-z_1-9]*?)[\s|][\)|][\s|]-->(.*?)(<!-- ELSE -->(.*?)<!--[\s|]ENDIF[\s|]-->|<!--[\s|]ENDIF[\s|]-->)/is', $content, $match);
		if ( !empty($match['0']) )
		{
			$matches_count = count ($match['0']);

			$i = 0;
			while ($i < $matches_count )
			{
				switch ($match['2'][$i])
				{
					case "==":
							$a = isset($vars[$match['1'][$i]]) ? $vars[$match['1'][$i]] : (int) $match['1'][$i];
							$b = isset($vars[$match['3'][$i]]) ? $vars[$match['3'][$i]] : (int) $match['3'][$i];
					
							if ( $a == $b )
							{
								$output = $match['4'][$i];
							}
							else
							{
								$output = ($a = preg_match ('/(<!--[\s|]ELSE[\s|]-->)/', $match['5'][$i])) ? $match['6'][$i]: '';
							}
						break;
					case "!=":
							$a = isset($vars[$match['1'][$i]]) ? $vars[$match['1'][$i]] : (int) $match['1'][$i];
							$b = isset($vars[$match['3'][$i]]) ? $vars[$match['3'][$i]] : (int) $match['3'][$i];
							
							if ( $a != $b )
							{
								$output = $match['4'][$i];
							}
							else
							{
								$output = (preg_match ('/(<!--[\s|]ELSE[\s|]-->)/', $match['5'][$i])) ? $match['6'][$i]: '';
							}
					
						break;
				}
				$pattern = '/<!--[\s|]IF[\s|][\(|][\s|]' . $match['1'][$i] . '[\s|]' . $match['2'][$i] . '[\s|]' . $match['3'][$i] . '[\s|][\)|][\s|]-->(.*?)(<!-- ELSE -->(.*?)<!--[\s|]ENDIF[\s|]-->|<!--[\s|]ENDIF[\s|]-->)/is';
				
				# limit 1 fixes problems with same if template statements, but in worst case it still can swap positions ...
				$content = preg_replace($pattern, $output, $content, 1);
				$i++;
			}
		}
		####################################################
		###################### LOOP ##################
		#
		#
		#
		
		preg_match_all('/<!--\sLOOP\s(.+?)\s-->(.*?)<!--\sLOOP\send\s-->/is', $content, $loop);
		
		$aantal_loops_found = count ($loop['0']);
		$i = 0;

		while ($i < $aantal_loops_found)
		{
			preg_match_all('/{[a-z_]*\.([a-z_]*)}/i', $loop['2'][$i], $request);

			$result = "";
			$panic_switch = 0;
			foreach ( $vars[$loop['1'][$i]] as $element_van_array)
			{
				$temp = $loop['2'][$i];
				foreach ($request['1'] as $opgevraagde_key)
				{	
					if ( $opgevraagde_key == "")
					{
						$temp = str_replace( '{' . $loop['1'][$i] . '.'.$opgevraagde_key . '}', $vars[$loop['1'][$i]][$panic_switch], $temp);
					}
					else
					{
						$temp = str_replace( '{' . $loop['1'][$i] . '.'.$opgevraagde_key . '}', $element_van_array[$opgevraagde_key], $temp);
					}
				}
				$panic_switch++;
				$result .= $temp;
			}

			$content = str_replace($loop['0'][$i], $result, $content);
			$i++;
		}
		// echo "##################################";
		// echo $content;
		// echo "##################################";
		
		
		foreach ($vars as $key => $value)
		{
			if ( !is_array ($value) )
			{
				$content = str_replace('{' . $key . '}', $value, $content);
			}
		}
		return $content;
	}

	public function push_output ()
	{
		return ( !empty($this->page) ) ? $this->page : $this->core->error( 1, "Pagina kon is niet aangemaakt", __FILE__, __LINE__);
	}
}
?>