<?php

// load menu page
$core->view->use_page('menu');

// get something from db
$fetch = $core->db->sql ("SELECT * FROM users;", __FILE__, __LINE__);

// send to view
$core->view->fetch = $fetch;

// show results
$core->view->use_page('db_example');

?>