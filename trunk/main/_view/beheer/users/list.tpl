<?php
// static
$level = array(
				-1 	=> 'banned',
				0 	=> 'guest',
				1	=> 'user',
				2	=> 'moderator',
				3	=> 'admin',
				4	=> 'owner',
				5	=> 'dev'
				);
?>
<h2>Admin Paneel</h2>
<a href="/beheer/">Beheer</a> &rsaquo; Gebruikerslijst

<table width="100%">
	<tr>
		<td>id</td>
		<td>gebruikers</td>
		<td>level</td>
		<td>email</td>
		<td>registratie datum</td>
		<td>opties</td>
	</tr>
	<?php
	foreach($users as $k => $v)
	{
		echo "<tr>";
			echo "<td>" . $v['id'] 		. "</td>";
			echo "<td>" . $v['username'] 		. "</td>";
			echo "<td>" . $level[$v['level']] 		. " (" . $v['level'] . ")</td>";
			echo "<td>" . $v['email'] 		. "</td>";
			echo "<td>" . $v['reg_date'] 		. "</td>";
			echo "<td><i>Aanpassen</i> | <i>Verwijderen</i></td>";
		echo "</tr>";
	}
	?>

	</tr>
</table>
<a href='/beheer/user/new'>Nieuwe gebruiker toevoegen.</a>