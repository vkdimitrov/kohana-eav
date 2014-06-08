<?php defined('SYSPATH') or die('No direct script access.');
$types = ORM::factory("EAV_Attribute_Type")->find_all();
$parent_attributes = $set->parent->attributes->find_all()->as_array('id', 'label');

$singular_nomenclature = Inflector::singular($controller);
?>

<script>
$(function() {
	$( ".datepicker" ).datepicker({ dateFormat: 'dd.mm.yy' });
});
function redirect() {
  $('#save_and_add').val("1");
}
</script>
<div class="container">
	<div class="col-md-12">		
		<h1><?= __(ucfirst($action)) . ' ' . ucfirst($singular_nomenclature) ?>
			| <?= HTML::anchor("admin/nomenclatures/{$controller}/", __(ucfirst($controller))) ?></h1>
		<form class="form-horizontal" method="post" role="form">
			<?= ($set->has_visual) ? "HAS VISUAL!" : NULL?>
			<?php foreach($attributes as $attribute):  ?>
				<div class="form-group<?= Form::has_error($attribute->name, $errors) ? ' has-error' : null ?>">
					<label for="<?= $attribute->name ?>" class="col-sm-2 control-label"><?= $attribute->label ?></label>
					<div class="col-sm-10">
						<?= EAV_Helper::form($entity, $attribute, array("class" => "form-control")) ?>
					</div>
				</div>
			<?php endforeach; ?>
			<div class="form-group">
				<div class="col-sm-offset-2 col-sm-10">
					<button type="submit" class="btn btn-primary"><?= (isset($set) ? __('Save') : __('Add')) ?></button>
					<input type="hidden" id="save_and_add" name="save_and_add" value="0">
					<button type="submit" onclick="redirect();"  class="btn btn-primary"><?=__('Save And Add')?></button>
				</div>
			</div>
		</form>
	</div>
</div>