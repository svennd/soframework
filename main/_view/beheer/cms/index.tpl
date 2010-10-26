<h2>Admin Paneel</h2>
<a href="/beheer/">Beheer</a> &rsaquo; CMS

<h3>CMS</h3>
bewerkbare pagina's :
<ul>
<?php
foreach ($data as $file )
{
	echo "<li><a href='/beheer/edit_page/" . $file . "'>" . $file . "</a></li>";
}
?>
</ul>