<?php
#   Svenn D'Hert
include('frame/main_frame.php');

# initialisation w/o extra value's
$core = new core();

# loading the new module
$core->load_modules('admin');


	 // $c = array(
						// "old_txt" => array( 
											// '<!-- TEXT -->tekst die je wel kunt aanpassen<!-- TEXTEND -->'
										// ) ,
						// "new_txt" => array( 
										// '<!-- TEXT -->eded<!-- TEXTEND -->'
										// ) ,
						// );
// $core->cms->edit_file($c, 'index');

$core->template->output_page('index');


// $core->template->output_page('footer');

$core->close();

?>
