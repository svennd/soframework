<?php
# load modules
$core->load_modules(array('view'));

# load 'page' menu
$core->view->use_page('menu');

# load index page
$core->view->use_page('index');

?>