<?php defined('SYSPATH') or die('No direct script access.');

class View_Pages_Album_List extends Kostache_Layout {

	public $title = 'My Albums';
	public $meta_keywords = 'cool, musick';
	public $meta_description = 'A list of awesome albums';
	public $meta_copyright = 'dowdrake.com';
	public $list_title = 'Current Album List';

	public function base()
	{
		return URL::base(Request::$initial, TRUE);
	}

	public function albums()
	{
		// using with('genre') improves efficiency by pre-loading the genre info (avoids lazy loading)
		$albums = array();
		$route = Route::get('normal');
		foreach(ORM::factory('Album')->with('genre')->find_all()->as_array() as $album)
		{
			$album = $album->as_array();
			$album['genre_name'] = $album['genre']['name'];
			$album['edit_link'] = $route->uri(array('controller'=>'album', 'action'=>'edit', 'id'=>$album['id']));
			$album['delete_link'] = $route->uri(array('controller'=>'album', 'action'=>'delete', 'id'=>$album['id']));
			$albums[] = $album;
		}
		return $albums;
	}

	public function links()
	{
		$route = Route::get('normal');
		return array(
			'album_add' => $route->uri(array('controller'=>'album', 'action'=>'add')),
			'user_logout' => $route->uri(array('controller'=>'user', 'action'=>'logout')),
			'user_login' => $route->uri(array('controller'=>'user', 'action'=>'login')),
			'user_profile' => $route->uri(array('controller'=>'user')),
			'admin_list' => $route->uri(array('controller'=>'admin')),
		);
	}
	public function logged_in_user()
	{
		$auth = Auth::instance();
		return $auth->logged_in() ? $auth->get_user()->username : null;
	}
	public function user_is_admin()
	{
		$admin_role = ORM::factory('role',array('name' => 'admin'));
		$auth = Auth::instance();
		return $auth->logged_in() ? $auth->get_user()->has('roles',$admin_role) : FALSE;
	}
}
