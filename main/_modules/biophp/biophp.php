<?php
/**
* @package SoFramwork
* @copyright 2010 Svenn D'Hert
* @license http://www.gnu.org/licenses/gpl-2.0.txt GNU Public License
*/

/**
* biophp class
* @abstract
*/
final class biophp
{
	public 
		$core,
		$module_path
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
		
		include $module_path . 'inc/fasta.php';
		$this->fasta = new fasta($core, $module_path);
	}
	
	/**
	* transcribe dna -> rna
	* @param string
	* @return string
	*/
	public function dna_to_rna ( $dna )
	{
		return preg_replace('/T/', 'U', $dna);
	}

	/**
	* form up the triplets
	* @param string
	* @return array
	*/
	public function mrna_to_triplet ( $mrna, $offset = 0 )
	{
		if ( $offset == 0 )
		{
			$mrna = substr( $mrna, $offset);
		}
		$rna_length = strlen($mrna);
		
		// loop triplets
		for ($i = 0; $i < $rna_length; $i += 3)
		{
			$triplet_order[] = substr($mrna, $i, 3);
		}
	
		return $triplet_order;
	}
	
	/**
	* 'translate' triplets to peptide (letter)
	* @param string
	* @return array
	*/
	public function triplet_to_peptide ( $triplet )
	{
		# include the codon_base library
		require $this->module_path . 'inc/condon_base.inc';
		if ( strlen($triplet) == 3 )
		{
			return $codon_to_peptide[$triplet];
		}
		return false;
	}
	
	/**
	* check if string is dna
	* @param string
	* @return bool
	*/	
	public function is_dna ( $string )
	{
		# anything found beside ACTG will return 0
		return (!preg_match("/[^(A|C|T|G)]/", $string));
	}
		
	/**
	* check if string is rna
	* @param string
	* @return bool
	*/
	function is_rna ( $string )
	{
		# anything found beside ACUG will return 0
		return (!preg_match("/[^(A|C|U|G)]/", $string));
	}
}
?>