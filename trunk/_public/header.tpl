<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title><?php echo (isset($page_info->title)) ? $page_info->title : 'No title'; ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

	<!--link rel="_public/stylesheet/less" href="bootstrap/less/bootstrap.less" type="text/css" /-->
	<!--link rel="_public/stylesheet/less" href="bootstrap/less/responsive.less" type="text/css" /-->
	<!--script src="_public/bootstrap/js/less-1.3.3.min.js"></script-->
	<!--append ‘#!watch’ to the browser URL, then refresh the page. -->
	
	<link href="_public/bootstrap/css/bootstrap.min.css" rel="stylesheet">
	<!--<link href="_public/bootstrap/css/style.css" rel="stylesheet">-->

  <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
  <!--[if lt IE 9]>
    <script src="_public/bootstrap/js/html5shiv.js"></script>
  <![endif]-->

	<script type="text/javascript" src="_public/bootstrap/js/jquery.min.js"></script>
	<script type="text/javascript" src="_public/bootstrap/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="_public/bootstrap/js/scripts.js"></script>
</head>

<body>
<div class="container">
	<div class="row clearfix">
		<div class="col-md-12 column">
			<nav class="navbar navbar-default" role="navigation">
				<div class="navbar-header">
					 <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1"> <span class="sr-only">Toggle navigation</span><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></button> <a class="navbar-brand" href="#">Brand</a>
				</div>
				
				<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
					<ul class="nav navbar-nav">
						<li class="active">
							<a href="index.php">Home</a>
						</li>
					</ul>
					<ul class="nav navbar-nav navbar-right">
						<?php if (isset($is_logged_in) && $is_logged_in): ?>
						<li>
							<a href="ucp.php?byebye"><span class="glyphicon glyphicon-log-out"></span> Logout</a>
						</li>
							<?php if($is_admin) : ?>
							<li>
								<a href="admin.php"><span class="glyphicon glyphicon-user"></span> Userlist</a>
							</li>
							<?php endif; ?>
						<?php else: ?>
						<li>
							<a href="ucp.php?reg"><span class="glyphicon glyphicon-user"></span> Register</a>
						</li>
						<li>
							<a href="ucp.php?log"><span class="glyphicon glyphicon-log-in"></span> Login</a>
						</li>
						<?php endif; ?>
					</ul>
					<!--
					<ul class="nav navbar-nav navbar-right">
						<li class="dropdown">
							 <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-user"></span>&nbsp;User Management<strong class="caret"></strong></a>
							<ul class="dropdown-menu">
								<li>
									<a href="#">Action</a>
								</li>
								<li class="divider"></li>
								<li>
									<a href="#">Another action</a>
								</li>

							</ul>
						</li>
					</ul>
					-->
				</div>
				
			</nav>