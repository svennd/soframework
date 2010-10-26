<?php
// load menu page
$core->view->use_page('menu');

if ( $action == 'remake_chart' ) 
{
	// static
	$maand = array("Jan","Feb","Mar","Apr","Mei","Jun","Jul","Aug","Sep","Okt","Nov","Dec");
	
	// load chart module
	$core->load_modules('chart');

	// 'select' line chart
	$core->chart->load('line');

	// additional settings
	$core->chart->line->spin = 80;
	$core->chart->line->rand['bottom'] = 75;
	$core->chart->line->rand['left'] = 75;
	$core->chart->line->rand['right'] = 20;
	// $core->chart->line->rand_fixed = 50;
	$core->chart->line->fixed['min'] = 15000;
	$core->chart->line->fixed['max'] = 60000;
	
	
	$core->chart->line->breedte = 800;
	// add data to progress
	$fetch = $core->db->sql ("SELECT * FROM `chart_data` ORDER BY `year` ASC, `month` ASC", __FILE__, __LINE__);

		$start_bedrag = 15000;
		$cumul_procent = 100;
	foreach ( $fetch as $month => $value )
	{
		
		// new culum %
		$cumul_procent		= $cumul_procent + ($value['value'] * $cumul_procent / 100);
		
		// new culum in euro
		$cumul_euro = floor($start_bedrag * ( $cumul_procent /100));
		
		// new winst in euro
		$winst_euro = floor ( $cumul_euro * ($value['value'] /100));

		// x point style month / 'year (Jan / '10)
		$x_point = $maand[$value['month'] - 1 ] . '/\'' . substr($value['year'], 2);
		
		$data[$x_point] = floor($cumul_procent) - 100;
	}
	print_r($data);
	
	// setting data array
	$core->chart->line->data = $data;
	
	// save chart to file
	$core->chart->line->save_to_file('test');
	
	$core->view->use_page('show_new_result');
}
else
{

	// show results
	$core->view->use_page('resultaten');
}
?>