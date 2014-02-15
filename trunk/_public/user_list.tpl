<style>
	.user-row { margin-bottom: 14px; }
	.user-row:last-child { margin-bottom: 0; }
	.dropdown-user { margin: 13px 0; padding: 5px; height: 100%; }
	.dropdown-user:hover { cursor: pointer; }
	.table-user-information > tbody > tr { border-top: 1px solid rgb(221, 221, 221); }
	.table-user-information > tbody > tr:first-child { border-top: 0; }
	.table-user-information > tbody > tr > td { border-top: 0; }
</style>
<div class="well col-xs-12 col-sm-12 col-md-12 col-lg-12">
	<!-- user -->
	<?php foreach($users as $user) : ?>
	<div class="row user-row">
		<div class="col-xs-3 col-sm-2 col-md-1 col-lg-1">
			<img class="img-circle"
				 src="https://lh5.googleusercontent.com/-b0-k99FZlyE/AAAAAAAAAAI/AAAAAAAAAAA/eu7opA4byxI/photo.jpg?sz=50"
				 alt="User Pic">
		</div>
		<div class="col-xs-8 col-sm-9 col-md-10 col-lg-10">
			<strong><?php echo $user['username']; ?></strong><br>
			<span class="text-muted">User level: <?php echo $level[$user['level']]; ?></span>
		</div>
		<div class="col-xs-1 col-sm-1 col-md-1 col-lg-1 dropdown-user" data-for=".<?php echo $user['username']; ?>">
			<i class="glyphicon glyphicon-chevron-down text-muted"></i>
		</div>
	</div>
	<div class="row user-infos <?php echo $user['username']; ?>"">
		<div class="col-xs-12 col-sm-12 col-md-10 col-lg-10 col-xs-offset-0 col-sm-offset-0 col-md-offset-1 col-lg-offset-1">
			<div class="panel panel-primary" style="background-color: #f5f5f5;">
				<div class="panel-heading">
					<h3 class="panel-title">User information</h3>
				</div>
				<div class="panel-body">
					<div class="row">
                            <div class="col-xs-2 col-sm-2 col-md-3 col-lg-3">
                                <img class="img-circle"
                                     src="https://lh5.googleusercontent.com/-b0-k99FZlyE/AAAAAAAAAAI/AAAAAAAAAAA/eu7opA4byxI/photo.jpg?sz=100"
                                     alt="User Pic">
                            </div>
						<div class="col-xs-10 col-sm-10 col-md-9 col-lg-9">
							 <strong><?php echo $user['username']; ?></strong><br>
                                <table class="table table-user-information">
                                    <tbody>
                                    <tr>
                                        <td>User level:</td>
                                        <td><?php echo $level[$user['level']]; ?></td>
                                    </tr>
                                    <tr>
                                        <td>User level:</td>
                                        <td><?php echo $user['level']; ?></td>
                                    </tr>
                                    <tr>
                                        <td>User e-mail:</td>
                                        <td><?php echo $user['email']; ?></td>
                                    </tr>
                                    <tr>
                                        <td>Registered since:</td>
                                        <td><?php echo $user['reg_date']; ?></td>
                                    </tr>
                                    <tr>
                                        <td>Last update:</td>
                                        <td><?php echo $user['edit_date']; ?></td>
                                    </tr>
                                    <tr>
                                        <td>Last login:</td>
                                        <td><?php echo $user['last_login_date']; ?></td>
                                    </tr>
                                    </tbody>
                                </table>
						</div>
					</div>
				</div>
				<div class="panel-footer">
					<button class="btn btn-sm btn-primary" type="button"
							data-toggle="tooltip"
							data-original-title="Send message to user"><i class="glyphicon glyphicon-envelope"></i></button>
					<span class="pull-right">
						<button class="btn btn-sm btn-warning" type="button"
								data-toggle="tooltip"
								data-original-title="Edit this user"><i class="glyphicon glyphicon-edit"></i></button>
						<button class="btn btn-sm btn-danger" type="button"
								data-toggle="tooltip"
								data-original-title="Remove this user"><i class="glyphicon glyphicon-remove"></i></button>
					</span>
				</div>
			</div>
		</div>
	</div>
	<?php endforeach; ?>
</div>

<script type="text/javascript">
$(document).ready(function() {
    var panels 			= $('.user-infos');
    var panelsButton 	= $('.dropdown-user');
    panels.hide();
    panelsButton.click(function() {
        var dataFor 		= $(this).attr('data-for');
        var idFor 			= $(dataFor);
        var currentButton 	= $(this);
        idFor.slideToggle(400, function() {
            if(idFor.is(':visible')) {
				currentButton.html('<i class="glyphicon glyphicon-chevron-up text-muted"></i>');
            }
            else {
                currentButton.html('<i class="glyphicon glyphicon-chevron-down text-muted"></i>');
            }
        })
    });
    $('[data-toggle="tooltip"]').tooltip();
});
</script>