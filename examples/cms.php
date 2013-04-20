<?php
#   Svenn D'Hert
include('../_main/main_frame.php');
 
# initialise frame
$core = new core(
					array('title' => 'Mijn Pagina', 'path' => '../')
				);

# load modules
$core->load_modules('cms');

# output for the header
$core->view->use_page('header');

# print a fancy table with all the edits
# that one can do using our cms module
# most failing edits will result in a die()
# so its no use putting else statements in here
echo '
<table class="table table-hover">
	<thead><tr><th>test</th><th>result</th></tr></thead>
	<tbody>
	<tr>
		<td>change header tag</td>
		<td>';
		
		# this will attempt to change the content of the header tag with "-header-"
		# note: this will die() if something is wrong so no failed tab ;)
		if ($core->cms->edit_file('header', '-header-', 'tests/cms.tpl'))
		{
			echo '<span class="label label-success">Success</span>';
		}
echo '
		</td>
	</tr>
	<tr>
		<td>change body tag</td>
		<td>';
		
		# this will attempt to change the content of the body tag with "-body-"
		# note: this will die() if something is wrong so no failed tab ;)
		if ($core->cms->edit_file('body', '-body-', 'tests/cms.tpl'))
		{
			echo '<span class="label label-success">Success</span>';
		}
echo '
		</td>
	</tr>
	<tr>
		<td>lock file</td>
		<td>';
		# this will attempt to "lock" the file
		# locking the file means setting <!-- edit:true --> to false
		# this could be renamed to finished
		# optional you could also remove all tags; this however would require editing later
		# using $core->cms->lock('tests/cms.tpl', true);
		if ($core->cms->lock('tests/cms.tpl'))
		{
			echo '<span class="label label-success">Success</span>';
		}
echo
		'</td>
	</tr>
	<tr>
		<td>un-lock file</td>
		<td>';
		# check if config allows us unlocking
		# if not report this; this is default behavior
		# since one could force a file to be "unlocked" that wasn't supposed to be
		# edited at all
		if (!isset($core->cms_can_unlock) || !$core->cms_can_unlock)
		{
			echo '<span class="label label-warning">cms cannot unlock; config cms_can_unlock is set to false or not set (default)</span>';
		}
		else
		{
			# we are allowed to unlock
			# try unlock, again if failed then it will die() 
			if($core->cms->unlock('tests/cms.tpl')) 
			{	
				echo '<span class="label label-success">Success</span>';
			}
		}
echo '</td>
	</tr>
	</tbody>
</table>';

# show output to screen
$core->close();
?>
