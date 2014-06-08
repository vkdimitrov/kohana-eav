<?php defined('SYSPATH') or die('No direct script access.');
?>
<div class="container">
    <div class="col-md-12">
        <h1><?= __('Attributes') ?> | <?= HTML::anchor('admin/entities/attributes/new', __('New')) ?></h1>
		<label for="filter"><?= __('Attribute Filter') ?>:</label> <input type="text" name="filter" id="filter" value=""/> <span id="filter-sign" class="glyphicon glyphicon-minus-sign"></span>
        <table class="table table-striped tablesorter zebra-striped">
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
				$("#filter-sign").hide();
				$("table.tablesorter")
						.tablesorter({sortList: [[0, 0]], widthFixed: true, widgets: ['zebra']})
						.tablesorterPager({container: $("#pager")});
				$("#filter").change(function() {
					tableubdate();
				});
				$("#filter-sign").click(function() {
					$("#filter").val("");
					tableubdate();
				});

			});
			function tableubdate() {
				if ($("#filter").val() === "") {
					$("#filter-sign").hide();
				} else {
					$("#filter-sign").show();
				}
				$.post("/admin/entities/attributes/ajax_filter", {filter: $("#filter").val()}, function(data) {
					$("table.tablesorter tbody").html("");
					for (k in data) {
						var rdata = data[k];
						console.debug(rdata);
						var row = $("<tr/>");
						var td = $("<td/>").append($("<b/>").append($("<a/>", {href: "/admin/entities/attributes/edit/" + rdata.id}).html(rdata.name)));
						row.append(td);
						var td = $("<td/>").html(rdata.type.name);
						row.append(td);
						var td = $("<td/>").html(rdata.comment);
						row.append(td);
						var td = $("<td/>").html(rdata.default);
						row.append(td);
						var td = $("<td/>").html(rdata.unique);
						row.append(td);
						var td = $("<td/>").html(rdata.obligatory);
						row.append(td);
						var td = $("<td/>").append($("<b/>").append($("<a/>", {href: "/admin/entities/attributes/delete/" + rdata.id, onclick: 'return confirm("Are you sure?")'}).html('<span class="glyphicon glyphicon-trash"></span>')));
						row.append(td);
						$("table.tablesorter tbody").append(row);
					}
					$("table.tablesorter")
							.trigger("update");
				}, "json");
			}
		</script>
    </div>
</div>