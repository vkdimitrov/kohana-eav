<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * ORM Model EAV
 *
 * @author Petar
 * Created on 2014-2-26
 */
class EAV extends ORM {

	protected $_belongs_to = array(
		'set' => array(
		),
	);

	/**
	 * Flag that registers if attribute values are loaded.
	 * @var boolean 
	 */
	protected $attributes_loaded = FALSE;

	/*
	 * Changed attributes through attr()
	 * @var array
	 */
	protected $attributes_changed = array();

	/**attributes_loaded
	 * Array of attributes and their values (array of EAV_Value objects)
	 * @var Ambiguous
	 */
	protected $attributes = NULL;
	
	/**
	 * Hold search conditions
	 * @var array
	 */
	protected $_pending_conditions = array();

	/**
	 * Alias of ORM::factory()
	 * @param type $model the name of the ORM class
	 * @param type $id ID of the ORM object you want to load
	 * @return ORM Object
	 */
	public static function factory($model, $id = NULL)
	{
		return ORM::factory($model, $id);
	}
	
	public static function as_array_attr($objects, $arr_key, $arr_value)
	{

		$result = array();
		foreach ($objects as $obj)
		{
			if ( ! $obj->has_attr($arr_key) AND ! $obj->has_attr($arr_value))
				return $objects->as_array($arr_key, $arr_value);

			if ($obj->has_attr($arr_key))
				$key = $obj->attr($arr_key)->raw_value();
			else
				$key = $obj->$arr_key;
			
			if ($obj->has_attr($arr_value))
				$value = $obj->attr($arr_value)->raw_value();
			else
				$value = $obj->$arr_value;

			$result[$key] = $value;

		}

		return $result;
	}

	/**
	 * Generates the eav model values table name
	 * @param Model_EAV_Attribute $eav_attribute
	 * @return string $eav_values_table_name
	 * @throws EAV_Exception
	 */
	public function get_attribute_value_table_name($eav_attribute)
	{
		if ( ! $this->loaded() OR ! $eav_attribute->loaded() OR ! ($eav_attribute instanceof Model_EAV_Attribute))
			throw new EAV_Exception;

		$eav_values_table_name = Inflector::singular($this->_table_name) . '_attribute_value_' . strtolower($eav_attribute->type->name);
		return $eav_values_table_name;
	}
	
	/**
	 * A Procedure Function that create a value table in user/core database
	 * @param Model_EAV_Attribute $eav_attribute
	 */
	protected function create_value_table($eav_attribute)
	{
		$value_table_name		= $this->get_attribute_value_table_name($eav_attribute);
		$entity_foreign_key		= $this->_object_name . '_id';
		$attribute_foreign_key	= 'eav_attribute_id';
		$result = DB::query(NULL, ""
				. "CREATE TABLE IF NOT EXISTS {$value_table_name} ("
				. "id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT, "
				. "{$entity_foreign_key} INT(10) UNSIGNED NOT NULL, "
				. "{$attribute_foreign_key} INT(10) UNSIGNED NOT NULL, "
				. 'value ' . $eav_attribute->type->db_type . ' NOT NULL, '
				. 'PRIMARY KEY (`id`) '
				.  ') ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=0 ;'
		)->execute($this->_db);
	}
	/**
	 * Gets or sets an attribute
	 * @param string $name
	 * @param unknown $value
	 * @return Ambigious <mixed, array>
	 * @throws EAV_Exception
	 */
	public function attr($name = NULL, $value = NULL)
	{
		if ( ! $name) // returns all of the attributes
		{
			if ( ! $this->attributes_loaded)
			{
				$this->load_attributes();
			}
			return $this->attributes;
		}

		if ( ! $this->has_attr($name))
			throw new EAV_Exception;

		if (isset($value)) // add the value to attributes_changed	
		{
				$this->attributes_changed[$name] = $value;
		}
		else // returns a signle attribute
		{
			return $this->get_attribute($name);
		}
	}

