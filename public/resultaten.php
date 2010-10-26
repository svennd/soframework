<?php

// load menu page
$core->view->use_page('menu');

// months
$maand = array("Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Aug", "Sep", "Okt", "Nov", "Dec");

// get value's from database
$fetch = $core->db->sql ("SELECT * FROM `chart_data` WHERE graph = '1' ORDER BY `year` ASC, `month` ASC", __FILE__, __LINE__);

// start value's
$start_bedrag = 220000;
$cumul_procent = 100;

// loop data
foreach ( $fetch as $month => $value )
{
	// new culum %
	$cumul_procent	= $cumul_procent + ($value['value'] * $cumul_procent / 100);
	
	// new culum in euro
	$cumul_euro = floor($start_bedrag * ( $cumul_procent /100));
	
	// new winst in euro
	$winst_euro = floor ( $cumul_euro * ($value['value'] /100));

	// send data to view
	$data[] = array(
					"maand" 			=> $maand[$value['month'] - 1 ],
					"jaar" 				=> '\'' . substr($value['year'], 2),
					"winst_euro" 		=> $winst_euro,
					"cumul_euro" 		=> $cumul_euro,
					"winst_procent" 	=> $value['value'],
					"cumul_procent"		=> floor($cumul_procent) - 100
					);	
}
$fetch = $core->db->sql ("SELECT * FROM `sys_var` WHERE name = 'daily_result'", __FILE__, __LINE__);

// setting data array
$core->view->data = $data;
$core->view->start_bedrag = $start_bedrag;
$core->view->fetch = $fetch['0'];

// show results
$core->view->use_page('resultaten');

?>