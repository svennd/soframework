<?php
$fetch = $core->db->sql ("SELECT * FROM `chart_data` WHERE graph = '1' ORDER BY `year` ASC, `month` ASC", __FILE__, __LINE__);

unset($data);
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
	
	$data[$x_point] = floor($winst_euro);
}


$fill = count($data);
	
// we need data from 1 year at least, if not add zero's
if ( $fill < 11 )
{
	$last_fetch = $fetch[$fill - 1];
	$month 	= $last_fetch['month'];
	$year 	= $last_fetch['year'];
	
	// data = 2
	$i = 1;
	while ($i < 13 - $fill)
	{
		$a = $month - 1 + $i;
		$new_month = ($a >= 12) ? $a - 12 : $a;

		$x_point = $maand[$new_month] . '/\'' . substr($year, 2);
		$data[$x_point] = 0;
		$i++;
	}
}

// setting data array
$core->chart->balk->data = $data;
	
// save chart to file
$core->chart->balk->save_to_file('winst_euro');

?>