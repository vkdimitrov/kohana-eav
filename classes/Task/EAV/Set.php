<?php defined('SYSPATH') or die('No direct script access.');
 
 /**
 * This is an EAV_Set model file generator.
 *
 * It can accept the following options:
 *  - model_name: the name of the object model file. It is required.
 *  - set: eav oject set. It is required.
 *  
 * @author     Vladimir Dimitrov
 */

class Task_EAV_Set extends Minion_Task
{
	protected $_options = array(
		'model_name' => NULL,
		'set' => NULL,
	);
 
	public function build_validation(Validation $validation)
	{
		return parent::build_validation($validation)
			->rule('model_name', 'not_empty') 
			->rule('set', 'not_empty');
	}

	protected function _execute(array $params)
	{
		$model_name = $params['model_name'];
		$set = $params['set'];
		$through_set = Inflector::singular($set);
		$child = strtolower($model_name);

		mkdir(APPPATH . 'classes/Model/EAV/' . $model_name);
		$eav_obj_set_fname = APPPATH . 'classes/Model/EAV/' . $model_name . '/Set.php';
		//Opens the template file and replaces the name
		$eav_obj_set_template = View::factory('eav/set', array(
			'model_name' => $model_name, 
			'through_set' => $through_set,
			'child'	=> $child,
			));

		if (file_put_contents($eav_obj_set_fname, $eav_obj_set_template))
		{
			Minion_CLI::write(Minion_CLI::color('Create ' . $eav_obj_set_fname, 'light_green'));
		}
		else
		{
			Minion_CLI::write(Minion_CLI::color('Error writing ' . $eav_obj_set_fname, 'red'));
		}

		mkdir(APPPATH . 'classes/Model/EAV/' . $model_name .'/Set');
		$eav_obj_set_attr_fname = APPPATH . 'classes/Model/EAV/' . $model_name . '/Set/Attribute.php';
		$eav_obj_set_attr_data  = "<?php defined('SYSPATH') or die('No direct script access.'); \n\n";
		//Opens the template file and replaces the name
		$eav_obj_set_attr_template = View::factory('eav/set_attribute', array('model_name' => $model_name));

		if (file_put_contents($eav_obj_set_attr_fname, $eav_obj_set_attr_template))
		{
			Minion_CLI::write(Minion_CLI::color('Create ' . $eav_obj_set_attr_fname, 'light_green'));
		}
		else
		{
			Minion_CLI::write(Minion_CLI::color('Error writing ' . $eav_obj_set_attr_fname, 'red'));
		}
	}
}