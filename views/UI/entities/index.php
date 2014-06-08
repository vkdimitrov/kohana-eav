<?php
defined('SYSPATH') or die('No direct script access.');
?>
<div class="container">
	<div class="col-md-12">
		<h1><?= $entity ?> | <?= HTML::anchor("/eav_entities/new/${entity}", __('New')) ?></h1>
		<table >
			<thead>
				<tr>
					<th>Name</th>
					<th>Parent</th>
					<th>Delete</th>
				</tr>
			</thead>
			<tbody>
				<?php
				foreach ($sets as $set) :
					?>
					<tr>
						<td><a href="/admin/entities/<?= $controller ?>/edit/<?= $set->id ?>"><b><?= $set->name ?></b></a></td>
						<td><?= $set->parent->loaded() ? $set->parent->name : 'none' ?></td>
						<td><a href="/admin/entities/<?= $controller ?>/delete/<?= $set->id ?>" onclick="return confirm('Are you sure?')"><span class="glyphicon glyphicon-trash"></span></a></td>
					</tr>
					<?php
				endforeach;
				?>
			</tbody>
		</table>
	</div>
</div>
