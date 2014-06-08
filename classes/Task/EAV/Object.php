<?php defined('SYSPATH') or die('No direct script access.');
 
 /**
 * This is an EAV object model file generator.
 *
 * It can accept the following options:
 *  - model_name: the name of the object model file. It is required.
 *  - table_set_id: db column name for eav_object's set. It is required.
 *  - eav_obj_extends: EAV or EAV_User. It is required.
 *  - set: eav oject set. It is required.
 *
 * @author     Vladimir Dimitrov
 */

class Task_EAV_Object extends Minion_Task
{
	protected $_options = array(
		'model_name' => NULL,
		'table_set_id' => NULL,
		'eav_obj_extends' => NULL,
		'set' => NULL,
	);
 
	public function build_validation(Validation $validation)
	{
		return parent::build_validation($validation)
			->rule('model_name', 'not_empty') 
			->rule('table_set_id', 'not_empty') 
			->rule('eav_obj_extends', 'not_empty') 
			->rule('set', 'not_empty');
	}

	protected function _execute(array $params)
	{
		$model_name = $params['model_name'];
		$table_set_id = $params['table_set_id'];
		$eav_obj_extends =$params['eav_obj_extends'];
		$set = $params['set'];
		$belongs_to_set = Text::ucfirst(substr(Inflector::singular($set), 4, -4));
		$eav_obj_fname = APPPATH . 'classes/Model/EAV/' . $model_name . '.php';
		//Opens the template file and replaces the name
		$eav_obj_template = View::factory('eav/entity', array(
			'model_name' => $model_name, 
			'eav_obj_extends' => $eav_obj_extends,
			'belongs_to_set' => $belongs_to_set,
			'table_set_id' => $table_set_id,
			));

		if (file_put_contents($eav_obj_fname, $eav_obj_template))
		{
			Minion_CLI::write(Minion_CLI::color('Create ' . $eav_obj_fname, 'light_green'));
		}
		else
		{
			Minion_CLI::write(Minion_CLI::color('Error writing ' . $eav_obj_fname, 'red'));
		}
	}
}