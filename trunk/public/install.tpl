<div class="offset1 span11">
<h3>Installation check</h3>
<div class="alert">
  <strong>Warning!</strong> This script only looks for existance of the default config; The config file will be found under the module;
  (default: _modules/module_name/config.default.php) It is recommended you rename the file to :
  config.php in the module directory;
</div>
Checking main config : <?php echo (!is_file('../_main/_config.default.php')) ? '<i class="icon-ok"></i> OK' : '<i class="icon-remove"></i> Default Config' ; ?><br/>

Checking modules : <br/>
<table class="table table-hover">
<thead>
	<tr>
		<th>#</th>
		<th>Module</th>
		<th>Found</th>
		<th>Config</th>
	</tr>
</thead>
<tbody>
<?php 
	$i = 1;
	foreach ($module_info as $info) 
	{
		echo "<tr>
				<td>" . $i . "</td>
				<td>" . $info['0'] . "
					<p class='muted'>" . $info['1'] . "</p>
				</td>
				<td>" . (is_dir('../_modules/' . $info[2]) ? '<i class="icon-ok"></i> Yes':'<i class="icon-remove"></i> No') . "</td>
				<td>" . (($info['3'] == '-1' || !is_file('../_modules/' . $info[2] . '/' . $info[3])) ? 
																						'<i class="icon-ok"></i> OK' : 
																						'<i class="icon-remove"></i> Default Config'
						)
						. "</td>
			  </tr>
			  
			";
		$i++;
	}
?>
</tbody>
</table>
</div>
