<?php
defined('SYSPATH') or die('No direct script access.');
$types = ORM::factory("EAV_Attribute_Type")->find_all();
if (isset($set->parent)) {
$parent_attributes = $set->parent->attributes->find_all()->as_array('id', 'label');
$errors = NULL;
}
$singular_nomenclature = ucfirst(substr($entity, 4));
?>

<div class="container">
	<div class="col-md-12">		
		<h1><?= HTML::anchor("/eav_entities/index/{$entity}", 'Back') ?> | <?= ucfirst($singular_nomenclature) ?></h1>
		<form class="form-horizontal" method="post" role="form">
			<div class="form-group<?= Form::has_error('name', $errors) ? ' has-error' : null ?>">
				<label for="<?= $singular_nomenclature ?>_name" class="col-sm-2 control-label"><?= __(ucfirst($singular_nomenclature) . ' Identifier') ?></label>
				<div class="col-sm-10">
<?= Form::input("name", (isset($set) ? $set->name : null), array('id' => $singular_nomenclature . '_name', "class" => "form-control")) ?>
					<p class="help-block"><?=__('Has to be unique! Only lowercase, numbers and underscore are allowed! Example: bg_order_1')?></p>
<?= Form::error('name', $errors); ?>
				</div>
			</div>
			<div class="form-group<?= Form::has_error('parent_set_id', $errors) ? ' has-error' : null ?>">
				<label for="<?= $singular_nomenclature ?>_parent" class="col-sm-2 control-label"><?= __('Parent ' . ucfirst($singular_nomenclature)) ?></label>
				<div class="col-sm-10">
<?= Form::select("parent_set_id", $sets, (isset($set) ? $set->parent_set_id : null), array('id' => $singular_nomenclature . '_parent', "class" => "form-control")) ?>
			<?= Form::error('parent_set_id', $errors); ?>
				</div>
			</div>
			<?php
			if (array_key_exists('eav_country_id', $set->as_array())) :
				?>
				<div class="form-group<?= Form::has_error('country', $errors) ? ' has-error' : null ?>">
					<label for="<?= $singular_nomenclature ?>_country" class="col-sm-2 control-label"><?= __(ucfirst($singular_nomenclature) . ' Country') ?></label>
					<div class="col-sm-10">
						<?= Form::select("eav_country_id", $countries, (isset($countries) ? $set->eav_country_id : null), array('id' => $singular_nomenclature . '_country', "class" => "form-control")) ?>
						<p class="help-block">The country where this nomenclature operates</p>
				<?= Form::error('country', $errors); ?>
					</div>
				</div>
<?php endif; ?>
			<div class="form-group<?= Form::has_error('comment', $errors) ? ' has-error' : null ?>">
				<label for="comment" class="col-sm-2 control-label"><?= __(' Comment') ?></label>
				<div class="col-sm-10">
<?= Form::input("comment", (isset($set) ? $set->comment : null), array('id' => 'comment', "class" => "form-control")) ?>
<?= Form::error('comment', $errors); ?>
				</div>
			</div>
			<hr>
			<div class="form-group">
			<h2 class="col-sm-2 control-label"><?=__('Attributes')?></h2>
			<div class="col-sm-10">
				<? foreach($all_attributes as $attribute): ?>
					<div class="checkbox col-sm-12 col-md-4">
						<label>
							<?= Form::checkbox('attributes[]', $attribute->id, (in_array($attribute->id, $set_attributes)) ? TRUE : FALSE) ?> <?= $attribute->name ?>
						</label>
					</div>
				<? endforeach; ?>
			</div>
             </div>
			<div class="form-group">
				<div class="col-sm-offset-2 col-sm-10">
					<button type="submit" class="btn btn-primary"><?= (isset($set) ? __('Save') : __('Add')) ?></button>
				</div>
			</div>
		</form>
	</div>
</div>
