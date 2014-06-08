<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Eav_Entities extends Controller_Eav {

	protected $main_model = 'EAV_Money_Set';
	protected $filter_fields = array('name');

	public function action_index()
	{
		$entity = $this->request->param('id');
		$sets = ORM::factory($entity.'_Set')->find_all();

		$this->template->content = View::factory('UI/entities/index', array(
					'sets' => $sets
		));
	}

	public function action_new()
	{
		$set = ORM::factory('EAV_Money_Set');
		$sets = $set->find_all();
		$errors = array();
		$all_attributes = ORM::factory('EAV_Attribute')->find_all()->as_array('id', 'label');
		$countries = EAV::as_array_attr(EAV::factory('EAV_Country')->find_all(), 'id', 'country_name');

		if ($this->valid_post)
		{
			$data = $this->request->post();
			$db = Database::instance();
			try
			{
				$db->begin();
				$set->create_set($data);
				$db->commit();
				Message::push(__("You have successfuly added: {$set->name}"), Message::SUCCESS);
				$this->redirect(URL::base(true, true) . 'admin/entities/' . $this->controller . '/edit/' . $set->id);
			} catch (ORM_Validation_Exception $ex)
			{
				$db->rollback();
				$errors = $ex->errors('models');
			} catch (Database_Exception $ex)
			{
				$db->rollback();
				throw $ex;
			}
		}

		$this->template->content = View::factory('admin/entities/form', array(
					'set' => $set,
					'sets' => array(NULL => 'Няма') + $sets->as_array('id', 'name'),
					'errors' => $errors,
					'all_attributes' => $all_attributes,
					'set_attributes' => array(),
					'countries' => $countries,
		));
	}

	public function action_edit()
	{
		$set = ORM::factory('EAV_Money_Set', $this->request->param('id'));
		$sets = ORM::factory('EAV_Money_Set')->find_all();
		$errors = array();
		$all_attributes = ORM::factory('EAV_Attribute')->find_all()->as_array('id', 'label');
		$set_attributes = $set->attributes->find_all()->as_array('id', 'label');
		$countries = EAV::as_array_attr(EAV::factory('EAV_Country')->find_all(), 'id', 'country_name');

		if ($this->valid_post)
		{
			$data = $this->request->post();
			$db = Database::instance();
			try
			{
				$db->begin();
				$set->update_set($data);
				$db->commit();
				Message::push(__("You have Successfuly edited: {$set->name}"), Message::SUCCESS);
				$set_attributes = $set->attributes->find_all()->as_array('id', 'label');
			} catch (ORM_Validation_Exception $ex)
			{
				$db->rollback();
				$errors = $ex->errors('models');
				Debug::output($errors, 1);
			} catch (Database_Exception $ex)
			{
				$db->rollback();
				throw $ex;
			}
		}

		$this->template->content = View::factory('admin/entities/form', array(
					'set' => $set,
					'sets' => array(NULL => 'Няма') + $sets->as_array('id', 'name'),
					'errors' => $errors,
					'all_attributes' => $all_attributes,
					'set_attributes' => $set_attributes,
					'countries' => $countries,
		));
	}

	public function action_delete()
	{
		$set = EAV::factory('EAV_Money_Set', $this->request->param('id'));
		$set_name = $set->name;
		if ($set->delete_set())
			Message::push(__('You have successfuly delete ":set_name" set', array(':set_name' => $set_name)), Message::SUCCESS);
		else
			Message::push(__('":set_name" set cannot be deleted!', array(':set_name' => $set_name)), Message::ERROR);
		$this->redirect(URL::site('admin/entities/money/index'));
	}

	public function action_get_attributes()
	{
		$this->auto_render = FALSE;
		$set = ORM::factory('EAV_Money_Set', $this->request->param('id'));
		if ( ! $set->loaded())
			throw new HTTP_Exception_404;

		$this->response->body(json_encode($set->attributes->find_all()->as_array('id', 'label')));
	}

}
