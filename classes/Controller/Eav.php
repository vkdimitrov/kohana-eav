<?php defined('SYSPATH') OR die('No direct access allowed.');

class Controller_Eav extends Kohana_Controller_Template {

	public $template = 'layouteav';

	public $valid_post = FALSE;

	public function before() 
	{
		if ($this->request->method() == HTTP_Request::POST && Valid::not_empty($this->request->post()))
			$this->valid_post = TRUE;
		parent::before();
	}

	public function action_index() 
	{
		//load entities, every entity can be a type of attribute
		$entities_names = ORM::factory('EAV_Attribute_Type')->where('class', '<>', 'NULL')->find_all()->as_array();
		$entities = array();
		$attributes = ORM::factory('EAV_Attribute')->find_all()->as_array();
		foreach ($entities_names as $entity_name)
		{
			array_push($entities, ORM::factory($entity_name->class));
		}
		$this->template->content = View::factory('UI/index', array('entities' => $entities, 'attributes' => $attributes));
	}

	public function action_new() 
	{
		die("new");
		/*
		$this->view = new View('flexiblemigrations/index');
		$this->view->set_global('migrations', $migrations);
		$this->view->set_global('migrations_runned', $migrations_runned);
		*/
		$this->template->content = View::factory('UI/index', array());
	}
}
