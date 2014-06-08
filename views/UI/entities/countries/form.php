<?php defined('SYSPATH') or die('No direct script access.');
?>
<div class="container">
	<div class="col-md-12">
		<h1><?=__(ucfirst($action) . ' Country') ?> | <a href="/admin/entities/documents/"><?= __('Countries') ?></a></h1>
		<form class="form-horizontal" method="post" role="form">
			<div class="form-group<?=Form::has_error('country_name', $errors) ? ' has-error' : null?>">
				<label for="document_name" class="col-sm-2 control-label"><?= __('Country Name') ?></label>
				<div class="col-sm-10">
					<?= Form::input("country_name", ($country && $country->loaded() ? $country->attr('country_name')->value : NULL), array('id' => 'country_name', "class" => "form-control")) ?>
					<?= Form::error('country_name', $errors); ?>
				</div>
			</div>
			<div class="form-group<?=Form::has_error('country_code_2', $errors) ? ' has-error' : null?>">
				<label for="document_name" class="col-sm-2 control-label"><?= __('Country Code 2 chars') ?></label>
				<div class="col-sm-10">
					<?= Form::input("country_code_2", ($country && $country->loaded() ? $country->attr('country_code_2')->value : NULL), array('id' => 'country_name', "class" => "form-control")) ?>
					<p class="help-block"><?=__('Country code must meet :iso standarts', array(':iso' => HTML::anchor('http://en.wikipedia.org/wiki/ISO_3166-1_alpha-2', 'ISO 3166-1 alpha-2')))?></p>
					<?= Form::error('country_code_2', $errors); ?>
				</div>
			</div>
			<div class="form-group<?=Form::has_error('country_code_3', $errors) ? ' has-error' : null?>">
				<label for="document_name" class="col-sm-2 control-label"><?= __('Country Code 3 chars') ?></label>
				<div class="col-sm-10">
					<?= Form::input("country_code_3", ($country && $country->loaded() ? $country->attr('country_code_3')->value : NULL), array('id' => 'country_name', "class" => "form-control")) ?>
					<p class="help-block"><?=__('Country code must meet :iso standarts', array(':iso' => HTML::anchor('http://en.wikipedia.org/wiki/ISO_3166-1_alpha-3', 'ISO 3166-1 alpha-3')))?></p>
					<?= Form::error('country_code_3', $errors); ?>
				</div>
			</div>
			<div class="form-group">
				<?php
				if ($country && $country->loaded())
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
		</form>
	</div>
</div>