<?='<?php'?> defined('SYSPATH') or die('No direct script access.');

/**
 * ORM Model <?=$model_name?> 
 *
 * Created on <?=date('Y-m-d')?> 
 */

class Model_EAV_<?=$model_name?> extends <?=$eav_obj_extends?> {
	public $_belongs_to = array(
		'set' => array(
			'model' => 'EAV_<?=$belongs_to_set?>_Set',
			'foreign_key' =>'<?=$table_set_id?>',
		),
	);
}
?>