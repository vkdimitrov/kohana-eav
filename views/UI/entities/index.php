<?php
defined('SYSPATH') or die('No direct script access.');
$set_name = $entity->get_set()->get_name();
$entity_name =  $entity->get_name();
?>
<div class="container">
	<div class="col-md-12">
		<h1><?= HTML::anchor("/eav", __('Back')) ?> | <?= $set_name ?> | <?= HTML::anchor("/eav_entities/new/${entity_name}", __('New')) ?></h1>
		<table border="1">
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
						<td><a href="/eav_entities/edit/<?=$set->name?>/<?= $set->id ?>"><b><?= $set->name ?></b></a></td>
						<td><?= $set->parent->loaded() ? $set->parent->name : 'none' ?></td>
						<td class="center"><a href="/eav_entities/delete/<?= $set->id ?>" onclick="return confirm('Are you sure?')">X</a></td>
					</tr>
					<?php
				endforeach;
				?>
			</tbody>
		</table>
	</div>
</div>
