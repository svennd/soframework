<?php
#   Svenn D'Hert
include('main/main_frame.php');
 
# initialise frame
$core = new core();

# output for the header
$core->view->use_page('header');

$core->view->use_page('main');

$core->view->use_page('footer');

$core->close();
?>
