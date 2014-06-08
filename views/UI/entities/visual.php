<?php
defined('SYSPATH') or die('No direct script access.');
$singular_nomenclature = Inflector::singular($controller);
?>
<style>
	#attributes-library { min-height: 12em; }
	#attributes-library.custom-state-active { background: #eee; }
	#attributes-library li {cursor: move; float: left; padding: 0.4em; margin: 0 0.4em 0.4em 0; text-align: left; }
	li h5 { margin: 0 0 0.4em; float:left;display: inline;}
	.attribute-remove{ float: left; display: none;}
	#attributes-library li img { width: 100%; cursor: move; }
	.dataholder { min-height: 5em; padding: 1%; }
	.dataholder h4 { line-height: 16px; margin: 0 0 0.4em; }
	.dataholder h4 .ui-icon { float: left; }
	.dataholder ul { margin: 0; padding: 0; }

	.attribute {
		width: 144px;
		margin: 0 1em 1em 0;
		padding: 0.3em;
		cursor: move;
		float: left;
	}
	.attribute-title {
		padding: 0.2em 0.3em;
		margin-bottom: 0.5em;
		position: relative;
	}
	.attribute-toggle {
		/*		position: absolute;
				top: 50%;
				right: 0;
				margin-top: -8px;*/
		float: right;
	}
	.attribute-description {
		padding: 0.4em;
	}
	.attribute-placeholder {
		border: 1px dotted black;
		margin: 0 1em 1em 0;
		height: 50px;
	}
