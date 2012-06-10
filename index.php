<?php
#   Svenn D'Hert
include('_main/main_frame.php');
 
# initialise frame
$core = new core(
					array('title' => 'Mijn Pagina')
				);

# load modules
$core->load_modules(array('database', 'log', 'sessions', 'users', 'view', 'dbcms'));

# output for the header
$core->view->use_page('header');

# output for main
$core->view->use_page('index');

# output for footer
$core->view->use_page('footer');

# show output to screen
$core->close();
?>
