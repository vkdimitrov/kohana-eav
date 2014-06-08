<?php defined('SYSPATH') or die('No direct script access.');
 
 /**
 * This is an EAV object generator.
 *
 * It can accept the following options:
 *  - name: the name of the object. It is required.
 *
 * @author     Vladimir Dimitrov
 */

class Task_EAV_Create extends Minion_Task
{
	protected $_options = array(
		'name' => NULL,
	);
 
	public function build_validation(Validation $validation)
	{
		return parent::build_validation($validation)
			->rule('name', 'not_empty') 
			->rule('name', 'alpha_numeric');
	}

	protected function _execute(array $params)
	{
		Minion_CLI::write();
		//names
		$name               = strtolower($params['name']);
		$eav_table          = "eav_" . Inflector::plural($name);
		$eav_table_sets     = "eav_" . Inflector::singular($name) . "_sets";
		$eav_table_set_attr = Inflector::singular($eav_table_sets). "_attributes";
		$model_name         = Text::ucfirst($name);

		//get all sets
		$table_names = Database::instance('default')->list_tables();
		$sets = Array($eav_table_sets);
		foreach ($table_names as $table)
		{
			if (substr($table, -4) == 'sets' AND substr($table, 0, 3) == 'eav')
				array_push($sets, $table);
		}
		//create EAV folder in Model
		if ( ! is_dir(APPPATH . 'classes/Model/EAV/'))
		{
			mkdir(APPPATH . 'classes/Model/EAV/');	
		}

		//create new set or use existing
		Minion_CLI::write(Minion_CLI::color('Create dedicated ' . $eav_table_sets . ' or choose one of the existing:', 'light_green'));
		$count = 0;
		foreach ($sets as $set)
		{
			Minion_CLI::write(Minion_CLI::color( $count.') '.$set, 'light_green'));
			$count++;
		}   
		$eav_obj_set = Minion_CLI::read('',range(0, $count-1));

		//eav_obj file + db

		$eav_obj_table_set_id = Inflector::singular($sets[$eav_obj_set])."_id";
		
		$eav_obj_extends = "EAV";
		$dbname = Kohana::$config->load('database.default.connection.database');
		Procedure::create_eav($dbname, $eav_table, $eav_obj_table_set_id);

		Minion_Task::factory(array('task' => 'EAV:Object',
				 'model_name' => $model_name,
				 'table_set_id' => $eav_obj_table_set_id,
				 'eav_obj_extends' => $eav_obj_extends,
				 'set' => $sets[$eav_obj_set],
				  ))->execute();
		
		//eav_obj_sets file & eav_obj_set_attributes file + db
		if ($eav_obj_set == 0)
		{
			Minion_Task::factory(array('task' => 'EAV:Set',
							 'model_name' => $model_name,
							 'set' => $sets[$eav_obj_set],
							  ))->execute();

			Procedure::create_eav_set_wo_country($eav_table_sets);
			
			//create new set db
			Procedure::create_eav_set_attributes($eav_table_set_attr, $eav_obj_table_set_id, $eav_table_sets);
		}

		//insert new attribute type
		$attr_type = ORM::factory('EAV_Attribute_Type');
		$attr_type->label 		= $model_name;
		$attr_type->name 		= $name;
		$attr_type->db_type 	= 'int';
		$attr_type->value_type 	= 'object';
		$attr_type->class 		= 'EAV_' . $model_name;
		$attr_type->save();
	}


}