	/**
	 * Loads attributes of that model to the array $this->attributes.
	 * If attributes are loaded successfully sets the flag attributes_loaded to TRUE
	 */
	protected function load_attributes()
	{
		$eav_attributes = $this->get_set_attributes();
		$this->attributes = $eav_attributes->as_array('name', NULL);
		
			foreach ($eav_attributes as $attribute)
			{
				$this->attributes[$attribute->name] = new EAV_Value(NULL, $attribute);

				$table_name = $this->get_attribute_value_table_name($attribute);
				try
				{
					$result = DB::select()
						->from($table_name)
						->where(Inflector::singular($this->_table_name) . '_id', '=', $this->pk())
						->and_where('eav_attribute_id', '=', $attribute->pk())
						->as_assoc()->execute($this->_db);
				} 
				catch (Database_Exception $ex) 
				{
					if ($ex->getCode() == 1146)
					{
						$this->create_value_table($attribute);
						return $this->load_attributes();
					}
					throw $ex;
				}
				foreach ($result as $value)
				{
					$this->attributes[$attribute->name] = new EAV_Value($value['value'], $attribute);
				}
			}

		$this->attributes_loaded = TRUE;
	}
	
	/**
	 * Saves or updates (if the values are Valid) the EAV object and his attributes
	 * @param Validation $validation
	 * @throws ORM_Validation_Exception
	 */
	public function save(Validation $validation = NULL)
	{
		if ($this->loaded())
		{
			if ( ! $this->attributes_loaded)
				$this->load_attributes();
			$set_attributes = $this->get_set_attributes();
			$merged_values = array();
			foreach ($this->attributes as $key => $eav_value)
			{
				$merged_values[$key] = isset($this->attributes_changed[$key]) ? $this->attributes_changed[$key] : $eav_value->raw_value();
			}
		}
		else
		{
			$set_attributes = $this->get_set()->attributes->find_all();
			$merged_values = $this->attributes_changed;
		}
		$attributes_validation = Validation::factory($merged_values);
		foreach ($set_attributes as $attr)
		{
			if ($attr->obligatory == 1)
			{
				$attributes_validation->rule($attr->name, 'not_empty');
			}
			//to be continued AC-11
			if ($attr->unique == 1)
			{
				$attributes_validation->rule($attr->name, array($this, 'unique_set_value'), array(':value', ':value'));
			}
/*			if ($attr->default !== NULL AND !isset($this->attributes_changed[$attr->name]))
			{
				$this->attr($attr->name, $attr->default);
				$this->attributes_changed[$attr->name] = $attr->default;
			}*/
			if ( ! empty($attr->type->validation))
			{
				$attributes_validation->rule($attr->name, $attr->type->validation);
			}
		}	
		$this->_db->begin();
		try
		{
			parent::save($attributes_validation);
			$this->save_attributes();
			$this->attributes_loaded = FALSE;
			$this->_db->commit();
		}
		catch(Kohana_Database_Exception $ex)
		{
			$this->_db->rollback();
			throw $ex;
		}
	}
	
	public function attr_loaded()
	{
		return $this->attributes_loaded;
	}

	/**
	 * Gets attribute value from $this->attributes if they are loaded,
	 * if not loads all attributes.
	 * @param string $name
	 * @return EAV_Value
	 * @throws EAV_Exception
	 */
	protected function get_attribute($name)
	{
		if ( ! $this->has_attr($name))
			throw new EAV_Exception;

		if ( ! $this->attributes_loaded)
			$this->load_attributes();

		return $this->attributes[$name];
	}

