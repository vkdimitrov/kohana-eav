<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Eav_Attributes extends Controller_Eav {

	public function action_index()
	{
		$attributes = ORM::factory('EAV_Attribute')->find_all();
		$this->template->content = View::factory('UI/entities/attributes/index', array(
					'attributes' => $attributes,
		));
	}

	public function action_new()
	{
		$attribute = ORM::factory('EAV_Attribute');
		
		$data = array();
		$errors = array();

		if ($this->valid_post)
		{
			$data = $this->request->post();
			$data['label'] = $data['name'];
			try
			{
				$attribute->values($data, $attribute->expected());
				$attribute->save();
				$this->redirect('/eav_attributes/edit/' . $attribute->id);
			} catch (ORM_Validation_Exception $ex)
			{
				$errors = $ex->errors('models');
			}
		}
		$this->template->content = View::factory('UI/entities/attributes/form', array(
					'attribute' => $attribute,
					'data' => $data,
					'errors' => $errors,
		));
	}
}
