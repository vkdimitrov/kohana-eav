<?php
defined('SYSPATH') or die('No direct script access.');
$types = ORM::factory("EAV_Attribute_Type")->order_by('value_type')->find_all();
$readonly = $attribute->loaded() ? array("readonly" => "readonly") : array();

?>
<div class="container">
	<div class="col-md-12">
		<h1><a href="/eav"><?= __('Back') ?></a> | <?= ($attribute->loaded() ? __('Edit Attribute') : __('New Attribute')) ?></h1>
		<form class="form-horizontal" method="post" role="form">
			<?php
			if (isset($attribute) and ($attribute->name != ""))
			{
				?><hr/>
				<h2><?= __('Attribute Labels & Values') ?>&nbsp;<span class="glyphicon-values glyphicon"></span></h2>
				<hr/>
				<ul class="nav nav-pills">
					<li class="dropdown">
						<a class="dropdown-toggle" data-toggle="dropdown" href="#">
							<?= __('Language') ?> <span class="caret"></span>
						</a>
						<ul class="dropdown-menu">
							<?php
							foreach ($languages as $lang => $language)
							{
								?><li><a href="#" class="language-set language-set-<?= $lang ?>" data="<?= $lang ?>"><?= __($language) ?></a></li><?php
							}
							?>
						</ul>
					</li>
					<li class="navbar-right"><a href="#"><?= __('Add Language') ?></a></li>
				</ul>
				<?php
				foreach ($languages as $lang => $language)
				{
					?><div class="language-values language-values-<?= $lang ?>"><?php
					switch ($attribute->type->value_type)
					{
						case "basic":
							?>

								<div class="form-group <?= Form::has_error('language-values-' . $lang . '-label', $errors) ? 'has-error' : null ?>">
									<label for="language-values-<?= $lang ?>-label" class="col-sm-2 control-label"><?= __('Label (:language)', array(':language' => __($language))) ?></label>
									<div class="col-sm-10">
										<?= Form::input("label[{$lang}]", (isset($attribute) ? $attribute->get('label', $language) : null), array('id' => "language-values-{$lang}-label", "class" => "form-control attribute-labels")); ?>
										<?= Form::error("language-values-{$lang}-label", $errors); ?>
									</div>
								</div>
								<?php
								break;
							case "select":
								?>
								<div class="form-group <?= Form::has_error('language-values-' . $lang . '-label', $errors) ? 'has-error' : null ?>">
									<label for="language-values-<?= $lang ?>-label" class="col-sm-2 control-label"><?= __('Label (:language)', array(':language' => __($language))) ?></label>
									<div class="col-sm-10">
										<?= Form::input("label[{$lang}]", (isset($attribute) ? $attribute->name : null), array('id' => "language-values-{$lang}-label", "class" => "form-control attribute-labels")); ?>
										<?= Form::error("language-values-{$lang}-label", $errors); ?>
									</div>
								</div>
								<hr>
								<?php
								foreach ($attribute->options->find_all() as $option)
								{
									?>
									<div class="form-group <?= Form::has_error('language-values-' . $lang . '-label', $errors) ? 'has-error' : null ?>">
										<label for="language-values-<?= $lang ?>-label" class="col-sm-2 control-label"><?= __('Label (:language)', array(':language' => __($language))) ?></label>
										<div class="col-sm-10">
											<?= Form::input("value[{$lang}]",  $option->value, array('id' => "language-values-{$lang}-label", "class" => "form-control attribute-labels")); ?>
											<?= Form::error("language-values-{$lang}-label", $errors); ?>
										</div>
									</div>
									<?php
								}
								?>
								<div class="attribute-labels-<?= $lang ?>-new">
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label"><?= __('New Value') ?></label>
									<div class="col-sm-10">
										<a href="#" class="add-value"><span class="glyphicon glyphicon-plus"></span></a>
									</div>
								</div>
								<?php
								break;
						}
						?></div><?php
				}
			}
			?>
			<hr/>
			<h2><?= __('Attribute Settings') ?>&nbsp;<span class="glyphicon-settings glyphicon"></span></h2>
			<div class="form-group <?= Form::has_error('name', $errors) ? 'has-error' : null ?>">
				<label for="attribute_name" class="col-sm-2 control-label"><?= __('Attribute Name') ?></label>
				<div class="col-sm-10">
					<?php
					if ($attribute->loaded() and ! in_array("name", $attribute->expected()))
					{
						print Form::input("name", (isset($attribute) ? $attribute->name : null), array('id' => 'attribute_name', "class" => "form-control", "readonly" => "readonly"));
					}
					else
					{
						print Form::input("name", (isset($attribute) ? $attribute->name : null), array('id' => 'attribute_name', "class" => "form-control"));
						?><p class="help-block"><?= __('The attribute name is an unique field and cannot be edited. Use country code when it will be used in specific country only, the entity when it will be used in a specific entity and the name and any other data to be sure it will be unique. Example: bg_company_id') ?></p><?php
					}
					print Form::error('name', $errors);
					?>
				</div>
			</div>
			<div class="form-group">
				<label for="attribute_type" class="col-sm-2 control-label"><?= __('Attribute Type') ?></label>
				<div class="col-sm-10">
					<?php
					if ($attribute->loaded() and ! in_array("type_id", $attribute->expected()))
					{
						print Form::input("type_id", $attribute->type->name, array('id' => 'attribute_type', "class" => "form-control attribute-settings", "readonly" => "readonly"));
					}
					else
					{
						print Form::select("type_id", $types->as_array('id', 'label'), (isset($attribute) ? $attribute->type->id : null), array('id' => 'attribute_type', "class" => "form-control attribute-settings"));
						?><p class="help-block"><?= __('The attribute type - for prices, use decimal, for titles - text, etc...') ?></p><?php
					}
					print Form::error("type_id", $errors);
					?>
				</div>
			</div>
			<div class="form-group" >
				<label for="default" class="col-sm-2 control-label"><?= __('Default') ?></label>
				<div class="col-sm-10">
					<?= Form::input("default", (isset($attribute) ? $attribute->default : null), array('id' => 'default', "class" => "form-control attribute-settings")) ?>
					<p class="help-block"><?= __('Default') ?></p>
					<?= Form::error("default", $errors); ?>
				</div>
			</div>			<div class="form-group">
				<div class="col-sm-offset-2 col-sm-10">
					<div class="checkbox">
						<label>
							<?= Form::checkbox("unique", "1", (isset($attribute) && $attribute->unique), array("class" => "attribute-settings")) ?> <?= __('Attribute Is Unique') ?>
							<?= Form::error("unique", $errors); ?>
						</label>
					</div>
				</div>
			</div>
			<div class="form-group <?= Form::has_error('obligatory', $errors) ? 'has-error' : null ?>">
				<div class="col-sm-offset-2 col-sm-10">
					<div class="checkbox">
						<label>
							<?= Form::checkbox("obligatory", "1", (isset($attribute) && $attribute->obligatory), array("class" => "attribute-settings")) ?> <?= __('Attribute Is Obligatory') ?>
							<?= Form::error("obligatory", $errors); ?>
						</label>
					</div>
				</div>
			</div>
			<div class="form-group">
				<label for="comment" class="col-sm-2 control-label"><?= __('Comment') ?></label>
				<div class="col-sm-10">
					<?= Form::input("comment", (isset($attribute) ? $attribute->comment : null), array('id' => 'comment', "class" => "form-control attribute-settings")) ?>
					<p class="help-block"><?= __('Attribute Comment') ?></p>
					<?= Form::error("comment", $errors); ?>
				</div>
			</div>
			<div class="form-group">
				<label for="attribute_validation" class="col-sm-2 control-label"><?= __('Attribute Validation') ?></label>
				<div class="col-sm-10">
					<?= Form::input("validation", (isset($attribute) ? $attribute->validation : null), array('id' => 'attribute_validation', "class" => "form-control attribute-settings")) ?>
					<p class="help-block"><?= __('Rules for user input validation. Regular expressions are used.') ?></p>
					<?= Form::error("validation", $errors); ?>
				</div>
			</div>
			<div class="form-group">
				<label for="attribute_type" class="col-sm-2 control-label"><?= __('Attribute Type') ?></label>
				<div class="col-sm-3">
