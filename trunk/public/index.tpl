<?php if(isset($report)) : ?>
<div class="alert alert-warning alert-dismissable">
  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
  <strong><span class="glyphicon glyphicon-remove">&nbsp;Warnings!</strong> <?php echo $report; ?>
</div>
<?php endif; ?>

<?php if(isset($succes)) : ?>
<div class="alert alert-success alert-dismissable">
  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
  <strong><span class="glyphicon glyphicon-ok"></span>&nbsp;Succes!</strong> <?php echo $succes; ?>
</div>
<?php endif; ?>

<div class="well col-xs-12 col-sm-12 col-md-12 col-lg-12">
Index
</div>