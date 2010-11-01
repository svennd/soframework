<?php
// load menu page
$core->view->use_page('menu');

// load newest addons
$addons_list = $core->update->check_new();

// send to view
$core->view->addons = $addons_list;

// show results
$core->view->use_page('update');

?>