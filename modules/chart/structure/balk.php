<?php
/**
* @package SoFramwork
* @copyright 2010 Svenn D'Hert
* @license http://www.gnu.org/licenses/gpl-2.0.txt GNU Public License
* @note :  		The default font is set verdana.ttf. A font that is supported by 99.34% of the windows machines. (wiki quote)
*				However due copyright restrictions, its not allowed to pack this in this framework.
*/

/**
* makes balk chart class
* @abstract
*/
final class balk {

	# default settings
	public
			$tussenstappen = 10, # aantal tussenstappen
			$data = array(),
			$hoogte = 400,
			$breedte = 800,
			$bar_space = 50,
			$rand = 50,
			$font = 'main/_modules/chart/verdana.ttf',
			$font_size = 10,
			$default_color = array(
									'background' 			=> 'white',
									'raster'	 			=> 'grey',
									'text'					=> 'black',
									'box'					=> 'black',
									'grafiek'				=> 'alfa',
									'grafiek_achtergrond'	=> 'brown',
								)
			;
	
	private 
			$img
			;
	
	/**
	* initialize
	* @param object
	* @param string
	*/
	public function __construct ( $chart_core, $path )
	{
		$this->chart_core = $chart_core;
		$this->path = $path;
	}
	
	# setter function
	# as this is a not so much used class I can use this slower way of setting value's
	public function __set ($name, $value)
	{
		$this->{$name} = $value; 
	}
	
	# the caller
	# direct output is not really supported (source is not optimalised for this)
	# additional save methods could be added (jpg, gif, ...)
	public function save_to_file ( $filename )
	{
		if (!empty( $this->data ) )
		{
			# create graf
			$this->create_image();
			
			# save to a file
			imagepng($this->img, $this->path . $filename . '.png' );
			
			# remove file from server
			imagedestroy($this->img);
			
			return true;
		}
		return false;
	}

	# make colors (this might be used somewhere else and therfore splitted
	# i din't find a proper way to move $colors from imagecolorallocate work for a revision
	private function alloc_color ()
	{
		$color = array(
																# 	R 	 B	   G
				'white' 		=> imagecolorallocate($this->img, 0xFF,	0xFF, 0xFF),
				'black' 		=> imagecolorallocate($this->img, 0x00, 0x00, 0x00),
				'grey' 			=> imagecolorallocate($this->img, 0xE6, 0xE6, 0xE6), 
				'red' 			=> imagecolorallocate($this->img, 0xcc, 0x00, 0x33),//CC­00­33
				'blue' 			=> imagecolorallocate($this->img, 0x00, 0x00, 0xA3),
				'green' 		=> imagecolorallocate($this->img, 0x00, 0x99, 0x00), 
				'orange'		=> imagecolorallocate($this->img, 0xFF, 0x99, 0x00),
				'yellow'		=> imagecolorallocate($this->img, 0xFF, 0xFF, 0x99), #light yellow
				'purple' 		=> imagecolorallocate($this->img, 0x99, 0x33, 0x99),
				'brown' 		=> imagecolorallocate($this->img, 0xCC, 0x66, 0x33),
				'alfa'			=> imagecolorallocate($this->img, 0xFF, 0x99, 0x00),//ff9900
				);
		foreach ( $color as $kleur => $data)
		{
			$this->{$kleur} = $data;
		}
	}
	