	/**
	 * updates or saves the changed attributes
	 */
	protected function save_attributes()
	{
		$eav_values = $this->attr();
		foreach ($this->attributes_changed as $attribute_name => $attribute_value) // each changed attribute must be checked if the attribute already exists or not
		{
			$eav_value = $eav_values[$attribute_name];
			$table_name = $this->get_attribute_value_table_name($eav_value->attribute);
			if ( ( ! is_object($eav_value->value) && ! empty($eav_value->value)) || (is_object($eav_value->value) && $eav_value->value->loaded())) //update
			{
				try
				{
					$result = DB::update($table_name)
						->set(array('value' => $attribute_value))
						->where(Inflector::singular($this->_table_name) . '_id', '=', $this->pk()) // where eav_object_id = $this->id
						->and_where(Inflector::singular($eav_value->attribute->_table_name) . '_id', '=', $eav_value->attribute->pk()); // where eav_attribute_id = $eav_value->attribute->id
					$result->execute($this->_db);
				}
				catch(Database_Exception $ex)
				{
					if ($ex->getCode() == 1146)
					{
						$this->create_value_table($eav_value->attribute);
						$result->execute($this->_db);
					}
					else
						throw $ex;
				}
			}
			else
			{ // instert
				$affected_cols = array(
					Inflector::singular($this->_table_name) . '_id', // eav object foreign key
					Inflector::singular($eav_value->attribute->_table_name) . '_id', //eav attribute foreign key
					'value', // value
				);
				
				$col_values = array(
					$this->pk(),
					$eav_value->attribute->pk(),
					$attribute_value,
				);
				
				try
				{
					$result = DB::insert($table_name, $affected_cols)
						->values($col_values);
					$result->execute($this->_db);
				}
				catch(Database_Exception $ex)
				{
					if ($ex->getCode() == 1146)
					{
						$this->create_value_table($eav_value->attribute);
						$result->execute($this->_db);
					}
					else
						throw $ex;
				}
			}
		}
	}
	
	/**
	 * Checks if EAV Model has attribute with that name
	 * @param string $name
	 * @return boolean
	 */
	public function has_attr($name)
	{
		$set = $this->get_set();
		$attribute = ORM::factory('EAV_Attribute', array('name' => $name));
		if ( ! $attribute->loaded())
			return FALSE;
		
		return $this->set_family_has_attr($set, $attribute);
	}

	protected function set_family_has_attr($set, $attribute)
	{
		if($set->has('attributes', $attribute))
			return true;
		
		if($set->parent->loaded())
			return $this->set_family_has_attr($set->parent, $attribute);
		
		return false;
	}
	
	/**
	 * Loads all attributes of the set and his parents tree
	 * @return Array of EAV_Attributes
	 */
	protected function get_set_attributes()
	{
		$attributes = $this->get_set()->attributes->find_all();
		return $attributes;
	}
	
	/**
	 * Getting the set of that EAV object
	 * This function is created because the foreign keys and name of ORM classes may vary
	 * @return ORM 
	 */
	public function get_set()
	{
		if ($this->loaded())
			return $this->set;

		$set_foreign_key = $this->_belongs_to['set']['foreign_key'];
		return ORM::factory($this->_belongs_to['set']['model'], $this->$set_foreign_key);
	}

	/**
	 * Assigning a set of the EAV object
	 * This function is created because the foreign keys and name of ORM classes can vary
	 * @param type $set_id
	 */
	public function assign_set($set_id)
	{
		$set_foreign_key = $this->_belongs_to['set']['foreign_key'];
		$this->$set_foreign_key = $set_id;
	}

	/**
	 * Get eav entity name 
	 * @return string eav_entity name
	 */
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

	/**
	 * Search for eav objects in following relation operator($attr, $value)
	 * @param string $attr
	 * @param string $operator
	 * @param mixed $value
	 * @return Ambiguous <EAV, array>
	 */
	/*public function eav_where($attr, $operator, $value)
	{
		$result = array();
		$all_objects = $this->find_all()->as_array();
		switch ($operator) 
		{
			case '=':
				foreach ($all_objects as $eav_object)
				{	
					if ($eav_object->has_attr($attr) AND $eav_object->attr($attr)->value == $value)
						array_push($result, $eav_object);
				}
				break;
			case '>':
				foreach ($all_objects as $eav_object)
				{
					if ($eav_object->has_attr($attr) AND $eav_object->attr($attr)->value > $value)
						array_push($result, $eav_object);
				}
				break;
			case '<':
				foreach ($all_objects as $eav_object)
				{
					if ($eav_object->has_attr($attr) AND $eav_object->attr($attr)->value < $value)
						array_push($result, $eav_object);
				}
				break;
			case '<>':
				foreach ($all_objects as $eav_object)
				{
					if ($eav_object->has_attr($attr) AND $eav_object->attr($attr)->value < $value AND $eav_object->attr($attr)->value > $value)
						array_push($result, $eav_object);
				}
				break;
			default:
				# code...
				break;
		}
		return $result;
	}*/

