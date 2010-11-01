<?php
# load the main bioPHP module
$core->load_modules('biophp');

# a sample dna sequence (random)
$dna = 'ATGAAACCCTGAATGAAACCCUGA';
	echo 'DNA : ' . $dna . '<br/>';

# translating to rna
$rna = $core->bio->dna_to_rna( $dna ) ;
	echo 'RNA : ' . $rna . '<br/>'; 

# what triplets do we have in rna
$rna_triplet = $core->bio->mrna_to_triplet( $rna );
	echo 'Triplets : <br/>';
	foreach ($rna_triplet as $triplet)
	{
		echo $triplet . '<br/>';
	}

# or even better lets just transcribe them to peptides
echo 'Peptide order : <br/>';
	foreach ( $rna_triplet as $triplet )
	{
		$peptide = $core->bio->triplet_to_peptide( $triplet );
		if ( $peptide )
			echo $peptide . '(' . $triplet . ')<br/>';
		else
			echo '<b>stop-codon</b> (' . $triplet . ')<br/>';
	}
	
# ofc this could be done in 1 step :
# $rna_triplet = $core->bio->mrna_to_triplet( $core->bio->dna_to_rna( $dna ) )
# foreach ( $rna_triplet as $triplet )
# {
#	echo $core->bio->triplet_to_peptide( $triplet ) . '(' . $triplet . ')';
# }
?>