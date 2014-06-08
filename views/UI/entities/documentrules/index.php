<?php defined('SYSPATH') or die('No direct script access.'); ?>
<div class="container">
    <div class="col-md-12">
        <h1><?= __('Rules') ?> | <?= HTML::anchor('admin/entities/documentrules/new', __('New')) ?></h1>
        <table class="table table-striped tablesorter zebra-striped">
            <thead>
                <tr>
                    <th><?= __('Rule Name') ?></th>
                    <th><?= __('Rule Type') ?></th>
                    <th><?= __('Rule Is Default') ?></th>
                    <th><?= __('Rule Is Unique') ?></th>
                    <th><?= __('Rule Is Obligatory') ?></th>
                </tr>
			</thead>
            <tfoot>
                <tr>
                    <th><?= __('Rule Name') ?></th>
                    <th><?= __('Rule Type') ?></th>
                    <th><?= __('Rule Is Default') ?></th>
                    <th><?= __('Rule Is Unique') ?></th>
                    <th><?= __('Rule Is Obligatory') ?></th>
                </tr>
			</tfoot>
			<tbody>
				<?php
				foreach ($document_rules as $rule) :
					?>
					<tr>
						<td><b><?= HTML::anchor('admin/entities/documentrules/edit/' . $rule->id, $rule->name) ?></b></td>
						<td><?= $rule->type->name ?></td>
						<td><?= __($rule->default) ?></td>
						<td><?= __($rule->unique) ?></td>
						<td><?= __($rule->obligatory) ?></td>
					</tr>
					<?php
				endforeach;
				?>
            </tbody>
        </table>
		<div id="pager" class="pager">
			<form>
				<span class="glyphicon glyphicon-step-backward first"></span>
				<span class="glyphicon glyphicon-backward prev"></span>
				<input type="text" class="pagedisplay"/>
				<span class="glyphicon glyphicon-forward next"></span>
				<span class="glyphicon glyphicon-step-forward last"></span>
				<select class="pagesize">
					<option selected="selected"  value="10">10</option>
					<option value="20">20</option>
					<option value="30">30</option>
					<option value="40">40</option>
				</select>
			</form>
		</div>

		<script>
			$(document).ready(function() {
				$("table.tablesorter")
						.tablesorter({sortList: [[0, 0]], widthFixed: true, widgets: ['zebra']})
						.tablesorterPager({container: $("#pager")});
			});
		</script>
    </div>
</div>
