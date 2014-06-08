<?='<?php'?> defined('SYSPATH') or die('No direct script access.');

/**
 * ORM Model EAV_<?=$model_name?>_Set 
 *
 * Created on <?=date('Y-m-d')?> 
 */

class Model_EAV_<?=$model_name?>_Set extends Model_EAV_Set {
	public $_has_many = array(
		'attributes' => array(
			'model' => 'EAV_Attribute',
			'through' =>'<?=$through_set?>_attributes',
			'far_key' =>'eav_attribute_id',
		),
		'child_sets' => array(
			'model' => 'EAV_<?=$model_name?>_Set',
			'foreign_key' => 'parent_set_id',
		),
		'children' => array(
			'model' => 'EAV_<?=$model_name?>',
			'foreign_key' => 'eav_<?=$child?>_set_id',
		),
	);

	public $_belongs_to = array(
		'parent' => array(
			'model' => 'EAV_<?=$model_name?>_Set',
			'foreign_key' =>'parent_set_id',
		),
	);

	public function rules()
	{
		$ext_rules = array(
			'parent_set_id' => array(
				),
		);
		return Arr::merge(parent::rules(), $ext_rules);
	}

	public function filters()
	{
		return array(
			'name' => array(
				array('trim'),
			),
		);
	}
}
?>
