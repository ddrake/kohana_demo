<?php defined('SYSPATH') or die('No direct script access.');

class View_Pages_Admin_Form extends Kostache_Layout {

	public $meta_keywords = 'cool, musick';
	public $meta_copyright = 'A list of awesome albums';

	public $title = '';
	public $meta_description = '';
	public $editor_title = '';


	public function __construct($template = NULL, array $partials = NULL)
	{
		parent::__construct('pages/admin/form', $partials);
	}

	public function base()
	{
		return URL::base(Request::$initial, TRUE);
	}

	public function links()
	{
		$route = Route::get('normal');
		return array(
			'save' => $route->uri(array('controller'=>'admin', 'action'=>'save')),
		);
	}
	public function user()
	{
		$user_arr = $this->user->as_array();
		$admin_role = ORM::factory('role',array('name' => 'admin'));
		$user_arr['is_admin'] = $this->user->has('roles',$admin_role);
		return $user_arr;
	}
	public function styles()
	{
		return array(
			'assets/css/form.css',
		);
	}

	public function scripts()
	{
		return array(
			'assets/js/jquery-1_5_2_min.js',
			'assets/js/jqtest.js',
		);
	}

}
