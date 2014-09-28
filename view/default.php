<?php view('include/header', array('title' => 'Notes')); ?>

<div class="container">

	<h1>Notes</h1>

	<?php if (isset($success)): ?>
		<div class="alert alert-success" role="alert"><?php echo $success ?></div>
	<?php endif; ?>

	<div class="row">
		<div class="col-md-2 col-md-offset-10 text-right">
			<a href="<?php baseurl('note/add/') ?>" class="btn btn-default btn-lg" role="button">Add</a>
		</div>
	</div>

	<table class="table">
		<thead>
			<tr>
				<th>Date</th>
				<th>Subject</th>
				<th></th>
			</tr>
		</thead>

		<tbody>
			<?php if (count($items) == 0): ?>
				<tr>
					<td colspan="3" class="text-muted text-center">No notes found.</td>
				</tr>
			<?php else: ?>
				<?php foreach ($items as $item): ?>
					<tr>
						<td><?php echo date('Y-m-d H:i:s', $item->date) ?></td>
						<td><?php echo $item->subject ?></td>
						<td class="text-right">
							<a href="<?php baseurl('note/edit/key/' . $item->key) ?>"><span class="glyphicon glyphicon-edit"></span></a> |
							<a href="<?php baseurl('note/delete/key/' . $item->key) ?>"><span class="glyphicon glyphicon-remove"></span></a>
						</td>
					</tr>
				<?php endforeach; ?>
			<?php endif; ?>
		</tbody>
	</table>

</div>

<?php view('include/footer'); ?>