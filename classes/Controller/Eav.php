<?php defined('SYSPATH') OR die('No direct access allowed.');

class Controller_Eav extends Kohana_Controller_Template {

	public $template = 'layouteav';

	public $valid_post = FALSE;

	protected $view;

	public function before() 
	{
		if ($this->request->method() == HTTP_Request::POST && Valid::not_empty($this->request->post()))
			$this->valid_post = TRUE;
		parent::before();
	}

	public function action_index() 
	{
		$entities = ORM::factory('EAV_Attribute_Type')->where('class', '<>', 'NULL')->find_all()->as_array();
		/*
		$this->view = new View('flexiblemigrations/index');
		$this->view->set_global('migrations', $migrations);
		$this->view->set_global('migrations_runned', $migrations_runned);
		*/
		$this->template->content = View::factory('UI/index', array('entities' => $entities));
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
