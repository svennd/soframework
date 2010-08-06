<?php
#   Svenn D'Hert
include('main/main_frame.php');

# initialisation w/o extra value's
$core = new core();

// $core->auto_load_on();

# information needed for header.tpl (this can be done in an optional module "header.php", an editted by givin $page_info in initialiser)
$core->view->header = array(
								'title' 				=> 'voorbeeld bestand'
							);

# output for the header
$core->view->use_page('header');

# return the index.tpl w/o using the view system to replace stuff
# ofc here should happen way more like getting query's doing math ect.
$core->view->use_page('index');

# and returning the foot of view file,
# again this can be done in "config" of the view module, tho to remain OOP I removed this
# or optional you can user a module "footer.php" to let this be done in the close(); function
$core->view->use_page('footer');

# end of file, this will ouput framework view, kill sessions, finish db connection
# tho, this could done auto. when using _destructor (maybe going to be implemented in further versions)
$core->close();

?>
