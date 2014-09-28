<?php view('include/header', array('title' => $title)); ?>

<div class="container">

	<h1><?php echo $title ?></h1>

	<?php if (isset($success)): ?>
		<div class="alert alert-success" role="alert"><?php echo $success ?></div>
	<?php endif; ?>

	<form class="form-horizontal" role="form" method="POST" action="<?php echo $form_action ?>">
	  <div class="form-group">
	    <label for="subject" class="col-sm-2 control-label">Subject</label>
	    <div class="col-sm-10">
	      <input type="text" class="form-control" id="subject" name="subject" <?php echo isset($disabled) && $disabled ? 'disabled="disabled"' : '' ?> placeholder="Subject" value="<?php echo isset($subject) ? $subject : '' ?>">
	    </div>
	  </div>
	  <div class="form-group">
	    <label for="text" class="col-sm-2 control-label">Text</label>
	    <div class="col-sm-10">
	      <textarea class="form-control" id="text" name="text" <?php echo isset($disabled) && $disabled ? 'disabled="disabled"' : '' ?> placeholder="Text"><?php echo isset($text) ? $text : '' ?></textarea>
	    </div>
	  </div>
	  <div class="form-group">
	  	<div class="col-sm-offset-2 col-sm-10">
	  		<a href="<?php baseurl('note/') ?>" class="btn btn-default" role="button">Back</a>
	  		<button type="submit" class="btn btn-default" name="button"><?php echo $action ?></button>
	  	</div>
	  </div>
	</form>

</div>

<?php view('include/footer'); ?>