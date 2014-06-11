<?php defined('SYSPATH') or die('No direct script access.');
?>
<hr>
<h1>EAV entities | <?= HTML::anchor('#', __('New'), array('onClick' => 'return alert("To add new entity run the following Minion Task: minion --task=EAV:Create --name=entity_name")')) ?></h1>
<br>
<table class="table" border="1">
<th>EAV_Entity_Name</th>
<th>EAV_Entity_Set</th>
<th>Nomenclatures</th>
<?php foreach ($entities as $entity): ?>
	<tr>
		<td><?=$entity->get_name()?></td>
		<td><a href="/eav_entities/index/<?=$entity->get_name()?>"><?=$entity->get_set()->get_name()?></a></td>
		<td class="center"><a href="#">Add</a></td>
	</tr>
<?php endforeach; ?>
</table>
<br>
<hr>
<h1>EAV Attributes | <?= HTML::anchor('eav_attributes/new', __('New')) ?></h1>

<table class="table" border="1">
<th>EAV_Attribute_Name</th>
<th>comment</th>
<th>type</th>
<th>default</th>
<th>unique</th>
<th>obligatory</th>
<th>show_in_listing</th>
<th>Delete</th>
<?php foreach ($attributes as $attribute): ?>
	<tr>
		<td><a href="#"><?=$attribute->name?></a></td>
		<td><?=$attribute->comment?></td>
		<td><?=$attribute->type->name?></td>
		<td><?=$attribute->default?></td>
		<td class="center"><?=$attribute->unique?></td>
		<td class="center"><?=$attribute->obligatory?></td>
		<td class="center"><?=$attribute->show_in_listing?></td>
		<td class="center"><a href="#">X</a></td>
	</tr>
<?php endforeach; ?>
</table>