<?php
#   Svenn D'Hert
include('../main/main_frame.php');

# initialisation w/o extra value's
$core = new core(array('PATH' => '../'));

# loading the new module
$core->load_modules('users');

# header info for view
$core->view->header = array(
								'title' 				=> 'User Management',
								'template_folder'		=> '../main/_view',	
							);

// get vars 
$modify = (isset($_GET['modify'])) ? true : false;
$delete = (isset($_GET['delete'])) ? true : false;
$add_user = (isset($_GET['add_user'])) ? true : false;

# will need the header on each page
$core->view->use_page('header');

// if nothing is given default list
if ( !$modify && !$delete && !$add_user )
{
	$next = (isset($_GET['show_more'])) ? (int) $_GET['show_more'] : false;
	
	// get array with users, 10 limit
	$core->view->user_list = ( $next ) ? $core->user->show_all_users(10) : $core->user->show_all_users(10 + $next);
	
	// each time a admin wants to see more users add 10 to next link
	$core->view->show_more = ( !$next ) ? 10 : $next + 10;
	
	// the page :)
	$core->view->use_page('admin/user/index');
}
// add user
elseif ( $add_user )
{
	// save it or still want page ?
	$save = (isset($_GET['save'])) ? true : false;
	if ( $save )
	{
		$user_name = (isset($_POST['username'])) ? $_POST['username'] : '';
		$user_pass = (isset($_POST['password'])) ? $_POST['password'] : '';
		$user_email = (isset($_POST['email'])) ? $_POST['email'] : '';
		$user_level = (isset($_POST['level'])) ? (int) $_POST['level']: '';
		if ( $return = $core->user->registration ($user_name, $user_pass, $user_email, $user_level, true))
		{
			$core->log->save('admin_add_user user:' . $return);
			
			$core->view->succes = 'succes';
			$core->view->msg = 'user succesfull added.';
			$core->view->use_page('admin/user/response');
		}
		else
		{
			$core->log->save('fail admin_add_user user: not_set');
			
			$core->view->succes = 'niet gelukt succes';
			$core->view->msg = 'Er is iets fout gelopen bij het invoegen van de gebruikers info.';
			$core->view->use_page('admin/user/response');
		}
	}
	// page output
	else
	{
		$core->view->use_page('admin/user/add_user');
	}
}
else
{
	# get user id, and admin cannot edit his own settings
	$user_id = (isset($_GET['id']) && $core->user->get_user_id() != (int) $_GET['id'] ) ? (int) $_GET['id'] : false;
	if ( $user_id )
	{
		$save = (isset($_GET['save'])) ? true : false;
		if ( !$save )
		{
			$core->db->sql('SELECT user_data.id, user_data.username, user_data.email, user_data.level FROM user_data WHERE user_data.id = "' . $user_id . '" LIMIT 1;', __LINE__, __FILE__);
			$core->view->user_info = array(
											'id' 		=> $core->db->result['id'],
											'name' 		=> $core->db->result['username'],
											'email' 	=> $core->db->result['email'],
											'level' 	=> $core->db->result['level']
											);
			if ( $delete )
			{
				$core->view->use_page('admin/user/delete');
			}
			elseif ( $modify )
			{
				$core->view->use_page('admin/user/modify');
			}
		}
		else
		{	
			if ( $delete )
			{
				$core->view->delete = true;
				if ($core->db->sql('DELETE FROM `user_data` WHERE `id` = ' . $user_id . ' LIMIT 1;', __LINE__, __FILE__))
				{
					$core->view->succes = 'succes';
					$core->view->msg = 'Gebruiker succesvol verwijderd.';
					$core->log->save('admin_delete user:' . $user_id);
					$core->view->use_page('admin/user/response');
				}
				else
				{
					$core->view->succes = 'niet gelukt succes';
					$core->view->msg = 'Er is iets fout gelopen bij het verwijderen van deze gebruiker.';
					$core->log->save('fail admin_delete user:' . $user_id);
					$core->view->use_page('admin/user/response');
				}
			}
			elseif ( $modify )
			{
					$user_name = (isset($_POST['username'])) ? $_POST['username'] : '';
					$user_pass = (isset($_POST['password'])) ? $_POST['password'] : '';
					$user_email = (isset($_POST['email'])) ? $_POST['email'] : '';
					$user_level = (isset($_POST['level'])) ? (int) $_POST['level']: '';
					
					if ($core->user->edit_user_info ( $user_id, $user_name, $user_pass, $user_email, $user_level))
					{
						$core->view->succes = 'succes';
						$core->view->msg = 'gebruikersinfo succesvol gewijzigd.';
						$core->log->save('admin_edit_user user:' . $user_id);
						$core->view->use_page('admin/user/response');
					}
					else
					{
						$core->view->succes = 'niet gelukt succes';
						$core->view->msg = 'Er is iets fout gelopen bij het aanpassen van de gebruikers info.';
						$core->log->save('fail admin_edit_user user:' . $user_id);
						$core->view->use_page('admin/user/response');
					}
			}
		}
		
	}
}

$core->view->use_page('footer');

$core->close();

?>