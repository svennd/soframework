<?php
/**
* @package SoFramwork
* @copyright 2010 Svenn D'Hert
* @license http://www.gnu.org/licenses/gpl-2.0.txt GNU Public License
* @note :  		The default font is set verdana.ttf. A font that is supported by 99.34% of the windows machines. (wiki quote)
*				However due copyright restrictions, its not allowed to pack this in this framework.
*/

/**
* makes line gfx class
* @abstract
*/
final class lijn_grafiek {

	# default settings
	public
			$tussenstappen = 10, # aantal tussenstappen
			$data = array(),
			$hoogte = 300,
			$breedte = 450,
			$spin = 90,
			$draw_box = true,
			$rand = array(
							'left' 		=> 40,
							'right' 	=> 12,
							'top' 		=> 15,
							'bottom' 	=> 40 
						),
			$font = 'verdana.ttf',
			$default_color = array(
									'background' 	=> 'white',
									'raster'	 	=> 'grey',
									'text'			=> 'black',
									'box'			=> 'black',
									'grafiek'		=> 'brown',
								)
			;
	
	private 
			$img
			;
			
	# setter function
	# as this is a not so much used class I can use this slower way of setting value's
	public function __set ($name, $value)
	{
		$this->{$name} = $value; 
	}
	
	# the caller
	# direct output is not really supported (source is not optimalised for this)
	# additional save methods could be added (jpg, gif, ...)
	public function save_to_file ( $filename)
	{
		if (!empty( $this->data ) )
		{
			# create graf
			$this->create_image();
			
			# save to a file
			imagepng($this->img, 'frame/_cache/'. $filename . '.png' );
			
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
				'red' 			=> imagecolorallocate($this->img, 0xFF, 0x00, 0x00),
				'blue' 			=> imagecolorallocate($this->img, 0x00, 0x00, 0xA3),
				'green' 		=> imagecolorallocate($this->img, 0x33, 0x66, 0x33), 
				'orange'		=> imagecolorallocate($this->img, 0xFF, 0x99, 0x00),
				'yellow'		=> imagecolorallocate($this->img, 0xFF, 0xFF, 0x99), #light yellow
				'purple' 		=> imagecolorallocate($this->img, 0x99, 0x33, 0x99),
				'brown' 		=> imagecolorallocate($this->img, 0xCC, 0x66, 0x33)
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
		
		# set the background color
		imagefill( $this->img, 0, 0, $this->{$this->default_color['background']});
		
		# grafiek specs - the margins
		$grafiek_hoogte = $this->hoogte - ($this->rand['top'] + $this->rand['bottom']);
		$grafiek_breedte = $this->breedte - ($this->rand['left'] + $this->rand['right']);
		
		# the smallest value in the data array
		$min_data = min($this->data);
		
		# scaling
		# automatic scaling
		if ( !isset($this->fixed['min']) && !isset($this->fixed['max']) )
		{
			# draw lines y_stap steps
			$y_stap = ceil( (max($this->data) - $min_data)/ $this->tussenstappen);
			
			# where are we going to set the limit ?
			$y_max =  $min_data + $this->tussenstappen * $y_stap;
		}
		# manual scaling
		else
		{
			# homuch steps between max/min
			$y_stap = ceil( ($this->fixed['max'] - $this->fixed['min'])/ $this->tussenstappen);
			
			# 
			$y_max =  $this->fixed['min'] + $this->tussenstappen * $y_stap;
			$min_data = $this->fixed['min'];
		}
		
		# draw raster
		for ($waarde = $min_data; $waarde <= $y_max; $waarde += $y_stap)
		{
			# current Y value
			$y = ($this->rand['top'] + $grafiek_hoogte * ( 1 - ($waarde-$min_data) / ($y_max - $min_data) ));
			
			# draw horizontal lines
			imageline(
						$this->img, 
						$this->rand['left'], 
						$y, 
						( $this->breedte - $this->rand['right']), 
						$y, 
						$this->{$this->default_color['raster']}
					);
		 
			# get some data from font & text
			$tekst_grootte = imagettfbbox(10, 0, $this->font, $waarde);
			
			# write the value's at Y as
			imagettftext(
							$this->img, 
							10, 
							0, 
							($this->rand['left']- ($tekst_grootte['4'] + $tekst_grootte['0']) -8), 
							($y + ((0 - $tekst_grootte['5']) / 2)), 
							$this->{$this->default_color['text']}, 
							$this->font, 
							round($waarde, 1)
						);

		}

		#
		$punt_nummer = 0;
		$previous_x = -1;
		$previous_y = -1;
		 
		foreach ($this->data AS $punt => $waarde)
		{

			$x = $this->rand['left'] + $grafiek_breedte * $punt_nummer/(count($this->data) - 1);
			$y = $this->rand['top'] + $grafiek_hoogte * ( 1 - ($waarde-$min_data) / ($y_max - $min_data) );
		 
			# make line connections
			if ($previous_x != -1 && $previous_y != -1)
				imageline($this->img, $x, $y, $previous_x, $previous_y, $this->{$this->default_color['grafiek']});
		 
			# label
			$tekst_grootte = imagettfbbox(10, 90, $this->font, $punt);
			
			imagettftext(
							$this->img, 
							10, 
							$this->spin, 
							$x - ($tekst_grootte['4']+$tekst_grootte['0']) / 2,
							$this->hoogte - $this->rand['bottom'] + ($tekst_grootte['1']-$tekst_grootte['3']) + 3,
							$this->{$this->default_color['text']}, 
							$this->font, 
							$punt
						);
			
			# X as lines
			imageline($this->img, $x, $this->rand['top'], $x, $this->hoogte-$this->rand['bottom'], $this->{$this->default_color['raster']});
		 
			# trash stuff ^^
			$previous_x = $x;
			$previous_y = $y;
			$punt_nummer++;
		 
		}
		# line around graf (optional)
		if ( $this->draw_box )
		{
			imagerectangle($this->img, $this->rand['left'], $this->rand['top'], ($this->breedte - $this->rand['right']), ($this->hoogte - $this->rand['bottom']), $this->{$this->default_color['box']});
		}
	}
}
?>