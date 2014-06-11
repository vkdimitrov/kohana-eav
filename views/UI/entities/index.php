<?php
defined('SYSPATH') or die('No direct script access.');
$set_name = $entity->get_set()->get_name();
$entity_name =  $entity->get_name();
?>

<h1><?= HTML::anchor("/eav", __('Back')) ?> | <?= $set_name ?>s | <?= HTML::anchor("/eav_entities/new/${entity_name}", __('New')) ?></h1>
<table border="1">
	<thead>
		<tr>
			<th>Id</th>
			<th>Set Name</th>
			<th>Set Comment</th>
			<th>Parent Set</th>
			<th>Delete</th>
		</tr>
	</thead>
	<tbody>
		<?php
		foreach ($sets as $set) :
			?>
			<tr>
				<td><?=$set->id?></td>
				<td><a href="/eav_entities/edit/<?=$set_name?>/<?= $set->id ?>"><b><?= $set->name ?></b></a></td>
				<td><?= ($set->comment == NULL) ? 'N/A' : $set->comment?></td>
				<td><?= $set->parent->loaded() ? $set->parent->name : 'none' ?></td>
				<td class="center"><a href="/eav_entities/delete/<?= $set->id ?>" onclick="return confirm('Are you sure?')">X</a></td>
			</tr>
			<?php
		endforeach;
		?>
	</tbody>
</table>
