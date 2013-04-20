<?php
#   Svenn D'Hert
include('../_main/main_frame.php');
 
# initialise frame
$core = new core(
					array('title' => 'Mijn Pagina', 'path' => '../')
				);

# load modules
$core->load_modules('view');

$core->
# show output to screen
$core->close();
?>
