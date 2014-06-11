<?php defined('SYSPATH') or die('No direct script access.');

/**
 * ORM Model Set
 *
 * @author Petar
 * Created on 2014-3-25
 */ 

class Model_EAV_Set extends Model_EAV_Core_Set {

	public function get_name()
	{
		$parts = explode("_", $this->_object_name);
		$name = NULL;
		foreach ($parts as $part)
		{
			if ($part == 'eav')
				$part = 'EAV';
			
			$name .= ucfirst($part).'_';
		}
		$name = rtrim($name, "_");
		return $name;
	}
}
