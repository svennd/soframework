<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="nl-be">
<head>

	<!-- title allong with other values can be set as part of page_info in the initialisation of the page -->
	<title>
		<?php 
			if (isset($page_info->title)) 
			{
				echo $page_info->title;
			} 
			else
			{
				echo 'No Title';
			}  
		?>
	</title>
	
	<!-- bootstrap http://twitter.github.io/bootstrap/ -->
	<link href="<?php echo $this->core->path; ?>public/bootstrap/bootstrap.min.css" rel="stylesheet" />
	<script src="<?php echo $this->core->path; ?>public/bootstrap/bootstrap.min.js"></script>
	
	<!-- needed for dynamic change of scale for different type of screens -->
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
	<!-- we use a simple bootstrap template, this is to your likings ofcourse -->
	<div class="row">