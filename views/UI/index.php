<?php defined('SYSPATH') or die('No direct script access.');
?>
<h1>EAV  | <?= HTML::anchor("eav/new", 'New') ?></h1>
<br>
<table border="1">
<th>Entity Name</th>
<?php foreach ($entities as $entity): ?>
	<tr>
		<td><a href="/eav_entities/index/<?=$entity->class?>"><?=$entity->class?></a></td>
	</tr>
<?php endforeach; ?>
</table>