	/**
	 * Create and where grouping
	 * @return this
	 */
	public function eav_and_where_open()
	{
		$this->_pending_conditions[] = array(
			'name' => 'eav_and_where_open',
			'args' => array(),
			);

		return $this;
	}

	/**
	 * Close and where grouping
	 * @return this
	 */
	public function eav_and_where_close()
	{
		$this->_pending_conditions[] = array(
			'name' => 'eav_and_where_close',
			'args' => array(),
			);

		return $this;
	}

	/**
	 * Creates a new "AND WHERE" condition for eav search.
	 * @param string $attr
	 * @param string $operator
	 * @param mixed $value
	 * @return Ambiguous <EAV, array>
	 */
	public function eav_and_where($attr, $operator, $value)
	{
		$this->_pending_conditions[] = array(
			'name' => 'eav_and_where',
			'args' => array($attr, $operator, $value),
			);

		return $this;	
	}

	/**
	 * Creates a new "AND WHERE" condition for eav search.
	 * @param string $attr
	 * @param string $operator
	 * @param mixed $value
	 * @return Ambiguous <EAV, array>
	 */
	public function eav_where($attr, $operator, $value)
	{
		$this->_pending_conditions[] = array(
			'name' => 'eav_where',
			'args' => array($attr, $operator, $value),
			);

		return $this;
	}

	/**
	 * Create or_where grouping
	 * @return this
	 */
	public function eav_or_where_open()
	{
		$this->_pending_conditions[] = array(
			'name' => 'eav_or_where_open',
			'args' => array(),
			);

		return $this;
	}

	/**
	 * Close or_where grouping
	 * @return this
	 */
	public function eav_or_where_close()
	{
		$this->_pending_conditions[] = array(
			'name' => 'eav_or_where_close',
			'args' => array(),
			);

		return $this;
	}

	/**
	 * Creates a new "OR WHERE" condition for eav search.
	 * @param string $attr
	 * @param string $operator
	 * @param mixed $value
	 * @return Ambiguous <EAV, array>
	 */
	public function eav_or_where($attr, $operator, $value)
	{
		$this->_pending_conditions[] = array(
			'name' => 'eav_or_where',
			'args' => array($attr, $operator, $value),
			);

		return $this;	
	}

	/**
	 * Finds and loads a single eav object.
	 * @return EAV 
	 */
	public function eav_find()
	{

	}

	/**
	 * Finds and loads into array all eav objects .
	 * @return array With EAV objects
	 */
	public function eav_find_all()
	{
		Debug::output($this->_pending_conditions,1);
	}

	/**
	 * Generate summary of attributes values for that eav
	 * @return string values of obligatory attributes separeted by |
	 */
	public function summary()
	{	
		$summary = '';
		$set = $this->get_set();
		$attributes = $set->attributes->where('obligatory', '=', 1)->and_where('type_id', '=', 12)->find_all()->as_array();

		foreach ($attributes as $attribute)
		{
			$summary .= $this->attr($attribute->name)->value . ' | ';
		}

		return $summary;

	}

	/**
	 * Check if the attribute is unique in scope of the set
	 * @param  [type] $set   [description]
	 * @param  [type] $value [description]
	 * @return bool        	TRUE - unique, FALSE - not_unique
	 */
	public function unique_set_value($attribute, $value)
	{
		/*die($attribute.'-'.$value);
		$result = ORM::factory('EAV_Attribute')->where('name', '=', $name);
		if($this->loaded())
			$result = $result->and_where('id', '<>', $this->pk());
		
		return ! $result->find()->loaded();*/
		return TRUE;
	}
}

?>
