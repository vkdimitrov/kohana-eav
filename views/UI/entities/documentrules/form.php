<?php
defined('SYSPATH') or die('No direct script access.');
$events = ORM::factory("EAV_Event")->find_all();
//Debug::output($document_rule);
?>
<div class="container">
    <div class="col-md-12">
        <h1><?= ($document_rule->loaded() ? __('Edit Rule') : __('New Rule')) ?> | <a href="/admin/entities/documentrules"><?= __('Rules') ?></a></h1>
        <form class="form-horizontal" method="post" role="form">
			<hr/>
			<h2><?= __('Rule Settings') ?>&nbsp;<span class="glyphicon-settings glyphicon"></span></h2>
            <div class="form-group <?= Form::has_error('name', $errors) ? 'has-error' : NULL ?>">
                <label for="rule_name" class="col-sm-2 control-label"><?= __('Rule Name') ?></label>
                <div class="col-sm-10">
					<?= Form::input("name", (isset($document_rule) ? $document_rule->name : NULL), array('id' => 'rule_name', "class" => "form-control")); ?>
					<p class="help-block"><?= __('Be as more descriptive as you can, and try to make it unique, at least for the coutry that the rule applies') ?></p>
					<?= Form::error('name', $errors); ?>
                </div>
            </div>
            <div class="form-group <?= Form::has_error('description', $errors) ? 'has-error' : NULL ?>">
                <label for="rule_description" class="col-sm-2 control-label"><?= __('Rule Name') ?></label>
                <div class="col-sm-10">
					<?= Form::textarea("description", (isset($document_rule) ? $document_rule->name : NULL), array('id' => 'rule_description', "class" => "form-control")); ?>
					<p class="help-block"><?= __('Be as more descriptive as you can, and try to describe the whole rule in human words') ?></p>
					<?= Form::error('description', $errors); ?>
                </div>
            </div>
            <div class="form-group <?= Form::has_error('name', $errors) ? 'has-error' : NULL ?>">
                <label for="rule_event" class="col-sm-2 control-label"><?= __('Rule Attached To Event') ?></label>
                <div class="col-sm-10">
					<?php
					if ($document_rule->loaded() and ! in_array("event_id", $document_rule->expected()))
					{
						print Form::input("event_id", $document_rule->event->name, array('id' => 'rule_event', "class" => "form-control rule-settings", "readonly" => "readonly"));
					}
					else
					{
						print Form::select("event_id", $events->as_array('id', 'name'), (isset($document_rule) ? $document_rule->event->id : NULL), array('id' => 'rule_event', "class" => "form-control rule-settings"));
						?><p class="help-block"><?= __('The rule event - for prices, use decimal, for titles - text, etc...') ?></p><?php
					}
					print Form::error("event_id", $errors);
					?>
                </div>
            </div>
            <div class="form-group <?= Form::has_error('name', $errors) ? 'has-error' : NULL ?>">
                <label for="rule_active" class="col-sm-2 control-label"><?= __('Rule Is Active') ?></label>
                <div class="col-sm-10">
					<?= Form::yesno("active", (isset($document_rule) ? $document_rule->active : NULL), TRUE, array('id' => 'rule_active', "class" => "form-control")); ?>
					<p class="help-block"><?= __('The event that will triger this rule') ?></p>
					<?= Form::error('active', $errors); ?>
                </div>
            </div>
            <div class="form-group <?= Form::has_error('name', $errors) ? 'has-error' : NULL ?>">
                <label for="rule_country" class="col-sm-2 control-label"><?= __('Rule For Country') ?></label>
                <div class="col-sm-10">
					<?= Form::input("country", (isset($document_rule) ? $document_rule->country : NULL), array('id' => 'rule_country', "class" => "form-control")); ?>
					<p class="help-block"><?= __('Country Code as: :ahref', array(':ahref' => '<a target="blank" href="http://bg.wikipedia.org/wiki/ISO_3166-1">ISO 3166-1 alpha-2</a>')); ?></p>
					<?= Form::error('country', $errors); ?>
                </div>
            </div>
            <div class="form-group <?= Form::has_error('name', $errors) ? 'has-error' : NULL ?>">
                <label for="rule_name" class="col-sm-2 control-label"><?= __('Rule Weight') ?></label>
                <div class="col-sm-10">
					<?= Form::input("weight", (isset($document_rule) ? $document_rule->weight : NULL), array('id' => 'rule_weight', "class" => "form-control")); ?>
					<p class="help-block"><?= __('The weight gives the rule advantage to another, potentially concurent rule.') ?></p>
					<?= Form::error('weight', $errors); ?>
                </div>
            </div>
            <div class="form-group <?= Form::has_error('name', $errors) ? 'has-error' : NULL ?>">
                <label for="rule_name" class="col-sm-2 control-label"><?= __('Rule Period') ?></label>
                <div class="col-sm-5">
					<?= Form::input("start_date", (isset($document_rule) ? $document_rule->start_date : NULL), array('id' => 'rule_start_date', "class" => "form-control")); ?>
					<p class="help-block"><?= __('The start date whne this rule will apply. By deafault the current date is set') ?></p>
					<?= Form::error('start_date', $errors); ?>
                </div>
                <div class="col-sm-5">
					<?= Form::input("end_date", (isset($document_rule) ? $document_rule->end_date : NULL), array('id' => 'rule_end_date', "class" => "form-control")); ?>
					<p class="help-block"><?= __('The end date until this rule will apply. By deafault is 0000-00-00, which means never') ?></p>
					<?= Form::error('end_date', $errors); ?>
                </div>
            </div>
			<hr/>
			<h2><?= __('Rules') ?>&nbsp;<span class="glyphicon-rules glyphicon"></span></h2>
            <div class="form-group">
                <label for="rule_name" class="col-sm-2 control-label"><?= __('Bulid Rules') ?></label>
                <div class="col-sm-10">
					<p class="help-block"><?= __('Apply the rule only if the following conditions are met') ?></p>
					<div class="conditionscombination">If <?= Form::yesno("active", (isset($document_rule) ? $document_rule->active : NULL), false, array("class" => "form-control")); ?> of these conditions are <?= Form::truefalse("active", (isset($document_rule) ? $document_rule->active : NULL), false, array("class" => "form-control")); ?>:</div>
					<div class="addsubcondition"><a href="#"><span class="glyphicon glyphicon-plus-sign"></span></a></div>
					<div class="subconditionselect">
						<?= Form::select("event_id", $events->as_array('id', 'name'), (isset($document_rule) ? $document_rule->event->id : NULL), array('id' => 'rule_event', "class" => "form-control rule-settings")); ?>
					</div>
					<ul class="subcondition">
						<li>
							<div class="subconditioncontent"></div>
							<div class="removesubcondition"><a><span class="glyphicon glyphicon-minus-sign"></span></a></div>
						</li>
					</ul>
                </div>
				<script>
					$(document).ready(function() {
						
					});
				</script>
            </div>
			<hr/>
			<h2><?= __('Actions') ?>&nbsp;<span class="glyphicon-actions glyphicon"></span></h2>
			<hr/>
			<div class="form-group">
				<div class="col-sm-offset-2 col-sm-10">
					<?php
					if (isset($document_rule) and ($document_rule->name != ""))
					{
						?>
						<button event="submit" class="btn btn-default" value="save_close"><?= __('Save & Close') ?></button>
						<button event="submit" class="btn btn-default" value="save"><?= __('Save') ?></button>
						<?php
					}
					else
					{
						?>
						<button event="submit" class="btn btn-default" value="add"><?= __('Add') ?></button>
						<?php
					}
					?>
                </div>
            </div>
        </form>

    </div>
</div>
