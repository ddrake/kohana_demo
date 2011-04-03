<?php defined('SYSPATH') or die('No direct script access.');

class View_Pages_Admin_Noaccess extends Kostache_Layout {

	public $meta_keywords = 'cool, musick';
	public $meta_copyright = 'A list of awesome albums';

	public $title = 'Unauthorized Access';
	public $meta_description = '';
	public $body_title = 'Administrative Access Only';
	public $message = 'Only admininistrators may access the requested page.  Please contact a site administrator to request access if needed.';

	public function base()
	{
		return URL::base(Request::$initial, TRUE);
	}

	public function links()
	{
		$route = Route::get('normal');
		return array(
			'album_list' => $route->uri(array('controller'=>'album', 'action'=>'list')),
		);
	}

}
