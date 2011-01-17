<?php
#   Svenn D'Hert
include('main/main_frame.php');

// known pages
// page -> title
$known_pages = array(
						'index' 				=> 'This is the index page.',
						'db_example' 			=> 'database example.',
						'update'				=> 'update'
						);


$core = new core();

// what page is requested
$url = ( isset($_GET['url']) ) ? htmlspecialchars($_GET['url']) : '';

	// split the url
	$url = explode('/', $url);

	// get controller
	$contr 	= (!empty($url['0'])) ? $url['0'] : 'index' ;

	// get action
	$action = (isset($url['1'])) ? htmlspecialchars($url['1']) : '' ;
	
	// get query
	$query 	= (isset($url['2'])) ? htmlspecialchars($url['2']) : '' ;

	// main page check
	if ( !in_array( $contr , array_keys($known_pages) ) )
	{
		// echo 'error' . $contr;
		// error handeling
		$core->log('Unknown url requested. (' . htmlspecialchars($_GET['url']) . ')' ,'error_log');
		$core->close();
	}
	
$core->view->page = $contr;
$core->view->header = array(
								'title' 				=> $known_pages[$contr]
							);

# output for the header
// $core->view->use_page('header');

// include controller
include './public/'. $contr . '.php';

$core->view->use_page('footer');
$core->close();
?>
