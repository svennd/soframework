<?php
#   Svenn D'Hert
include('frame/main_frame.php');

# initialisation w/o extra value's
$core = new core();

# example load of form module
$core->load_modules('form');
	# the functions defined in 'form' class can now be called like :
	# $core->form->function();

# information needed for header.tpl (this can be done in an optional module "header.php", an editted by givin $page_info in initialiser)
$core->template->header = array(
								'title' 				=> 'voorbeeld bestand'
							);
# output for the header
$core->template->use_page('header');

$core->template->test = 123;
# return the index.tpl w/o using the template system to replace stuff
# ofc here should happen way more like getting query's doing math ect.
$core->template->use_page('index');

# and returning the foot of template file,
# again this can be done in "config" of the template module, tho to remain OOP I removed this
# or optional you can user a module "footer.php" to let this be done in the close(); function
$core->template->use_page('footer');

# end of file, this will ouput framework template, kill sessions, finish db connection
# tho, this could done auto. when using _destructor (maybe going to be implemented in further versions)
$core->close();

?>
