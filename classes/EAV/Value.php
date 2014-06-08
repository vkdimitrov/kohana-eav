<?php defined('SYSPATH') or die('No direct script access.');

/**
 * ORM Model Value
 *
 * @author Petar
 * Created on 2014-2-26
 */ 

class EAV_Value {
    
	public function __construct($value, $attribute)
	{
		$attr_type = $attribute->type;
		$user = Auth::instance()->get_user(NULL);
		$company_selected = ORM::factory('EAV_Company', Session::instance()->get('company', NULL));

		switch ($attr_type->value_type)
		{
			case 'object':
				//echo 'object '.$attr_type->class.' - ' . $attribute->name . "\n";
				$this->value = ORM::factory($attr_type->get_class(), $value);
				break;
			default:
				$this->value = $value;
		}
		$this->attribute = $attribute;
	}

	public function __toString()
	{
		return $this->value;
	}

	public function raw_value()
	{
		switch ($this->attribute->type->value_type)
		{
			case 'object':
				return $this->value->id;
				break;
			default:
				return $this->value;
		}
	}
}
?>
