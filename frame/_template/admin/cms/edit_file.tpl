&nbps;
<div id='cms_editor'>
	<ul>
		<li>Terug keren</li>
		<li><a href='#' title='submit'>Wijzigingen opslaan</a></li>
	</ul>
</div>
<script type="text/javascript">

//console.log('click');

$(document).ready(function() {
	$('[class^=toggle-item]').hide();
	$('[class^=link]').click(function() {
		var $this = $(this);
		var x = $this.attr("className");
		$('.' + x ).toggle();
		$('.toggle-item-' + x).toggle();
		return false;
	});
	$("a[title=submit]").click( function(){
		$('#page_edit').submit();
  });
});
</script>
<style>
	#hide:hover {
		background-color:#F4F4F4;
	}
</style>
<?php
echo $page;
?>