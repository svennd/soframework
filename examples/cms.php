<?php
#   Svenn D'Hert
include('../_main/main_frame.php');
 
# initialise frame
$core = new core(
					array('title' => 'Mijn Pagina', 'path' => '../')
				);

# load modules
$core->load_modules('cms');

# edit both value's
print "done header change<br/>";
$core->cms->edit_file('header', '-header-', 'cms.tpl');

print "done body change<br/>";
$core->cms->edit_file('body', '-body-', 'cms.tpl');

# lock the file so it cannot be editted anymore -this page will 
# only be able to change value's once!
print "locked file<br/>";
$core->cms->lock('cms.tpl');

print "unlocked file<br/>";
$core->cms->unlock('cms.tpl');

# show output to screen
$core->close();
?>
