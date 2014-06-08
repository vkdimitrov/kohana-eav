<?php defined('SYSPATH') or die('No direct script access.');

/**
 * ORM Model Options
 *
 * @author Petar
 * Created on 2014-3-25
 */ 

class Model_EAV_Core_Attribute_Option extends ORM {
    
	protected $_belongs_to = array(
		'attribute' => array(
			'model' => 'EAV_Attribute',
		)
	);
}
?>
