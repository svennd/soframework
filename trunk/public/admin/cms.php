<?php
#   Svenn D'Hert
include('../main/main_frame.php');

# initialisation w/o extra value's
$core = new core(array('PATH' => '../'));

# loading the new module
$core->load_modules('admin_cms');

# header info for view
$core->view->header = array(
								'title' 				=> 'Content Management',
								'template_folder'		=> '../main/_view',	
								// 'addit_header'			=> '<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>'
								'addit_header'			=> '<script type="text/javascript" src="http://localhost/sof/jquery-1.4.2.min.js"></script>'
							);

# main switch
$position = ( isset( $_GET['position'] ) ) ? (int) $_GET['position'] : false;

# will need the header on each page
$core->view->use_page('header');

# not yet choisen what page to edit
if ( !$position )
{	
	# save list with edit-able files to view var
	$core->view->edit_able_list = $core->admin_cms->get_file_list();
	
	# output 
	$core->view->use_page('admin/cms/file_list');
}
else if ( $position )
{
	# filename given & file is edit able
	$file = ( isset( $_GET['file'] ) && in_array($_GET['file'], $core->admin_cms->get_file_list()) ) ? $_GET['file'] : false;
	
	$core->view->page = $core->admin_cms->show_edit_page($file);
	$core->view->use_page('admin/cms/edit_file');
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


# add footer to page
$core->view->use_page('footer');

print_r($_POST);
$core->close();

?>