<?php
#   Svenn D'Hert
include('frame/main_frame.php');

$core = new core();


$header_template_vars = array(
								'TITEL' 				=> 'voorbeeld bestand',
								'TITEL_META' 			=> 'voorbeeld van frame',
								'DESCRIPTION' 			=> 'voorbeeld bestand',
								'KEYWORDS' 				=> '',
								'ADDAPT_HEADER' 		=> '',
								'TEMPLATE_FOLDER' 		=> './frame/template/',
							
							);
$core->template->output_page('header', $header_template_vars);

# get out, you dammed $vars!
$core->template->output_page('index');


$core->template->output_page('footer');
# lock the door.
$core->close();
?>
