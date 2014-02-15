<?php
#   Svenn D'Hert
include('_main/main_frame.php');
 
# initialise frame
$core = new core(
					array('title' => 'Index')
				);

# load modules
$core->load_modules(array('database', 'sessions', 'users', 'view'));
 
# is logged in
$core->view->is_logged_in 	= $core->user->is_logged();

# is admin
$core->view->is_admin 		= $core->user->is_level(3);

# output for the header
$core->view->use_page('header');

# output for main
$core->view->use_page('index');

# output for footer
$core->view->use_page('footer');

# show output to screen
$core->close();
?>
