<?php defined('SYSPATH') or die('No direct script access.');

Route::set('eav_entities','eav_entities/edit(/<id>(/<argv>))')
	->defaults(array(
		'controller' => 'eav_entities',
		'action' => 'edit'
	));

Route::set('eav','(/<controller>(/<action>(/<id>)))')
	->defaults(array(
		'controller' => 'eav',
		'action'     => 'index',
	));

Route::set('eav_attributes','(/<controller>(/<action>(/<id>)))')
	->defaults(array(
		'controller' => 'eav_attributes',
		'action'     => 'index',
	));