<?php
// load menu page
$core->view->use_page('menu');

// load chart module
$core->load_modules('chart');

// 'select' balk chart
$core->chart->load('balk');

$data = array(
    "Apr/'08" => 10.21,
    "Mei/'08" => 10.32,
    "Jun/'08" => 10.92,
    "Jul/'08" => 8.91,
    "Aug/'08" => 9.49,
    "Sep/'08" => 8.88,
    "Okt/'08" => 4.65,
    "Nov/'08" => 5.14,
    "Dec/'08" => 5.05,
    "Jan/'09" => 4.23,
    "Feb/'09" => 4.42,
    "Mar/'09" => 4.11,
    "Apr/'09" => 6.84,
    "Mei/'09" => 1.94,
    "Jun/'09" => 1.82,
    "Jul/'09" => 1.79,
    "Aug/'09" => 2.01,
    "Sep/'09" => 2.18,
    "Okt/'09" => 4.82,
    "Nov/'09" => 3.79,
    "Dec/'09" => 3.95,
    "Jan/'10" => 4.54,
    "Feb/'10" => 4.32,
    "Mar/'10" => 6.63,
    "Apr/'10" => 5.66

	);
		
// setting data array
$core->chart->balk->data = $data;
	
// save chart to file
$core->chart->balk->save_to_file('balk');
	
$core->view->use_page('balk');


?>