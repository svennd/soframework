<?php
# note this is not a install files ! 
# it only checks if everything is set correctly
# an aids the user in doing so!

#   Svenn D'Hert
include('../_main/main_frame.php');
 
# initialise frame
$core = new core(
					array('title' => 'installation check', 'path' => '../')
				);

# load modules
$core->load_modules('view');
 
# known modules
$module_info = array(
					'cms' => array(
							'CMS', 
							'file based content management system, with possibilty to lock/unlock files for editing', 
							'cms',
							-1),
					'database' => array(
							'Database Management',
							'Database management system, handles database query\'s.',
							'database',
							'config.default.php'
							),
					'dbcms' => array(
							'dbCMS', 
							'database based content management system', 
							'dbcms',
							-1,
							'database'),
					'filehandler' => array(
							'Filehandler', 
							'Do basic file level handels. (delete, copy, move, ...)', 
							'filehandler',
							-1),
					'form' => array(
							'Form', 
							'Input validation for forms', 
							'form',
							-1),
					'log' => array(
							'Log', 
							'Logs actions in file', 
							'log',
							-1),
					'sessions' => array(
							'Sessions', 
							'Session Handeling', 
							'sessions',
							-1,
							'database'),
					'users' => array(
							'Users', 
							'UserManagement, registration, login and user levels', 
							'users',
							-1,
							'database, sessions'),
					'view' => array(
							'View', 
							'A simple template module, used as view (in mvc model)', 
							'view',
							-1
							),
				);

# info to view
$core->view->set ('module_info', $module_info);

# output for the header
$core->view->use_page('header');

# output for main
$core->view->use_page('install');

# output for footer
$core->view->use_page('footer');

# show output to screen
$core->close();
?>
