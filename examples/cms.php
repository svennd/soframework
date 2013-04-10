<?php
#   Svenn D'Hert
include('_main/main_frame.php');
 
# initialise frame
$core = new core(
					array('title' => 'Mijn Pagina')
				);

# load modules
$core->load_modules(array('cms'));

# edit both value's
$core->cms->edit_file('header', 'some change', 'cms.tpl');
$core->cms->edit_file('body', 'some change', 'cms.tpl');

# lock the file so it cannot be editted anymore
$core->cms->lock('cms.tpl');

# show output to screen
$core->close();
?>
