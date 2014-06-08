<?php defined('SYSPATH') or die('No direct script access.');

/**
 * ORM Model Type
 *
 * @author Petar
 * Created on 2014-2-27
 */ 

class Model_EAV_Core_Attribute_Type extends ORM {
    protected $_has_many = array(
		'attributes' => array(
			'model' => 'EAV_Attribute',
		),
	);
	
	/**
	 * Return class name of attribute if we are in admin and there isn't selected firm 
	 * it return _default model
	 * @return string class name, eav_model name
	 */
	public function get_class()
	{

		$user = Auth::instance()->get_user(NULL);
		$company_selected = ORM::factory('EAV_Company', Session::instance()->get('company', NULL));
		$is_user_eav = ORM::factory('User_Eav', array('class' => $this->class));
		if ($user AND $user->is_admin() AND ! $company_selected->loaded() AND $is_user_eav->loaded())
		{
			return $this->class . '_Default';
		}
		return $this->class;
	}
}
?>