</style>
<script>
	$(function() {
		var save_url = '/admin/entities/<?= $controller ?>/save_visual/<?= $set->id ?>';
		var load_url = '/admin/entities/<?= $controller ?>/load_visual/<?= $set->id ?>';
		var $library = $("#attributes-library");
		$("div.dataholder").droppable({
			accept: "#attributes-library > div",
			activeClass: "ui-state-highlight",
			drop: function(event, ui) {
				addAttribute(this, ui.draggable);
			}
		});
		$("#attributes-library").droppable({
			accept: "ul.dataholder li div",
			activeClass: "custom-state-active",
			drop: function(event, ui) {
				removeAttribute(ui.draggable);
			}
		});
		function attribute_model(options) {
			this.options = {
				id: "",
				type: "",
				title: "",
				description: "",
				options: {}};
			this.options = $.extend(this.options, options);
			this.get = function() {
				var attribute = $("<div/>", {class: "attribute", id: "attribute-id-" + this.options.id, data_id: this.options.id});
				var title = $("<div/>", {class: "attribute-title"}).html(this.options.title);
				var description = $("<div/>", {class: "attribute-description", style: "display: none"}).html(this.options.description);
				var that = this;
				attribute.append(title, description);
				attribute.draggable({
					cancel: "a.ui-icon", // clicking an icon won't initiate dragging
					revert: "invalid", // when not dropped, the item will revert back to its initial position
					containment: "document",
					helper: "clone",
					cursor: "move"
				});
				attribute.addClass("ui-widget ui-widget-content ui-helper-clearfix ui-corner-all")
						.find(".attribute-title")
						.addClass("ui-widget-header ui-corner-all")
						.prepend("<span class='ui-icon ui-icon-circle-close attribute-remove'></span>")
						.prepend("<span class='ui-icon ui-icon-plusthick attribute-toggle'></span>");
				attribute.find(".attribute-remove").click(function() {
					removeAttribute($(this).parent().parent());
				})
				attribute.find(".attribute-toggle").click(function() {
					var icon = $(this);
					icon.toggleClass("ui-icon-plusthick ui-icon-minusthick");
					icon.closest(".attribute").find(".attribute-description").toggle();
				})
				return attribute;
			};
		}
		var attributes = new Array();
		var dataholder = new Array();
<?php
foreach ($attributes as $k => $attribute)
{
	?>
			attributes[<?= $attribute->id ?>] = new attribute_model({
				id: "<?= $attribute->id ?>",
				type: "<?= $attribute->type->name ?>",
				title: "<?= $attribute->label ?>",
				description: "<?= $attribute->label // description?                                ?>"
			});
	<?php
}
?>
		for (k in attributes) {
			$library.append(attributes[k].get());
		}
		function addAttribute($holder, $item) {
			var ident = $($holder).attr("id").split("-")[1];
			var attribute_id = $item.attr("data_id");
			dataholder[ident].data[attribute_id] = attributes[attribute_id].options;
			$item.fadeOut(function() {
				var $list = $("ul", $holder).length ?
						$("ul", $holder) :
						$("<ul class='gallery ui-helper-reset'/>").sortable({
					items: "li:not(.placeholder)",
					placeholder: "ui-state-highlight",
					sort: function() {
						$(this).removeClass("ui-state-default");
					}
				}).disableSelection().appendTo($holder);
				$item.find(".attribute-remove").show();
				$item.appendTo($list).fadeIn(function() {
					$item.animate({width: "100%"});
				});
			});
		}

		function removeAttribute($item) {
			var attribute_id = $item.attr("data_id");
			for (var ident in dataholder) {
				var data = {};
				for (var k in dataholder[ident].data) {
					if (k != attribute_id) {
						data[k] = dataholder[ident].data[k];
					}
				}
				dataholder[ident].data = data;
			}
			$item.fadeOut(function() {
				$item.find(".attribute-remove").hide();
				$item.css("width", "144px");
				$item.appendTo($library);
				$item.fadeIn();
			});
		}

		function loadData() {
			dataholder = {}
			var dataholders = $("#dataholders");
			$.getJSON(load_url, function(data) {
				var reload_need = false;
				$.each(data, function(key, val) {
					dataholder[key] = {}
					dataholder[key].data = {}
					dataholder[key].set = {}
					if ($("#dataholder-" + key).length === 0) {
						var holder = $("<div/>", {class: "col-sm-" + val.set.size + " dataholder", id: "dataholder-" + key});
						var title = $("<h4/>", {class: "ui-widget-header"}).html(val.set.title);
						holder.append(title);
						holder.droppable({
							accept: "#attributes-library > div",
							activeClass: "ui-state-highlight",
							drop: function(event, ui) {
								addAttribute(this, ui.draggable);
							}
						});
						dataholders.append(holder);
					} else {
						holder = $("#dataholder-" + key);
					}
					if (val.data) {

						$.each(val.data, function(k, v) {
							if (typeof attributes[v] !== "undefined") {
								dataholder[key].data[v] = v;
								attribite = attributes[v];
								addAttribute(holder, $("#attribute-id-" + v));
							} else {
								reload_need = true;
							}
						});
					}
				});
				if (reload_need) {
					saveData();
				}
			});
		}

		function saveData() {
			var json = "{";
			var kc = "";
			for (k in dataholder) {
				var row = kc + "\"" + k + "\":[";
				var ac = "";
				for (a in dataholder[k].data) {
					if (dataholder[k].data[a].id > 0) {
						row += ac + dataholder[k].data[a].id;
						ac = ",";
					}
				}
				row += "]";
				json += row;
				kc = ",";
			}
			json += "}"
			$.post(save_url, {visual: json}, function(data) {
			}, "json");
			loadData();
		}


		$("#attributes-library > li").click(function(event) {
			var $item = $(this);
			var $target = $(event.target);
			if ($target.is("a.ui-icon-circle-close")) {
				removeAttribute($item);
			}
			return false;
		});
		$("button:submit").click(function(event) {
			saveData();
			return false;
		});
		loadData();
	});
</script>
<div class="container">
	<div class="col-md-12">
		<form class="form-horizontal" method="post" role="form">
			<h1><?= __(ucfirst($action)) . ' ' . ucfirst($singular_nomenclature) ?> | <a href="/admin/entities/<?= $controller ?>/edit/<?= $set->id ?>"><?= __('Edit') ?></a> | <a href="/admin/entities/<?= $controller ?>/"><?= __('Documents') ?></a></h1>
			<div class="form-group">
				<label class="col-sm-2 control-label"><?= __('Available Attributes') ?></label>
				<div class="col-sm-10 ui-widget ui-helper-clearfix"  id="attributes-library">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label"><?= __('Used Attributes') ?></label>
				<div class="col-sm-10" id="dataholders">
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-offset-2 col-sm-10 buttons">
					<button type="submit" class="btn btn-primary"><?= __('Save') ?></button>
				</div>
			</div>
		</form>
	</div>
</div>