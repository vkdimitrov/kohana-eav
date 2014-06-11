<?php defined('SYSPATH') or die('No direct script access.');
?>
<div class="container">
    <div class="col-md-12">
        <h1><?= __('Attributes') ?> | <?= HTML::anchor('eav_attributes/new', __('New')) ?></h1>
        <table border="1" class="table table-striped tablesorter zebra-striped">
            <thead>
                <tr>
                    <th><?= __('Attribute Name') ?></th>
                    <th><?= __('Attribute Type') ?></th>
                    <th><?= __('Comment') ?></th>
                    <th><?= __('Attribute Is Default') ?></th>
                    <th><?= __('Attribute Is Unique') ?></th>
                    <th><?= __('Attribute Is Obligatory') ?></th>
                    <th><?= __('Show In Listing') ?></th>
                   	<th><?= __('Delete') ?></th>
                </tr>
			</thead>
            <tfoot>
                <tr>
                    <th><?= __('Attribute Name') ?></th>
                    <th><?= __('Attribute Type') ?></th>
                    <th><?= __('Comment') ?></th>
                    <th><?= __('Attribute Is Default') ?></th>
                    <th><?= __('Attribute Is Unique') ?></th>
                    <th><?= __('Attribute Is Obligatory') ?></th>
                    <th><?= __('Show In Listing') ?></th>
                    <th><?= __('Delete') ?></th>
                </tr>
			</tfoot>
			<tbody>
				<?php
				foreach ($attributes as $attribute) :
					?>
					<tr>
						<td><b><?= HTML::anchor('admin/entities/attributes/edit/' . $attribute->id, $attribute->name) ?></b></td>
						<td><?= $attribute->type->name ?></td>
						<td><?= $attribute->comment ?></td>
						<td><?= $attribute->default ?></td>
						<td><?= $attribute->unique ?></td>
						<td><?= $attribute->obligatory ?></td>
						<td><?= $attribute->show_in_listing ?></td>
						<td><?= HTML::anchor('admin/entities/attributes/delete/' . $attribute->id, '<span class="glyphicon glyphicon-trash"></span>', array('onClick' => 'return confirm("Are you sure?")')) ?></td>
					</tr>
					<?php
				endforeach;
				?>
            </tbody>
        </table>
    </div>
</div>