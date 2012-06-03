<h3>Installed modules</h3>
<p>These modules have been installed, and have been checked.</p>

<table width='70%'>
<tr>
	<td><b>Name module</b></td>
	<td><b>Installed version</b></td>
	<td><b>Latest version</b></td>
</tr>
<?php

	foreach ( $addons as $addon )
	{
		# newer verion available
		if ( $addon['current_version'] < $addon['latest_version'])
		{
			echo '<tr>
						<td><i>' . $addon['name'] . '</i></td>
						<td style="width:20%;background-color:#F87431;">' . $addon['current_version_name'] . ' (' . $addon['current_version'] . ')</td>
						<td style="width:20%;background-color:#6AFB92;">' . $addon['latest_version_name'] . ' (' . $addon['latest_version'] . ')</td>
					</tr>';
		}
		else
		{
			echo '<tr>
						<td>' . $addon['name'] . '</td>
						<td style="width:20%;background-color:#6AFB92;">' . $addon['latest_version_name'] . ' (' . $addon['latest_version'] . ')</td>
						<td style="width:20%;">&nbsp;</td>
					</tr>';
		}
	}



?>
</table>