<?php
/**
* @package SoFramwork
* @copyright 2010 Svenn D'Hert
* @license http://www.gnu.org/licenses/gpl-2.0.txt GNU Public License
*/

/* ex. fasta
>gi|5524211|gb|AAD44166.1| cytochrome b [Elephas maximus maximus]
LCLYTHIGRNIYYGSYLYSETWNTGIMLLLITMATAFMGYVLPWGQMSFWGATVITNLFSAIPYIGTNLV
EWIWGGFSVDKATLNRFFAFHFILPFTMVALAGVHLTFLHETGSNNPLGLTSDSDKIPFHPYYTIKDFLG
LLILILLLLLLALLSPDMLGDPDNHMPADPLNTPLHIKPEWYFLFAYAILRSVPNKLGGVLALFLSIVIL
GLMPFLHTSKHRSMMLRPLSQALFWTLTMDLLTLTWIGSQPVEYPYTIIGQMASILYFSIILAFLPIAGX
IENY
*/

/**
* fasta class
* @abstract
*/
final class fasta
{
	public 
		$core,
		$module_path
		;

	private
		$allowed_ext = array('fasta', 'fna', 'ffn', 'faa', 'frn')
		;

	/**
	* initialize
	* @param object
	* @param string
	*/
	function __construct( $core, $module_path )
	{
		// reference to the core object
		$this->core = $core;
		$this->module_path = $module_path;
	}
	
	/**
	* read & analyse a fasta string
	* @param string
	*/
	function read_fasta( $source )
	{
		// split each line
		$source_array = explode("\n", $source);
		
		$seq 		= 0; // amount of sequence in given string
		$notes 		= 0; // notes ';'
		$header_id	= 0;
		$header		= array(); // contains all header info
		$sequence	= array(); // contains all sequence
		$parse_info = array();
	
		foreach ($source_array as $line)
		{
			// do we find > .... then this is a new sequence
			$new_sequence 	= strpos($line, ">");
			$new_note 		= strpos($line, ";"); # older

			// just sequence
			if ( $new_sequence === false && $new_note === false ) 
			{
				$sequence[$seq] .= $line; 
			}
			// note/new header found :)
			else
			{
				$header[$header_id] = array(
											"full_line" 	=> $line,
											"param" 		=> explode("|", $line)
											);
				
				$header_id++;
				$seq++;
				// making new space for sequentie
				$sequence[$seq] = '';
			}
		}
		$parse_info['seq'] 		= $seq;
		$parse_info['lines'] 	= count($source_array);
		
		return array( $header, $sequence, $parse_info);
	}
	
	/**
	* write a fasta file
	* @param array
	* @param string
	* @param string
	* @param string
	*/
	function write_fasta( $param, $name, $seq, $ext = 'fasta' )
	{
		// making the header
		$first_line = implode("|", $param);
		$content = '>' . $first_line . "\n" . $seq;
		
		// checking if extension is allowed
		$ext = ( in_array( $ext, $this->allowed_ext) ) ? $ext : 'fasta';
		
		// writing it to file
		$new_fasta_file = fopen( $this->module_path . 'resource/' . $name . '.' . $ext , 'w+');
		fwrite($new_fasta_file, $content);
		fclose($new_fasta_file);
	}
}
?>