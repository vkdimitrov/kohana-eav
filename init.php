<?php defined('SYSPATH') or die('No direct script access.');

Route::set('eav','(/<controller>(/<action>(/<id>)))')
	->defaults(array(
		'controller' => 'eav',
		'action'     => 'index',
	));

Route::set('eav_entities','(/<controller>(/<action>(/<id>)(/<argv>)))')
	->defaults(array(
		'controller' => 'eav_entities',
		'action'     => 'index',
	));

Route::set('eav_attributes','(/<controller>(/<action>(/<id>)))')
	->defaults(array(
		'controller' => 'eav_attributes',
		'action'     => 'index',
	));