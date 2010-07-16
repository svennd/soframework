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
		<link href="<?php echo (isset($header['template_folder'])) ? $header['template_folder'] : './frame/_template' ?>/css/default.css" rel="stylesheet" type="text/css" />

		<?php echo (isset($header['addit_header'])) ? "<!-- additional header files -->\n" . $header['addit_header'] : ''; ?>
	</head>
	<body>