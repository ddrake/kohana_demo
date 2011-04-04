<?php defined('SYSPATH') OR die('No direct access allowed.');
class Controller_Auth extends Controller {

	public function before() {
		// This codeblock is very useful in development sites:
		// What it does is get rid of invalid sessions which cause exceptions, which may happen
		// 1) when you make errors in your code.
		// 2) when the session expires!
		try {
			$this->session = Session::instance();
		} catch(ErrorException $e) {
			session_destroy();
		}
		// Execute parent::before first
		parent::before();
		// Open session
		$this->session = Session::instance();

		// First make sure we have at least one admin user
		// This code can safely be deleted after the first login
		// DELETE AFTER FIRST LOGIN -- BLOCK START
		$user = Orm::Factory('user');
		if ($user->count_all() == 0)
		{
			$login_role = ORM::factory('role',array('name' => 'login'));
			$admin_role = ORM::factory('role', array('name' => 'admin'));
			$data = array('username' => 'administrator',
						  'email' => 'admin@tempuri.com',
						  'password' => 'admin12345',
						  'password_confirm' => 'admin12345');

			$user->create_user($data, array('username','password','email'));
			$user->add('roles',$login_role)->add('roles',$admin_role);
			$user->save();
		}
		$user = NULL;
		// DELETE AFTER FIRST LOGIN -- BLOCK END

		// Handle the unlikely situation where a logged-in user was recently deleted
		// by an admin, but is now making a request.  In this case, we want to log the user out
		// before trying to process the request.
		$auth = Auth::instance();
		$user = $auth->get_user();
		// In this situation, we will still have a user in the session,
		// and the auth instance will still see its original role(s),
		// but the ORM will fetch NULL for the user ID
		if (!empty($user) and $user->id === NULL)
		{
			$auth->logout(TRUE, TRUE);
		}

		// Check user auth and role
		$action_name = Request::current()->action();

		if (($this->auth_required !== FALSE &&
			 $auth->logged_in($this->auth_required) === FALSE)
		// auth is required AND user role given in auth_required is NOT logged in
		|| (is_array($this->secure_actions)
			&& array_key_exists($action_name, $this->secure_actions)
			&& $auth->logged_in($this->secure_actions[$action_name]) === FALSE)
		// OR secure_actions is set AND the user role given in secure_actions is NOT logged in
		) {
			if ($auth->logged_in()){
			// user is logged in but not on the secure_actions list
				$this->access_required();
			} else {
				$this->login_required();
			}
		}
	}
}
