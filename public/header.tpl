<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="nl-be">
<head>
	<title><?php if (isset($page_info->title)) {print_r($page_info->title);} else {echo 'Geen Titel ? :)';}  ?></title>
	
	<!-- bootstrap http://twitter.github.io/bootstrap/ -->
	<link href="<?php echo $this->core->path; ?>public/bootstrap/bootstrap.min.css" rel="stylesheet" />
	<script src="<?php echo $this->core->path; ?>public/bootstrap/bootstrap.min.js"></script>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
<div class="row">