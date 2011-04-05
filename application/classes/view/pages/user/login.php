<?php defined('SYSPATH') or die('No direct script access.');

class View_Pages_User_Login extends Kostache_Layout {

	public $meta_keywords = 'cool, musick';
	public $meta_copyright = 'dowdrake.com';

	public $title = 'Log In';
	public $meta_description = 'A user login form';
	public $editor_title = 'Log In';

	public function base()
	{
		return URL::base(Request::$initial, TRUE);
	}

	public function links()
	{
		$route = Route::get('normal');
		return array(
			'save' => $route->uri(array('controller'=>'user', 'action'=>'login')),
		);
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
			'assets/js/jquery-1.5.2.min.js',
			'assets/js/jqtest.js'
		);
	}
}