<!-- 					<div class="col-sm-offset-2 col-sm-10">
						<div class="checkbox">
							<label>
								<?= Form::checkbox("use_in_company", "1", (isset($attribute) && $attribute->use_in_company), array("class" => "attribute-settings")) ?> <?= __('Attribute Use In Company') ?>
								<?= Form::error("use_in_company", $errors); ?>
							</label>
						</div>
					</div>
					<div class="col-sm-offset-2 col-sm-10">
						<div class="checkbox">
							<label>
								<?= Form::checkbox("use_in_documents", "1", (isset($attribute) && $attribute->use_in_documents), array("class" => "attribute-settings")) ?> <?= __('Attribute Use In Documents') ?>
								<?= Form::error("use_in_documents", $errors); ?>
							</label>
						</div>
					</div>
					<div class="col-sm-offset-2 col-sm-10">
						<div class="checkbox">
							<label>
								<?= Form::checkbox("use_in_document_articles", "1", (isset($attribute) && $attribute->use_in_document_articles), array("class" => "attribute-settings")) ?> <?= __('Attribute Use In Document Articles') ?>
								<?= Form::error("use_in_document_articles", $errors); ?>
							</label>
						</div>
					</div>
					<div class="col-sm-offset-2 col-sm-10">
						<div class="checkbox">
							<label>
								<?= Form::checkbox("use_in_account_pairs", "1", (isset($attribute) && $attribute->use_in_account_pairs), array("class" => "attribute-settings")) ?> <?= __('Attribute Use In Accounting') ?>
								<?= Form::error("use_in_account_pairs", $errors); ?>
							</label>
						</div>
					</div>

				</div>
				<div class="col-sm-4">
					<div class="col-sm-offset-2 col-sm-10">
						<div class="checkbox">
							<label>
								<?= Form::checkbox("use_in_document_rules", "1", (isset($attribute) && $attribute->use_in_document_rules), array("class" => "attribute-settings")) ?> <?= __('Attribute Use In Document Rules') ?>
								<?= Form::error("use_in_document_rules", $errors); ?>
							</label>
						</div>
					</div>
					<div class="col-sm-offset-2 col-sm-10">
						<div class="checkbox">
							<label>
								<?= Form::checkbox("use_in_totaling_rules", "1", (isset($attribute) && $attribute->use_in_totaling_rules), array("class" => "attribute-settings")) ?> <?= __('Attribute Use In Totaling Rules') ?>
								<?= Form::error("use_in_totaling_rules", $errors); ?>
							</label>
						</div>
					</div>
					<div class="col-sm-offset-2 col-sm-10">
						<div class="checkbox">
							<label>
								<?= Form::checkbox("use_in_tax_rules", "1", (isset($attribute) && $attribute->use_in_tax_rules), array("class" => "attribute-settings")) ?> <?= __('Attribute Use In Tax Rules') ?>
								<?= Form::error("use_in_tax_rules", $errors); ?>
							</label>
						</div>
					</div>
					<div class="col-sm-offset-2 col-sm-10">
						<div class="checkbox">
							<label>
								<?= Form::checkbox("use_in_accounting_rules", "1", (isset($attribute) && $attribute->use_in_accounting_rules), array("class" => "attribute-settings")) ?> <?= __('Attribute Use In Accounting Rules') ?>
								<?= Form::error("use_in_accounting_rules", $errors); ?>
							</label>
						</div>
					</div>
				</div>
				<div class="col-sm-3">
					<div class="col-sm-offset-2 col-sm-10">
						<div class="checkbox">
							<label>
								<?= Form::checkbox("use_in_reporting", "1", (isset($attribute) && $attribute->use_in_reporting), array("class" => "attribute-settings")) ?> <?= __('Attribute Use In Reporting') ?>
								<?= Form::error("use_in_reporting", $errors); ?>
							</label>
						</div>
					</div> -->
					<div class="col-sm-offset-2 col-sm-10">
						<div class="checkbox">
							<label>
								<?= Form::checkbox("show_in_listing", "1", (isset($attribute) && $attribute->show_in_listing), array("class" => "attribute-settings")) ?> <?= __('Attribute Use In Listing') ?>
								<?= Form::error("show_in_listing", $errors); ?>
							</label>
						</div>
					</div>
