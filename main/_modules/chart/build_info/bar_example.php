<?php
// building instruction for bar example
// this file should be referenced in allowed_files var in chart.php (module file)

// generating data
$data = array (
				"day 1" => 4,
				"day 2" => 12,
				"day 3" => 2,
				"day 4" => 5,
				"day 5" => 8,
		);

// setting data array
$core->chart->balk->data = $data;
	
// save chart to file
$core->chart->balk->save_to_file('winst_procent');

?>