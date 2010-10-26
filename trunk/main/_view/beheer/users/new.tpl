<h2>Admin Paneel</h2>
<a href="/beheer/">Beheer</a> &rsaquo; Nieuwe gebruiker

<h3>Nieuwe gebruiker</h3>
<form method="POST" action="/beheer/user/save" autocomplete="off">
	<table border="0">
		<tr>
			<td>Naam :</td>
			<td><input type="text" name="naam" id="naam" size="30"></td>
		</tr>
		<tr>
			<td>Paswoord :</td>
			<td><input type="password" name="pasw" id="pasw" size="30"></td>
		</tr>
		<tr>
			<td>email :</td>
			<td><input type="text" name="email" id="email" size="30"></td>
		</tr>
		<tr>
			<td>Level :</td>
			<td>
				<select name="level">
					<option value="1">Gebruiker (1)</option>
					<option value="2">Moderator (2)</option>
					<option value="3">Admin (3)</option>
					<option value="4">Owner (4)</option>
				</select>
			</td>
		</tr>
	</table>	
	<input type="submit" name="submit" value="Submit" />

</form>
</p>
