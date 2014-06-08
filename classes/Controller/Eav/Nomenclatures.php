<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_Nomenclatures_Main extends Controller_Admin_Main {


	public function action_index()
	{
		$nomenclature = 'EAV_' . Text::ucfirst(Inflector::singular($this->request->param('id'))) . '_Set';
		$sets = ORM::factory($nomenclature)->find_all();

		$this->template->content = View::factory('admin/nomenclatures/index', array(
					'sets' => $sets,
					'nomenclature' => $this->request->param('id')
		));
	}

	public function action_new()
	{
		$nomenclature_name = $this->request->param('id');
		$nomenclature = 'EAV_' . Text::ucfirst(Inflector::singular($nomenclature_name));
		$set = ORM::factory($nomenclature.'_Set', $this->request->param('arg'));
		$attributes = $set->attributes->find_all()->as_array();
		$entity = ORM::factory($nomenclature);
		$entity->assign_set($this->request->param('arg'));
		$errors = array();

		if ($this->valid_post)
		{
			try
			{
				foreach ($attributes as $attribute)
				{
					$entity->attr($attribute->name, $this->request->post($attribute->name));
				}
				$entity->save();

				if ($this->request->post('save_and_add') == 1)
					$this->redirect('admin/nomenclatures/'.$this->controller.'/new/' . $this->request->param('id')."/". $set->id);
				$this->redirect(URL::base(true, true).'admin/nomenclatures/'.$this->controller.'/edit/'.$nomenclature_name.'/'.$entity->id);
			}
			catch (ORM_Validation_Exception $ex)
			{
				Debug::output($ex, 1);
			}

		}

		$this->template->content = View::factory('admin/nomenclatures/form', array(
					'set'               => $set,
					'entity'            => $entity,
					'attributes'        => $attributes,
					'errors'            => $errors,
					'nomenclature_name' => $nomenclature_name
		));
	}

	public function action_edit()
	{
		$nomenclature_name = $this->request->param('id');
		$model = 'EAV_' . Text::ucfirst(Inflector::singular($nomenclature_name));
		$entity = ORM::factory($model, $this->request->param('arg'));
		$attributes = $entity->set->attributes->find_all();
		$errors = array();

		if ($this->valid_post)
		{
			$data = $this->request->post();
			//var_dump($this->request->post());die;

			foreach ($attributes as $attribute)
			{
				$entity->attr($attribute->name, $data[$attribute->name]);
			}
			$entity->save();
		}

		$this->template->content = View::factory('admin/nomenclatures/form', array(
					'set' => $entity->set,
					'entity' => $entity,
					'attributes' => $attributes,
					'errors' => $errors,
					'nomenclature_name' => $nomenclature_name
		));
	}

	public function action_delete()
	{
		$nomenclature = Inflector::singular($this->request->param('id'));
		$model = 'EAV_' . Text::ucfirst($nomenclature);
		$entity = ORM::factory($model, $this->request->param('arg'));

		if ( ! $entity->loaded())
			throw new HTTP_Exception_404;

		$attributes = $entity->set->attributes->find_all();
		try
		{
			$db = Database::instance();
			$db->begin();

			foreach ($attributes as $attribute)
			{	
				$table = $entity->get_attribute_value_table_name($attribute);
				$query = DB::query(Database::DELETE, "DELETE FROM {$table} WHERE eav_attribute_id ={$attribute->id} AND eav_{$nomenclature}_id={$entity->id}");
				$query->execute();
			}
			$entity->delete();
			$db->commit();
			$this->redirect('admin/nomenclatures/' . $this->controller . '/index/' . $this->request->param('id') );
		}
		catch (Database_Exception $e)
		{
			$db->rollback();
			Debug::output($e);
		}
	}
}
