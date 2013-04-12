<?php
#   Svenn D'Hert
include('_main/main_frame.php');
 
# initialise frame
$core = new core(
					array('title' => 'Mijn Pagina')
				);

# load modules
$core->load_modules(array('view'));

# yet to give examples :
# 'database', 'log', 'sessions', 'users'

# already done :
# 'cms'
 
# output for the header
$core->view->use_page('header');

# output for main
$core->view->use_page('index');

# output for footer
$core->view->use_page('footer');

# show output to screen
$core->close();
?>