<!-- 					<div class="col-sm-offset-2 col-sm-10">
						<div class="checkbox">
							<label>
								<?= Form::checkbox("use_in_sorting", "1", (isset($attribute) && $attribute->use_in_sorting), array("class" => "attribute-settings")) ?> <?= __('Attribute Use In Sorting') ?>
								<?= Form::error("use_in_sorting", $errors); ?>
							</label>
						</div>
					</div> -->
<!-- 					<div class="col-sm-offset-2 col-sm-10">
						<div class="checkbox">
							<label>
								<?= Form::checkbox("show_in_view", "1", (isset($attribute) && $attribute->show_in_view), array("class" => "attribute-settings")) ?> <?= __('Attribute Show In View') ?>
								<?= Form::error("show_in_view", $errors); ?>
							</label>
						</div>
					</div> -->

				</div>
			</div>
			<hr/>
			<div class="form-group">
				<div class="col-sm-offset-2 col-sm-10">
					<?php
					if (isset($attribute) and ($attribute->name != ""))
					{
						?>
						<button type="submit" class="btn btn-default" value="save_close"><?= __('Save & Close') ?></button>
						<button type="submit" class="btn btn-default" value="save"><?= __('Save') ?></button>
						<?php
					}
					else
					{
						?>
						<button type="submit" class="btn btn-default" value="add"><?= __('Add') ?></button>
						<?php
					}
					?>
				</div>
			</div>
		</form>
		<?php
		if (isset($attribute) and ($attribute->name != ""))
		{
			?>
			<script>
				$(document).ready(function() {
					var form_has_changes = false;
					var form_settings_has_changes = false;
					var form_settings_changes = Array();
					var form_values_has_changes = false;
					var form_values_changes = Array();
					var form_changes = Array();
					var new_value_index = 0;
					$("div.language-values").hide()
					$("div.language-values-0").show();
					var current_language = "0";
					$(".language-set").click(function(event) {
						var current_element = "div.language-values-" + current_language;
						var new_language = $(this).attr("data");
						var new_element = "div.language-values-" + new_language;
						$(current_element).hide();
						$(new_element).show();
						current_language = new_language;
						event.preventDefault();
					});

					$(".remove-value").click(function(event) {
						remove_value(event, obj);
					});
					$("input[type=text]").change(function(event) {
						change_input(event, this);
					});
					$("input[type=checkbox]").click(function(event) {
						change_checkbox(event, this);
					});
					$(".add-value").click(function(event) {
	<?php
	foreach ($languages as $lang => $language)
	{
		?>
							var input_id = 'attribute-value-<?= $lang ?>-new-' + new_value_index;
							$(".attribute-labels-<?= $lang ?>-new").append('<div id="attribute-labels-<?= $lang ?>-new-' + new_value_index + '" class="form-group">' +
									'<label for="' + input_id + '" class="col-sm-2 control-label">' +
									'<a href="#" class="remove-value"><span class="glyphicon glyphicon-minus"></span></a>&nbsp;' +
									'<?= __('Value (:language)', array(':language' => __($language))) ?>' +
									'</label>' +
									'<div class=\"col-sm-10\">' +
									'<input type="text" id="' + input_id + '" name="value[new][<?= $lang ?>][' + new_value_index + ']" value="" class="form-control attribute-settings" data_original="">' +
									'</div>' +
									'</div>');
							console.debug(input_id);
							form_values_changes[input_id] = true;
		<?php
	}
	?>
						new_value_index++;
						$(".remove-value").click(function(event) {
							remove_value(event, this);
						});
						$("input[type=text]").change(function(event) {
							change_input(event, this);
						});
						update_chganges();
						event.preventDefault();
					});

					function remove_value(event, obj) {
						var id = "#" + $(obj).parent().parent().attr("id");
						var res = id.split("-");
						var new_value_index = res[4];
	<?php
	foreach ($languages as $lang => $language)
	{
		?>
							var div_id = '#attribute-labels-<?= $lang ?>-new-' + new_value_index;
							var input_id = 'attribute-value-<?= $lang ?>-new-' + new_value_index;
							console.debug(div_id);
							var div = $(div_id);
							div.remove();
							form_values_changes[input_id] = false;
		<?php
	}
	?>
						update_chganges();
						event.preventDefault();
					}

					function change_checkbox(event, obj) {
						var type = $(obj).hasClass("attribute-settings") ? "attribute-settings" : "";
						var data_original = $(obj).attr("data_original");
						var is_checked = $(obj).is(':checked') ? "checked" : "";
						console.debug(data_original);
						console.debug(is_checked);
						if (type == "attribute-settings") {
							form_settings_changes[$(obj).attr("id")] = false;
							if (data_original != is_checked) {
								form_settings_changes[$(obj).attr("id")] = true;
							}
						}
						update_chganges();
					}

					function change_input(event, obj) {
						var type = $(obj).hasClass("attribute-settings") ? "attribute-settings" : "attribute-labels";
						var data_original = $(obj).attr("data_original");
						var data_new = $(obj).attr("value");
						if (type == "attribute-settings") {
							form_settings_changes[$(obj).attr("id")] = false;
							if (data_original != data_new) {
								form_settings_changes[$(obj).attr("id")] = true;
							}
						} else if (type == "attribute-labels") {
							form_values_changes[$(obj).attr("id")] = false;
							if (data_original != data_new) {
								form_values_changes[$(obj).attr("id")] = true;
							}
						}
						update_chganges();
					}

					function update_chganges() {
						form_has_changes = false;
						form_settings_has_changes = false;
						form_values_has_changes = false;
						for (key in form_values_changes) {
							if (form_values_changes[key]) {
								form_has_changes = true;
								form_values_has_changes = true;
							}
						}
						for (key in form_settings_changes) {
							if (form_settings_changes[key]) {
								form_has_changes = true;
								form_settings_has_changes = true;
							}
						}
						if (form_has_changes) {
							if (form_values_has_changes) {
								$("span.glyphicon-values").addClass("glyphicon-floppy-save");
							} else {
								$("span.glyphicon-values").removeClass("glyphicon-floppy-save");
							}
							if (form_settings_has_changes) {
								$("span.glyphicon-settings").addClass("glyphicon-floppy-save");
							} else {
								$("span.glyphicon-settings").removeClass("glyphicon-floppy-save");
							}
						} else {
							$("span.glyphicon-values").removeClass("glyphicon-floppy-save");
							$("span.glyphicon-settings").removeClass("glyphicon-floppy-save");
						}
					}
				});


			</script>
			<?php
		}
		?>
	</div>
</div>