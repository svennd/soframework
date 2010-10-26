<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="nl-be">
	<head>
		<title><?php echo $header['title'] ?></title>
		
		<meta http-equiv="content-type"     content="text/html; charset=utf-8"/>
		<meta http-equiv="content-language" content="nl-be"/>

		
		<?php echo (isset($header['meta'])) ? "<meta name=\"title\"  content=" . $header['meta'] . "/>" : ''; ?>
		<?php echo (isset($header['description'])) ? "<meta name=\"description\"  content=" . $header['description'] . "/>" : ''; ?>
		<?php echo (isset($header['keywords'])) ? "<meta name=\"keywords\"  content=" . $header['keywords'] . "/>" : ''; ?>
		
		<meta name="distribution" content="global"/>
		<meta name="copyright"    content="&copy; website"/>		
		
		<!-- css -->
		<link href="<?php echo (isset($header['template_folder'])) ? $header['template_folder'] : '/main/_view' ?>/css/default.css" rel="stylesheet" type="text/css" />

		<?php echo (isset($header['addit_header'])) ? "<!-- additional header files -->\n" . $header['addit_header'] : ''; ?>
		  <link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css"/>
		  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js"></script>
		  <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>
		  
		  <script>
		  $(document).ready(function() {
			$("#tabs").tabs();
			$("#tabsalfa").tabs();
		  });
		  </script>
	</head>
	<body>