<?php
#   Svenn D'Hert
include('_main/main_frame.php');
 
# initialise frame
$core = new core(
					array('title' => 'User Beheer')
				);

# load modules
$core->load_modules(array('database', 'sessions', 'users', 'view'));

# check if user is logged in
if (!$core->user->is_logged() || $core->user->get_user_level() <= 3 ){ header("Location: index.php"); exit;}

$core->view->is_admin 		= $core->user->is_level(3);
$core->view->is_logged_in 	= true;

# output for the header
$core->view->use_page('header');

# user info
$core->view->users 		= $core->db->sql('SELECT * FROM `user_data`;');
$core->view->level		= array('-1' => 'banned', '0' => 'guest', '1' => 'user', '2' => 'moderator', '3' => 'admin',  '4' => 'developer');

# output for user_list
$core->view->use_page('user_list');

# output for footer
$core->view->use_page('footer');

# show output to screen
$core->close();