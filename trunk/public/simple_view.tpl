<html>
	<head>
		<!-- title is set in new core(array('title' => 'my title')) -->
		<title>View module example : 
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
		<link href="<?php echo $page_info->path; ?>public/bootstrap/bootstrap.min.css" rel="stylesheet" />
		<script src="<?php echo $page_info->path; ?>public/bootstrap/bootstrap.min.js"></script>
	
		<!-- needed for dynamic change of scale for different type of screens -->
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
	</head>
	<body>
		<!-- we use a simple bootstrap template, this is to your likings ofcourse -->
		<div class="row span11">
			<p>Woohooo, hello world! Your first page using view module is working correctly!</p>
			<table class='table table-hover span5'>
				<thead>
					<tr><th>name</th><th>value's</th></tr>
				</thead>
				<tbody>
					<tr><td>a</td><td><?php echo $a; ?></td></tr>
					<tr><td>b</td><td><?php echo $b; ?></td></tr>
					<tr><td>test_var</td><td><?php echo $test_var; ?></td></tr>
					<tr><td>test_var+a+b</td><td><?php echo ($test_var+$a+$b); ?></td></tr>
				</tbody>
			</table>
		</div>
	</body>
</html>