<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * ORM Model Set
 *
 * @author Petar
 * Created on 2014-3-25
 */
class Model_EAV_Core_Set extends ORM {

	
	public function rules()
	{
		return array(
			'name' => array(
				array('not_empty'),
				array('max_length', array(':value', 45)),
				array('regex', array(':value', '/^[a-z0-9_]*$/')),
				array(array($this, 'unique_name'), array(':value')),
			),
		);
	}

	public function create_set($data)
	{
		$expected = array('name', 'comment', 'parent_set_id');
		if (empty($data['parent_set_id']))
		{
			$data['parent_set_id'] = NULL;
		}

		if ($data['comment'] === '')
		{
			$data['comment'] = NULL;
		}
		
		$this->values($data, array('name', 'parent_set_id', 'comment'));
		$this->save();

		if (isset($data['attributes']))
		{
			foreach ($data['attributes'] as $attribute)
			{
				$this->add('attributes', ORM::factory('EAV_Attribute', $attribute));
			}
		}
	}

	public function update_set($data)
	{
		$expected = array('name', 'comment', 'parent_set_id');
		if (empty($data['parent_set_id']))
		{
			$data['parent_set_id'] = NULL;
		}

		if ($data['comment'] === '')
		{
			$data['comment'] = NULL;
		}

		if (isset($data['eav_country_id']))
		{
			array_push($expected, 'eav_country_id');
		}

		$this->values($data, $expected);
		$this->save();

		if (isset($data['attributes']))
		{
			//current attributes	
			$set_attributes = $this->attributes->find_all()->as_array('id', 'label');
			//removed
			$removed_attributes = array_diff(array_keys($set_attributes), $data['attributes']);
			//added
			$added_attributes = array_diff($data['attributes'], array_keys($set_attributes));
			foreach ($added_attributes as $added_attribute)
			{
				$this->add('attributes', ORM::factory('EAV_Attribute', $added_attribute));
			}
			foreach ($removed_attributes as $removed_attribute)
			{
				$this->remove('attributes', ORM::factory('EAV_Attribute', $removed_attribute));
			}
		}
		else
		{
			DB::delete($this->_has_many['attributes']['through'])->where(Inflector::singular($this->table_name()) . '_id', '=', $this->id)->execute();
			//Debug::output(Database::instance()->last_query);
		}
	}

	/**
	 * Delete set if there arent any child sets, rules, or rows associated with this set
	 * @return bool on success true else false
	 */
	public function delete_set()
	{
		//name of the set to be deleted
		$set_name = EAV_Helper::ORM_name($this->_table_name);
		//load all EAV objects names
		$eavs = ORM::factory('EAV_Attribute_Type')->where('value_type', '=', 'object')->find_all();
		$user_eavs_objects = ORM::factory('User_Eav')->find_all();
		$user_eavs = array();
		$all_credentials = ORM::factory('EAV_Company_Credential')->find_all();
		foreach ($user_eavs_objects as $user_eav)
		{
			array_push($user_eavs, $user_eav->class);
		}
		//check if there are any eav_objects associated with this set
		foreach ($eavs as $eav)
		{
			if (in_array($eav->class, $user_eavs))
			{
				//check for default_values in our db
				$object = EAV::factory($eav->class.'_Default');
				if ($object->_belongs_to['set'] ['model'] == $set_name)
				{
					if ($this->has_rules($eav->class.'_Default'))
						return FALSE;

					$object_rows = $object->where($object->_belongs_to['set']['foreign_key'], '=', $this->id)->find();

					if ($object_rows->loaded())
					{
						return FALSE;
					}
				}
				//$object->clear_cache();

				// walk all users dbs...		
				foreach ($all_credentials as $credentials)
				{
					//$this->session->set('company', $credentials->eav_company_id);
					Session::instance()->set('company', $credentials->eav_company_id);
					$object = EAV::factory($eav->class);

					if ($object->_belongs_to['set'] ['model'] == $set_name)
					{
						if ($this->has_rules($eav->class))
							return FALSE;

						$object_rows = $object->where($object->_belongs_to['set']['foreign_key'], '=', $this->id)->find();

						if ($object_rows->loaded())
						{
							return FALSE;
						}
					}
					$object->clear_cache();
				}
			}
			else
			{
				$object = EAV::factory($eav->class);

				if ($object->_belongs_to['set'] ['model'] == $set_name)
				{
					if ($this->has_rules($eav->class))
						return FALSE;

					$object_rows = $object->where($object->_belongs_to['set']['foreign_key'], '=', $this->id)->find();

					if ($object_rows->loaded())
					{
						return FALSE;
					}
				}
			}
		}
		//check if there are any child sets
		$children = EAV::factory($set_name)->where('parent_set_id', '=', $this->id)->find();
		if ($children->loaded())
		{
			return FALSE;
		}

		//actual delete 
		try
		{
			$db = Database::instance();
			$db->begin();
			$this->delete();
			$db->commit();
			return TRUE;
		} catch (Exception $e)
		{
			$db->rollback();
			return FALSE;
		}
	}

	/**
 	 * Checks if there is a rules for that set and entity name
 	 */
	protected function has_rules($eav_class_name)
	{
		$event_rule = ORM::factory('Event_Rule')
			->join('events')
			->on('events.id', '=', 'event_rule.event_id')
			->where('events.entity', '=', $eav_class_name)
			->and_where('event_rule.set_id', '=', $this->id);
		$event_rule = $event_rule->find();
		if ($event_rule->loaded())
			return TRUE;

		return FALSE;
	}

	/**
	 * Gets both visual strucutre and data for the set
	 * @return array
	 */
	public function get_visual_structure()
	{
		$visual_dataset = array();
		foreach ($this->_visual_dataset as $holder => $structure)
		{
			$visual_dataset[$holder]["set"] = $structure;
			$visual_dataset[$holder]["data"] = null;
		}
		return $this->fill_visual_structure($visual_dataset);
	}

	/**
	 * Fills visual strucutre with set's stored data
	 * @param array $visual_dataset Visual structure
	 * @return array
	 */
	public function fill_visual_structure($visual_dataset)
	{
		$visual_data = unserialize($this->visual_data);
		foreach ($this->_visual_dataset as $holder => $d)
		{
			if (isset($visual_data[$holder]))
			{
				$visual_dataset[$holder]["data"] = $visual_data[$holder];
			}
		}
		return $visual_dataset;
	}

	/**
	 * Sets the stored visual data
	 * @param array $visual_dataset Visual data
	 */
	public function set_visual_data($visual_dataset)
	{
		$visual_data = array();
		foreach ($this->_visual_dataset as $holder => $dataset)
		{
			if ((isset($visual_dataset[$holder])) and (is_array($visual_dataset[$holder])))
			{
				foreach ($visual_dataset[$holder] as $k => $attribute_id)
				{
					if ($attribute_id > 0)
					{
						$visual_data[$holder][$k] = $attribute_id;
					}
				}
			}
		}
		$this->visual_data = serialize($visual_data);
	}

	/**
	 * Check if the name is unique in set table
	 * @param  string $name name of the set - identifier
	 * @return bool       unique - true else false
	 */
	public function unique_name($name)
	{
		return TRUE;
	}

}

?>
