<?php
#   Svenn D'Hert
include('_main/main_frame.php');
 
# initialise frame
$core = new core(
					array('title' => 'User Page')
				);

# load modules
$core->load_modules(array('database', 'sessions', 'users', 'view', 'form'));

# log the user out if requested
if(isset($_GET['byebye']))
{
	$core->user->logout();
	$core->view->report 	= "You are now logged out.";
	$core->view->use_page('header');
	$core->view->use_page('index');
	$core->view->use_page('footer');
	$core->close();
	exit;
}

# check if user is logged in
if ($core->user->is_logged() && !isset($_GET['byebye'])){ header("Location: index.php");}

# main pointer
$register_new_user 	= (isset($_GET['reg']) ) ? true : false;
$login_user 		= (isset($_GET['log']) ) ? true : false;

# output for the header
$core->view->use_page('header');

# reg new user
if ($register_new_user)
{
	# register pointer
	$data_entered = (isset($_GET['data'])) ? true : false;
	
	# load design for new registered user (congratz!)
	if ($data_entered)
	{
		$myform = new form($core, array('username' => 'string', 'password' => 'string', 'email' => 'email'));
		
		if (isset($myform->result['username']) && isset($myform->result['password']) && $myform->result['email'])
		{
			if (!$core->user->registration ($myform->result['username'], $myform->result['password'], $myform->result['email']))
			{
				$core->view->set('error', 'Registration failed, most likely your username already excist please <a href="ucp.php?reg" class="alert-link">try again</a>.');
				$core->view->use_page('index');
			}
			else
			{
				$core->view->is_admin 		= $core->user->is_level(3);
				$core->view->is_logged_in 	= true;
				$core->view->set('succes', 'Registration succes, you are now logged in :).');
				$core->view->use_page('index');
			}
		}
		else
		{
			$core->view->set('error', 'Some of the entered values are not valid, please get back and <a href="ucp.php?reg" class="alert-link">retry</a>.');
			$core->view->use_page('index');
		}
	}
	# load design for new user
	else
	{
		$core->view->use_page('new_user');
	}
}

# login
if ($login_user)
{
	# login pointer
	$data_entered = (isset($_GET['data'])) ? true : false;
	
	# load design for new registered user (congratz!)
	if ($data_entered)
	{
		$myform = new form($core, array('username' => 'string', 'password' => 'string'));
		
		if (isset($myform->result['username']) && isset($myform->result['password']))
		{
			if ($core->user->login($myform->result['username'], $myform->result['password']))
			{
				$core->view->is_admin 		= $core->user->is_level(3);
				$core->view->is_logged_in 	= true;
				$core->view->set('succes', 'Login succes, <a href="index.php" class="alert-link">welcome back, aboard sir!</a>.');
				$core->view->use_page('index');
			}
			else
			{
				$core->view->set('report', 'No valid data entered, <a href="ucp.php?log" class="alert-link">try again</a>.');
				$core->view->use_page('index');
			}
		}
		else
		{
			$core->view->set('report', 'No valid data entered, <a href="ucp.php?log" class="alert-link">try again</a>.');
			$core->view->use_page('index');
		}
	}
	# load design for new user
	else
	{
		$core->view->use_page('user_login');
	}
} 

# output for footer
$core->view->use_page('footer');

# show output to screen
$core->close();