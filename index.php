<?php
#   Svenn D'Hert
include('_main/main_frame.php');
 
# initialise frame
$core = new core();

$core->load_modules(array('database', 'log', 'sessions', 'users','view'));

# output for the header
$core->view->use_page('header');

$core->view->use_page('main');

$core->view->use_page('footer');

$core->close();
?>