	# main logic
	private function create_image ()
	{
		# create the image
		$this->img = imagecreatetruecolor($this->breedte, $this->hoogte);
		
		# set a the use-able colors
		$this->alloc_color();
		
		# grafiek hoogte (werkbaar veld)
		$grafiek_hoogte = $this->hoogte - $this->rand * 2;
		
		# grafiek breedte (werkbaar veld)
		$grafiek_breedte = $this->breedte - $this->rand * 2;
		
		# 
		$total_bars = count($this->data);
		
		# bar
		$bar_space = ($this->bar_space == -1) ? ($grafiek_breedte / count($this->data) - 15 ) : $this->bar_space;
		
		# grote van 'lege ruimte'
		# werkbare ruimte - ( aantal plaatsen * ruimte ) / aantal plaatsen + 1
		$gap = ($grafiek_breedte - ($total_bars * $bar_space) ) / ($total_bars + 1);
			
		# set the background color
		imagefill( $this->img, 0, 0, $this->white);
		
		// grafiek achtergrond
		imagefilledrectangle($this->img, $this->rand,$this->rand,$this->breedte-1-$this->rand,$this->hoogte-1-$this->rand, $this->white);

		// klopt
		$ratio = $grafiek_hoogte / max($this->data);
		
		// klopt
		$horizontal_gap = $grafiek_hoogte / $this->tussenstappen;

		$temp_d = '';
		for( $i = 1; $i <= $this->tussenstappen; $i++)
		{
			$y = $this->hoogte - $this->rand - $horizontal_gap * $i;
			
			imageline(
						$this->img,
						$this->rand, 
						$y,
						$this->breedte - $this->rand,
						$y,
						$this->{$this->default_color['raster']}
						);
						
			$v = intval($horizontal_gap * $i /$ratio);
			
			// fix for muliple same values :)
			if ((int) $temp_d == (int) $v)
			{
				$v = '';
			}
			else
			{	
				$temp_d = $v ;
			}
			
			$tekst_grootte = imagettfbbox($this->font_size, 0, $this->font, $v);
			
			// tekst op Y-as
			imagettftext(
				$this->img,
				$this->font_size,													// grootte
				0,																	// angle tekst
				($this->rand - ($tekst_grootte['4'] + $tekst_grootte['0']) - 8), 	// left offset
				($y + ((0-$tekst_grootte['5'])/2)), 								// hoogte offset
				$this->{$this->default_color['text']}, 
				$this->font,
				$v
			);
		}
	 
	 
		# ----------- Draw the bars here ------
		for($i=0;$i< $total_bars; $i++)
		{ 
			# ------ Extract key and value pair from the current pointer position
			list($key,$value)= each($this->data); 
			$x1 = $this->rand + $gap + $i * ($gap + $bar_space);
			$x2 = $x1 + $bar_space; 
			$y1 = $this->rand + $grafiek_hoogte - intval($value * $ratio) ;
			$y2 = $this->hoogte - $this->rand;

			$var = ( $this->bar_space == -1 ) ? ($bar_space / 2) : 0;
			if ( $value != 0 )
			{
			
				// voor tekst langer dan 3 tekens.
				if ( strlen($value) > 3 )
				{
					number_format($value, 0, ',', '.');
				}
				
				// in grafiek waardes
				imagettftext(
								$this->img,
								8,	// grootte
								50,	// angle tekst
								$x1 + 7 + $var + 10, // left offset
								$y1 - 4, // hoogte offset
								$this->{$this->default_color['text']}, 
								$this->font,
								$value
							);
	
				// onderschrift
				$tekst_grootte = imagettfbbox($this->font_size, 85, $this->font, $key);

				imagettftext(
								$this->img,
								$this->font_size,	// grootte
								70,	// angle tekst
								$x1 - (($tekst_grootte['4'] + $tekst_grootte['0']) / 2) + 10,
								$this->hoogte - $this->rand + ($tekst_grootte['1'] - $tekst_grootte['3'] ) , // hoogte offset
								$this->{$this->default_color['text']}, 
								$this->font,
								$key
							);
				imagefilledrectangle($this->img,$x1,$y1,$x2,$y2,$this->{$this->default_color['grafiek']});
			}	
		}
	// draw box
		imagerectangle(
				$this->img, 
				$this->rand, 
				$this->rand - $horizontal_gap, 
				($this->breedte - $this->rand), 
				($this->hoogte - $this->rand), 
				$this->{$this->default_color['box']}
				);
	
	}
}
?>