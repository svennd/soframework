&nbps;
<div id='cms_editor'>
	<ul>
		<li>Terug keren</li>
	</ul>
</div>

<div id="hide" class='show_text'>
	tekst 123
</div>

<div id="text" style='display:none;' class='show_text'>
	<textarea name='test' id='test'>tekst 123</textarea>
</div>

<script type="text/javascript">

$('#hide').click(function() {
  $('#hide').css('display', 'none');
  $('#text').css('display', 'block');
  $('#text').addClass('show_text');
});
</Script>http://www.frontpagewebmaster.com
<style>
	#hide:hover {
		background-color:yellow;
	}
		
	.show_text{
		display : block;
	}
	.show_text:hover {
		background-color : #DDDDDD;
	}
</style>
