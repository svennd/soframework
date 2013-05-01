<?php
#   Svenn D'Hert
include('../_main/main_frame.php');
 
# initialise frame
$core = new core(
					array(
							# you can set your own title per page if your template system supports it
							# but also author, and other page related info tags
							'title' => 'My view page', 
							
							# this is usefull if you link to other files as css/js/image from a subdirectory
							# as we do here ;-) if nothing is set it attempts './' (local dir)
							'path' => '../'
							)
				);

# load modules
$core->load_modules('view');

# define content for template file
	# default way of seting a variable
	$core->view->set('a', '5');
	$core->view->set('b', (3*5));
	
	# this uses a 'magic' definition __set(); 
	# its not a good practice but its damn handy sometimes !
	$core->view->test_var = 10;
	
# 'include' / use a page note that this page has no access to $core directly but could access it
# using $this->core if the parameter 'view_access_core' is set to true in the _main/_config.php file
# note that it keeps the file in "buffer" it will not be parsed here, that happens when $core->close(); is called
$core->view->use_page('simple_view');

# show output to screen
$core->close();
?>
