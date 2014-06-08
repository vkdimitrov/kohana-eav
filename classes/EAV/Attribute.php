<?php defined('SYSPATH') or die('No direct script access.');

/**
 * ORM Model EAV_Attribute
 *
 * @author Petar
 * Created on 2014-3-20
 */ 

class EAV_Attribute {
    
	/**
	 * Checks if there is a attribute with that name
	 * @param string $name
	 * @return boolean
	 */
	public static function unique_name($name)
	{
		return ! ORM::factory('EAV_Attribute', array('name' => $name))->loaded();
	}
	
}
?>
