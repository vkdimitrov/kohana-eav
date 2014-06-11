<?php
defined('SYSPATH') or die('No direct script access.');
$types = ORM::factory("EAV_Attribute_Type")->find_all();
if (isset($set->parent)) {
$parent_attributes = $set->parent->attributes->find_all()->as_array('id', 'label');
$errors = NULL;
}

$singular_nomenclature = ucfirst(substr($entity, 4));
?>

<h1><?= HTML::anchor("/eav_entities/index/{$entity}", 'Back') ?> | <?= $set_name ?></h1>
<?= Form::open()?>
	<label for="<?= $set_name ?>_name" ><?= $set_name . ' Identifier' ?></label>
	<?= Form::input("name", (isset($set) ? $set->name : null), array('id' => $set_name . '_name', "class" => "form-control")) ?>
	<p><?=__('Has to be unique! Only lowercase, numbers and underscore are allowed! Example: bg_order_1')?></p>
	<?= Form::error('name', $errors); ?>

	<label for="<?= $singular_nomenclature ?>_parent" ><?= __('Parent ' . ucfirst($singular_nomenclature)) ?></label>
	<?= Form::select("parent_set_id", $sets, (isset($set) ? $set->parent_set_id : null), array('id' => $singular_nomenclature . '_parent', )) ?>
	<?= Form::error('parent_set_id', $errors); ?>
	<br>

	<label for="comment"> <?= __(' Comment') ?> </label>
	<?= Form::input("comment", (isset($set) ? $set->comment : null), array('id' => 'comment')) ?>
	<?= Form::error('comment', $errors); ?>
	<hr>

	<h2 ><?=__('Attributes')?></h2>
	<? foreach($all_attributes as $id => $value): ?>
	<?= Form::checkbox('attributes[]', $id, (array_key_exists($id, $set_attributes)) ? TRUE : FALSE, NULL) ?> <?= $value ?>
	<? endforeach; ?>
	<br>
	<button type="submit"><?= (isset($set) ? __('Save') : __('Add')) ?></button>
<?= Form::close()?>