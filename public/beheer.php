<?php
$core->view->use_page('menu');

$core->load_modules('chart');
# user is not logged in
if ( !$core->user->is_logged() )
{
	# user is trying to login
	if ( $action == 'login')
	{
		$user 		= (isset($_POST['username'])) ? $_POST['username'] : '';
		$password 	= (isset($_POST['password'])) ? $_POST['password'] : '';

		if ( $core->user->login($user , $password) )
		{
			$core->view->action = array(
						'title' 	=> 'Inlog poging',
						'status' 	=> '<a href="/beheer/">Inlog succesvol</a>',
						'text' 		=> 'U bent succevol ingelogt als ' . $user .'<br/><a href="/beheer/">keer terug</a>',
						);
		}
		else
		{
			$core->view->action = array(
						'title' 	=> 'Inlog poging',
						'status' 	=> '<a href="/beheer/">Inlog niet succesvol</a>',
						'text' 		=> 'Uw gegevens werden niet teruggevonden in de database, gelieve opnieuw te proberen.<br/><a href="/beheer/">keer terug</a>',
						);
		}
		$core->view->use_page('beheer/action');
	}
	else
	{
		$core->view->use_page('beheer/login');
	}
}
elseif ( $core->user->is_level(3) )
{
	if ( $action == 'maand')
	{
		$fetch = $core->db->sql ("SELECT * FROM `chart_data` WHERE graph = '1' ORDER BY `year` ASC, `month` ASC", __FILE__, __LINE__);
		$core->view->data = $fetch;
		$core->view->use_page('beheer/maand/show');
	}
	elseif( $action == 'chart_options' )
	{
		if ( $query == 'save' )
		{
			$chart_id	= (isset($_POST['id'])) ? (int) $_POST['id'] : '' ;
			if ( $chart_id == 1 )
			{
				// getting posted vars
				$hoogte			= (isset($_POST['hoogte'])) 	? 	(int) $_POST['hoogte'] : '' ;
				$breedte		= (isset($_POST['breedte'])) 	? 	(int) $_POST['breedte'] : '' ;
				$spin			= (isset($_POST['spin'])) 		? 	(int) $_POST['spin'] : '' ;
				$rand_left		= (isset($_POST['rand_left'])) 	? 	(int) $_POST['rand_left'] : '' ;
				$rand_right		= (isset($_POST['rand_right'])) ? 	(int) $_POST['rand_right'] : '' ;
				$rand_top		= (isset($_POST['rand_top'])) 	? 	(int) $_POST['rand_top'] : '' ;
				$rand_bottom	= (isset($_POST['rand_bottom'])) ? 	(int) $_POST['rand_bottom'] : '' ;
				$min			= (isset($_POST['min'])) 		? 	(int) $_POST['min'] : '' ;
				$max			= (isset($_POST['max'])) 		? 	(int) $_POST['max'] : '' ;
				$gfx_color		= (isset($_POST['gfx_color'])) 	? 	$_POST['gfx_color'] : '' ;
				
				// running the querys
				$core->db->sql ("UPDATE `chart_options` SET `value`='" . $hoogte . "' WHERE `name`= 'hoogte' AND chart = '1' LIMIT 1;", 			__FILE__, __LINE__);
				$core->db->sql ("UPDATE `chart_options` SET `value`='" . $breedte . "' WHERE `name`= 'breedte' AND chart = '1' LIMIT 1;", 			__FILE__, __LINE__);
				$core->db->sql ("UPDATE `chart_options` SET `value`='" . $spin . "' WHERE `name`= 'spin' AND chart = '1' LIMIT 1;", 				__FILE__, __LINE__);
				$core->db->sql ("UPDATE `chart_options` SET `value`='" . $rand_left . "' WHERE `name`= 'rand_left' AND chart = '1' LIMIT 1;", 		__FILE__, __LINE__);
				$core->db->sql ("UPDATE `chart_options` SET `value`='" . $rand_right . "' WHERE `name`= 'rand_right' AND chart = '1' LIMIT 1;", 	__FILE__, __LINE__);
				$core->db->sql ("UPDATE `chart_options` SET `value`='" . $rand_top . "' WHERE `name`= 'rand_top' AND chart = '1' LIMIT 1;", 		__FILE__, __LINE__);
				$core->db->sql ("UPDATE `chart_options` SET `value`='" . $rand_bottom . "' WHERE `name`= 'rand_bottom' AND chart = '1' LIMIT 1;", 	__FILE__, __LINE__);
				$core->db->sql ("UPDATE `chart_options` SET `value`='" . $min . "' WHERE `name`= 'min' AND chart = '1' LIMIT 1;", 					__FILE__, __LINE__);
				$core->db->sql ("UPDATE `chart_options` SET `value`='" . $max . "' WHERE `name`= 'max' AND chart = '1' LIMIT 1;", 					__FILE__, __LINE__);
				$core->db->sql ("UPDATE `chart_options` SET `value`='" . $gfx_color . "' WHERE `name`= 'gfx_color' AND chart = '1' LIMIT 1;", 		__FILE__, __LINE__);
				
				$core->view->action = array(
					'title' 	=> 'grafiek aangepast',
					'status' 	=> '<a href="/beheer/">grafiek aangepast</a>',
					'text' 		=> 'Grafiek aangepast.<br/><a href="/beheer/">keer terug</a>',
				);
				$core->view->use_page('beheer/action');		
			}
		}
		else
		{
			$hoogte 		= $core->db->sql ("SELECT * FROM `chart_options` WHERE `name`= 'hoogte' AND chart = '1' LIMIT 1;", __FILE__, __LINE__);
			$breedte 		= $core->db->sql ("SELECT * FROM `chart_options` WHERE `name`= 'breedte' AND chart = '1' LIMIT 1;", __FILE__, __LINE__);
			$spin 			= $core->db->sql ("SELECT * FROM `chart_options` WHERE `name`= 'spin' AND chart = '1' LIMIT 1;", __FILE__, __LINE__);
			$rand_left 		= $core->db->sql ("SELECT * FROM `chart_options` WHERE `name`= 'rand_left' AND chart = '1' LIMIT 1;", __FILE__, __LINE__);
			$rand_right 	= $core->db->sql ("SELECT * FROM `chart_options` WHERE `name`= 'rand_right' AND chart = '1' LIMIT 1;", __FILE__, __LINE__);
			$rand_top 		= $core->db->sql ("SELECT * FROM `chart_options` WHERE `name`= 'rand_top' AND chart = '1' LIMIT 1;", __FILE__, __LINE__);
			$rand_bottom	= $core->db->sql ("SELECT * FROM `chart_options` WHERE `name`= 'rand_bottom' AND chart = '1' LIMIT 1;", __FILE__, __LINE__);
			$min 			= $core->db->sql ("SELECT * FROM `chart_options` WHERE `name`= 'min' AND chart = '1' LIMIT 1;", __FILE__, __LINE__);
			$max 			= $core->db->sql ("SELECT * FROM `chart_options` WHERE `name`= 'max' AND chart = '1' LIMIT 1;", __FILE__, __LINE__);
			$gfx_color 		= $core->db->sql ("SELECT * FROM `chart_options` WHERE `name`= 'gfx_color' AND chart = '1' LIMIT 1;", __FILE__, __LINE__);
			
			$core->view->data = array($hoogte, $breedte, $spin, $rand_left, $rand_right, $rand_bottom, $rand_top, $min, $max, $gfx_color);
			$core->view->use_page('beheer/charts/option');
		}
	}
	elseif( $action == 'wijzig_dag' )
	{
		if ( $query == 'save_edit' )
		{
			$procent	= (isset($_POST['procent'])) ? $_POST['procent'] : '' ;
		
			$fetch = $core->db->sql ("UPDATE `sys_var` SET `value`='" . (int) $procent . "', `last_edit` = " . time() . " WHERE `id` = 1 LIMIT 1;", __FILE__, __LINE__);
			$core->view->action = array(
				'title' 	=> 'dagwaarde aanpassen',
				'status' 	=> '<a href="/beheer/">dagwaarde gewijzigd</a>',
				'text' 		=> 'De waarde is aangepast.<br/><a href="/beheer/">keer terug</a>',
			);
			$core->view->use_page('beheer/action');		
		}
		else
		{
			$fetch = $core->db->sql ("SELECT * FROM `sys_var` WHERE id = '1'", __FILE__, __LINE__);
			$core->view->vorige_waarde = $fetch['0'];
			$core->view->use_page('beheer/dag/change');
		}
	}
	elseif ( $action == 'user' )
	{
		// user
		if( $query == 'new' )
		{
			// show new user form
			$core->view->use_page('beheer/users/new');
		}
		elseif( $query == 'save' )
		{
		
			$user 	= (isset($_POST['naam'])) ? $_POST['naam'] : '' ;
			$pasw 	= (isset($_POST['pasw'])) ? $_POST['pasw'] : '' ;
			$email 	= (isset($_POST['email'])) ? $_POST['email'] : '' ;
			$level 	= (isset($_POST['level'])) ? $_POST['level'] : '' ;
			
			// add new user
			$core->user->registration ($user, $pasw, $email, $level, true);
			
			$core->view->action = array(
				'title' 	=> 'Nieuwe gebruiker',
				'status' 	=> '<a href="/beheer/user/">Nieuwe gebuiker toegevoegt</a>',
				'text' 		=> 'De gebruiker ' . $user . ' is nu toegevoegd.',
			);
			$core->view->use_page('beheer/action');	
		}
		else
		{
			// show userlist
			$fetch = $core->db->sql ("SELECT * FROM `user_data` ORDER BY id ASC", __FILE__, __LINE__);
			$core->view->users = $fetch;
			$core->view->use_page('beheer/users/list');
		}
	}
	elseif ( $action == 'shut_down' )
	{
		if ( $query == 'save' )
		{
			$reason	= (isset($_POST['reason'])) ? $_POST['reason'] : '' ;
			$fh = fopen($core->path . 'main/index.exit', 'w+');
				fwrite($fh, $reason);
				fclose($fh);
			
			$core->view->action = array(
				'title' 	=> 'Site afgesloten',
				'status' 	=> 'Website gesloten',
				'text' 		=> 'De site is nu gesloten.',
			);
			$core->view->use_page('beheer/action');	
			mail('svennson56@hotmail.com', 'fortunes website close down.', 'website fortunes is gesloten.');
		}
		else
		{
			$core->view->use_page('beheer/close/close');	
		}
	}
	elseif( $action == 'wijzig_maand' && $query )
	{
		if ( is_numeric($query) ) 
		{
			$year = substr($query, 0, 4);
			$month = substr($query, 4);
			
			$fetch = $core->db->sql ("SELECT * FROM `chart_data` WHERE year = '" . $year . "' AND month = '" . $month . "' LIMIT 1;", __FILE__, __LINE__);
			$core->view->data = $fetch;
			$core->view->use_page('beheer/maand/change');
		}

		elseif ( $query == 'save_edit' ) 
		{
			$id 	= (isset($_POST['id'])) ?  (int) $_POST['id'] : '' ; // insecure but whatever !
			$month	= (isset($_POST['month'])) ? $_POST['month'] : '' ;
			$year	= (isset($_POST['year'])) ? $_POST['year'] : '' ;
			$value 	= (isset($_POST['procent'])) ? $_POST['procent'] : '' ;
			
			if ( strlen($year) == 4 )
			{			
				$fetch = $core->db->sql ("UPDATE `chart_data` SET `value` = '" . $value . "', `year` = '" . $year . "', `month` = '" . $month . "' WHERE `id`= '" . $id . "' LIMIT 1;", __FILE__, __LINE__);
				if ( $core->db->result )
				{
					$core->view->action = array(
							'title' 	=> 'Maand aanpassen',
							'status' 	=> '<a href="/beheer/maand/">Maand ingegeven</a>',
							'text' 		=> 'De nieuwe maand is ingegeven.<br/><a href="/beheer/maand/">keer terug</a>',
							);
				}
				else
				{
					$core->view->action = array(
							'title' 	=> 'Maand aanpassen',
							'status' 	=> '<a href="/beheer/">Maand ingave niet gelukt</a>',
							'text' 		=> 'Een of meerdere velden zijn niet correct ingevuld.<br/><a href="/beheer/maand/">keer terug</a>',
							);
				}
			}
			else
			{
				$core->view->action = array(
						'title' 	=> 'Maand aanpassen',
						'status' 	=> '<a href="/beheer/">Maand ingave niet gelukt</a>',
						'text' 		=> 'Het jaar dient ingegeven te worden met 4 cijfers. (vb : 2010)<br/><a href="/beheer/maand/">keer terug</a>',
						);
			}
			$core->view->use_page('beheer/action');		
		}
		else
		{
			// error
		}
		$core->chart->reload_all();
	}
	elseif( $action == 'nieuw_maand' )
	{
		if ( $query )
		{
			$month	= (isset($_POST['month'])) ? $_POST['month'] : '' ;
			$year	= (isset($_POST['year'])) ? $_POST['year'] : '' ;
			$value 	= (isset($_POST['procent'])) ? $_POST['procent'] : '' ;
			
			$fetch = $core->db->sql ("INSERT INTO `chart_data` (`month`, `year`, `value`, `graph`) VALUES (" . $month . ", " . $year . ", " . $value . ", 1);", __FILE__, __LINE__);
			if ( $core->db->result )
			{
				$core->view->action = array(
						'title' 	=> 'Nieuwe maand ingegeven',
						'status' 	=> '<a href="/beheer/">Maand ingegeven</a>',
						'text' 		=> 'De nieuwe maand is ingegeven.<br/><a href="/beheer/maand/">keer terug</a>',
						);
			}
			else
			{
				$core->view->action = array(
						'title' 	=> 'Nieuwe maand ingegeven',
						'status' 	=> '<a href="/beheer/">Maand ingave niet gelukt</a>',
						'text' 		=> 'Een of meerdere velden zijn niet correct ingevuld.<br/><a href="/beheer/maand/">keer terug</a>',
						);
			}
			$core->view->use_page('beheer/action');
		}
		else
		{
			$core->view->use_page('beheer/maand/new');
		}
		$core->chart->reload_all();
	}
	elseif( $action == 'delete_maand' )
	{
		if ( $query )
		{
			$year = substr($query, 0, 4);
			$month = substr($query, 4);
			
			$fetch = $core->db->sql ("DELETE FROM `chart_data` WHERE ( `month`= '" . $month . "' AND `year`= '" . $year . "' AND `graph` = '1') LIMIT 1;", __FILE__, __LINE__);
			if ( $core->db->result )
			{
				$core->view->action = array(
						'title' 	=> 'Maand verwijderd',
						'status' 	=> '<a href="/beheer/maand/">succes</a>',
						'text' 		=> 'De maand is gewist uit de database.<br/><a href="/beheer/maand/">keer terug</a>',
						);
			}
			else
			{
				$core->view->action = array(
						'title' 	=> 'Maand verwijderd',
						'status' 	=> '<a href="/beheer/maand">failed</a>',
						'text' 		=> 'De maand kon niet worden gewist.<br/><a href="/beheer/maand/">keer terug</a>',
						);
			}
			$core->view->use_page('beheer/action');
		}
		$core->chart->reload_all();
	}
	elseif ( $action == 'edit_page' )
	{
		$core->load_modules('cms');
		
		if ( $query == 'save' )
		{
			$send = array();
			$edit_fields = $_POST['edit_field'];
			
			
			$return_field = $core->cms->get_text_fields($_POST['file']);
			
			$a = count($return_field['0']);
			$i = 0;
			while ( $a > $i )
			{
				$send['old_txt'][] = '<!-- start_blok name=' . $return_field['1'][$i] . ' -->(.*?)<!-- end_blok -->';
				$send['new_txt'][] = '<!-- start_blok name=' . $return_field['1'][$i]  . ' -->' . $_POST[$return_field['1'][$i]] . '<!-- end_blok -->';
				$i++;
			}
			if ($core->cms->edit_file ( $send, $_POST['file'] ) )
			{
					$core->view->action = array(
					'title' 	=> 'Pagina aangepast',
					'status' 	=> '<a href="/beheer/edit_page/">succesvol opgeslagen</a>',
					'text' 		=> 'Pagina opgeslagen.<br/><a href="/beheer/edit_page/">keer terug</a>',
					);
					$core->view->use_page('beheer/action');
			}
			else
			{
					$core->view->action = array(
					'title' 	=> 'page edit fail',
					'status' 	=> '<a href="/beheer/edit_page/">succesvol herladen</a>',
					'text' 		=> 'Er is iets foutgelopen...<br/><a href="/beheer/edit_page/">keer terug</a>',
					);
					$core->view->use_page('beheer/action');
			}
		}
		else if ( $query != '' )
		{
			if ( in_array( $query , $core->cms->get_file_list() ) )
			{
				$core->view->return_field = $core->cms->get_text_fields($query);
				$core->view->efile = htmlspecialchars($query);
				$core->view->use_page('beheer/cms/show_edit_field');
			}
			else
			{
				echo 'file not found';
			}
		}
		else
		{
			$core->view->data = $core->cms->get_file_list();
			$core->view->use_page('beheer/cms/index');
		}
	}
	elseif ( $action == 'reload_charts' )
	{
		$core->chart->reload_all();
		
		$core->view->action = array(
			'title' 	=> 'Grafiek herladen',
			'status' 	=> '<a href="/beheer/">succesvol herladen</a>',
			'text' 		=> 'De grafieken werden succesvol heraangemaakt.<br/><a href="/beheer/">keer terug</a>',
		);
		$core->view->use_page('beheer/action');
		
	}
	else
	{
		$core->view->use_page('beheer/admin');
	}
}
else
{	
	die('no users allowed');
}
?>