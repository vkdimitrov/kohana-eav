<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Default EAV Attributes
 *
 * @author Petar
 * Created on 2014-3-25
 */ 

class Model_EAV_Core_Attribute extends ORM {
	protected $_belongs_to = array(
		'type' => array(
			'model' => 'EAV_Attribute_Type',
		),
	);
	
	protected $_has_many = array(
		'options' => array(
			'model' => 'EAV_Attribute_Option',
		),
	);
	
	public function rules()
	{
		return array(
			'name' => array(
				array('not_empty'),
				array('max_length', array(':value', 32)),
				array(array($this, 'unique_name'), array(':value')),
			),
			'type_id' => array(array('not_empty')),
			'obligatory' => array(
				array('regex', array(':value', '(0|1)'))
			),
			'unique' => array(
				array('regex', array(':value', '(0|1)'))
			),
			'validation' => array(
				array('max_length', array(':value', 255))
			),
		);
	}
	
	public function unique_name($name)
	{
		$result = ORM::factory('EAV_Attribute')->where('name', '=', $name);
		if($this->loaded())
			$result = $result->and_where('id', '<>', $this->pk());
		
		return ! $result->find()->loaded();
	}
	
	public function filters()
	{
		return array(
			'name' => array(
				array('trim'),
			 ),
			'validation' => array(
				array('trim'),
			),
			'comment' => array(
				array(function($value) {
					return ($value === '') ? NULL : $value;
				})
			),
		);
	}
	public function checkboxes()
	{
		return array(
			'obligatory',
			'unique',
			'use_in_company',
			'use_in_documents',
			'use_in_document_articles',
			'use_in_tax_rules',
			'use_in_account_pairs',
			'use_in_reporting',
			'use_in_sorting',
			'show_in_listing',
			'show_in_view',
			'use_in_document_rules',
			'use_in_totaling_rules',
			'use_in_accounting_rules',
			);
	}
	public function expected()
	{
		$edit = array(
			'default',
			'obligatory',
			'comment',
			'unique',
			'validation',
			'use_in_company',
			'use_in_documents',
			'use_in_document_articles',
			'use_in_tax_rules',
			'use_in_account_pairs',
			'use_in_reporting',
			'use_in_sorting',
			'show_in_listing',
			'show_in_view',
			'use_in_document_rules',
			'use_in_totaling_rules',
			'use_in_accounting_rules',
		);
		
		$add = array_merge($edit, array(
			'name',
			'type_id',
			'label',
		));
		
		return $this->loaded() ? $edit : $add;
	}
}
?>
