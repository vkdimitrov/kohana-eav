<?php defined('SYSPATH') or die('No direct script access.');
?>
<h1>EAV entities</h1>
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