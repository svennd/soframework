<?php
#   Svenn D'Hert
include('frame/main_frame.php');

# initialisation w/o extra value's
$core = new core();

# loading the new module
$core->load_modules('admin_cms');

$core->view->header = array(
								'title' 				=> 'voorbeeld bestand'
							);

$position = ( isset( $_GET['position'] ) ) ? (int) $_GET['position'] : false;

$core->view->use_page('header');

if ( !$position )
{	
	# save list with edit-able files to view var
	$core->view->edit_able_list = $core->admin_cms->get_file_list();
	
	# 
	$core->view->use_page('admin/cms/file_list');
}
else if ( $position )
{
	# filename given & file is edit able
	$file = ( isset( $_GET['file'] ) && in_array($_GET['file'], $core->admin_cms->get_file_list()) ) ? $_GET['file'] : false;
	$core->view->use_page('admin/cms/edit_file');
	$header['addit_header'] = 'test';
}
	 // $c = array(
						// "old_txt" => array( 
											// '<!-- TEXT -->tekst die je wel kunt aanpassen<!-- TEXTEND -->'
										// ) ,
						// "new_txt" => array( 
										// '<!-- TEXT -->eded<!-- TEXTEND -->'
										// ) ,
						// );
// $core->admin_cms->edit_file($c, 'index');



$core->view->use_page('footer');

$core->close();

?>
