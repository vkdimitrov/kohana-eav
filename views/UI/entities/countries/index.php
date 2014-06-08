<?php defined('SYSPATH') or die('No direct script access.');
$singular_nomenclature = Inflector::singular($controller);
?>
<div class="container">
	<div class="col-md-12">
		<h1><?= __(ucfirst($controller)) ?> | <?=HTML::anchor("admin/entities/{$controller}/new", __('New')) ?></h1>
		<table class="table table-striped">
			<thead>
				<tr>
					<th><?= __(ucfirst($singular_nomenclature) . ' ' .__('Name')) ?></th>
					<th><?= __(__('Parent') . ' ' . $singular_nomenclature) ?></th>
					<th><?= __('Delete') ?></th>
				</tr>
				<?php
				foreach ($sets as $set) :
					?>

					<tr>
						<td><a href="/admin/entities/<?=$controller?>/edit/<?= $set->id ?>"><b><?= $set->name ?></b></a></td>
						<td><?=$set->parent->loaded() ? $set->parent->name : 'няма'?></td>
						<td><a href="/admin/entities/<?=$controller?>/delete/<?= $set->id ?>" onclick="return confirm('Are you sure?')"><span class="glyphicon glyphicon-trash"></span></a></td>
					</tr>

					<?php
				endforeach;
				?>
			</thead>
		</table>
	</div>
</div>
