<?php defined('SYSPATH') or die('No direct script access.');

class View_Pages_Admin_List extends Kostache_Layout {

	public $title = 'User List';
	public $meta_keywords = 'cool, users';
	public $meta_description = 'A list of awesome users';
	public $meta_copyright = 'A list of awesome users';

	public $list_title = 'Current Users';

	public function base()
	{
		return URL::base(Request::$initial, TRUE);
	}

	public function links()
	{
		return array(
			'user_add' => Route::get('normal')->uri(array('controller'=>'admin', 'action'=>'add')),
			'user_logout' => Route::get('normal')->uri(array('controller'=>'user', 'action'=>'logout')),
			'user_login' => Route::get('normal')->uri(array('controller'=>'user', 'action'=>'login')),
			'user_profile' => Route::get('normal')->uri(array('controller'=>'user')),
			'album_list' => Route::get('normal')->uri(array('controller'=>'album')),
		);
	}

	public function users()
	{
		$cur_admin = Auth::instance()->get_user();
		$users = array();
		$admin_role = ORM::factory('role',array('name' => 'admin'));
		$route = Route::get('normal');
		foreach(ORM::factory('user')->order_by('username','asc')->find_all() as $user)
		{
			if ($user->id === $cur_admin->id) continue; // don't show the current user -- they shouldn't update this way (or delete self).
			$arr_user = $user->as_array();
			$arr_user['is_admin'] = $user->has('roles',$admin_role) ? 'Yes' : 'No';
			$arr_user['edit_link'] = $route->uri(array('controller'=>'admin', 'action'=>'edit', 'id'=>$user->id));
			$arr_user['delete_link'] = $route->uri(array('controller'=>'admin', 'action'=>'delete', 'id'=>$user->id));
			$users[] = $arr_user;
			//todo: find all roles for user and concatenate.
		}
		return $users;
	}
	public function logged_in_user()
	{
		return Auth::instance()->get_user()->username;
	}
}
