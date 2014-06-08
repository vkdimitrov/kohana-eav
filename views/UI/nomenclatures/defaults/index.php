<?php defined('SYSPATH') or die('No direct script access.'); 
?>
<div class="container">
	<div class="col-md-12">
		<h1><?= __($controller) ?></h1>
		<?php foreach($sets as $set): ?>
			<hr>
			<h2><?= $set->name . ' | ' . HTML::anchor('admin/nomenclatures/'.$controller.'/new/'. $set->id, __('New')) ?></h2>
			<table class="table table-striped tablesorter zebra-striped">
				<thead>
					<tr>
					<?php
						$attributes = $set->attributes->where('show_in_listing', '=', 1)->find_all()->as_array();
						foreach($attributes as $attribte): ?>
						<th><?= $attribte->label ?></th>
					<?php endforeach; ?>
						<th>Delete</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
					<?php foreach($attributes as $attribte): ?>
						<th><?= $attribte->label ?></th>
					<?php endforeach; ?>
						<th>Delete</th>
					</tr>
				</tfoot>
				<tbody>
					<?php
					foreach ($set->children_default->find_all()->as_array() as $child) : $first = 0;
						?>
						<tr>
						<?php foreach($attributes as $attribte): ?>
							 <td><?= ($first) ? $child->attr($attribte->name)->value : HTML::anchor('admin/nomenclatures/'.$controller.'/edit/'. $child->id, $child->attr($attribte->name)->value)?></td>
							<?php $first++;?>
						<?php endforeach; ?>
							<td><?= HTML::anchor("admin/nomenclatures/{$controller}/delete/". $child->id, '<span class="glyphicon glyphicon-trash"></span>', array('onClick' => 'return confirm("Are you sure?")')) ?></td>
						</tr>
						<?php
					endforeach;
					?>
				</tbody>
			</table>
		<?php endforeach; ?>

	</div>
</div>