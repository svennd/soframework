<?php
// building instruction for line culum euro

// additional settings loaded from database
$fetch = $core->db->sql ("SELECT * FROM `chart_options` WHERE chart = '1'", __FILE__, __LINE__);

foreach($fetch as $k => $v)
{
	$a = preg_match('/rand_(top|bottom|left|right)/', $v['name'], $match);

	if ( $a )
	{
		$core->chart->line->rand[$match['1']] = $v['value'];
	}
	elseif( $v['name'] == 'max' || $v['name'] == 'min' )
	{
		$core->chart->line->fixed[$v['name']] = $v['value'];
	}
	elseif( $v['name'] == 'gfx_color' )
	{
		$core->chart->line->default_color['grafiek'] = $v['value'];
	}
	else
	{
		$core->chart->line->{$v['name']} = $v['value'];
	}
}

// add data to progress
$fetch = $core->db->sql ("SELECT * FROM `chart_data` WHERE graph = '1' ORDER BY `year` ASC, `month` ASC", __FILE__, __LINE__);

$start_bedrag = 220000;
$cumul_procent = 100;

unset($data);
$data['Jul/\'10'] = $start_bedrag;
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
	
	$data[$x_point] = $cumul_euro;
}

// setting data array
$core->chart->line->data = $data;

// save chart to file
$core->chart->line->save_to_file('culum_euro');

?>