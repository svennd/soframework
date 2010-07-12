<?php
#   Svenn D'Hert
include('frame/main_frame.php');

# initialisation w/o extra value's
$core = new core();

# loading the new module
$core->load_modules('admin');

$core->template->header = array(
								'title' 				=> 'voorbeeld bestand'
							);

$position = ( isset( $_GET['position'] ) ) ? (int) $_GET['position'] : false;

$core->template->use_page('header');
if ( !$position )
{
	$core->template->edit_able_list = $core->cms->get_file_list();
	$core->template->use_page('admin/cms/file_list');
}
else if ( $position )
{
	# filename given & file is edit able
	$file = ( isset( $_GET['file'] ) && in_array($_GET['file'], $core->cms->get_file_list()) ) ? $_GET['file'] : false;
	$core->template->use_page('admin/cms/edit_file');
}
	 // $c = array(
						// "old_txt" => array( 
											// '<!-- TEXT -->tekst die je wel kunt aanpassen<!-- TEXTEND -->'
										// ) ,
						// "new_txt" => array( 
										// '<!-- TEXT -->eded<!-- TEXTEND -->'
										// ) ,
						// );
// $core->cms->edit_file($c, 'index');



$core->template->use_page('footer');

$core->close();

?>
