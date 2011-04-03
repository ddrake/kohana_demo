<?php defined('SYSPATH') OR die('No direct access allowed.');
class Controller_Admin extends Controller_Auth
{
    public $auth_required = 'admin';  // this is only TRUE for this controller
	public $secure_actions = array();

	protected function access_required() {
		$this->redirect_to_noaccess();
	}
	protected function login_required() {
		$this->redirect_to_noaccess();
	}
	private function redirect_to_noaccess()
	{
		$uri = Route::get('normal')->uri(array('controller'=>'user','action'=>'noaccess'));
		$this->request->redirect($uri);
	}

 	public function action_index()
 	{
		$view = new View_Pages_Admin_List;
		$this->response->body($view);  // . View::factory('profiler/stats');
 	}

 	public function action_add()
 	{
		$user = ORM::factory('user');
		$this->show_add_form($user);
 	}

 	public function action_edit($id)
 	{
		$user = ORM::factory('user', $id);
		$this->show_edit_form($user);
 	}

	// This method can be called by action_add
	// or by the save action if validation fails.
	private function show_add_form($user, $errors = NULL)
	{
		$view = new View_Pages_Admin_Add;
		$view->set('user',$user)->set('errors', $errors);
		$this->response->body($view);
	}
	// This method can be called by action_edit
	// or by the save action if validation fails.
	private function show_edit_form($user, $errors = NULL)
	{
		$view = new View_Pages_Admin_Edit;
		$view->set('user',$user)->set('errors', $errors);
		$this->response->body($view);
	}

	public function action_save()
	{
		if ($_POST)
		{
			try
			{
				$login_role = ORM::factory('role',array('name' => 'login'));
				$admin_role = ORM::factory('role', array('name' => 'admin'));
				if (empty ($_POST['id']))
				{
					$user = ORM::factory('user');
					$user->create_user($_POST, array('username','password','email'));
					$user->add('roles',$login_role);
				}
				else
				{
					$user = ORM::factory('user',$_POST['id']);
					$user->update_user($_POST, array('username','password','email'));
				}
				if (!empty($_POST['is_admin']) && !$user->has('roles',$admin_role))
				{
					$user->add('roles',$admin_role);
				}
				elseif (empty($_POST['is_admin']) && $user->has('roles',$admin_role))
				{
					$user->remove('roles', $admin_role);
				}

				$user->save();
				$this->redirect_to_list();
			}
			catch (ORM_Validation_Exception $e)
			{
				// todo: specify a real messages file here...
				// external errors are still in a sub-array, so we have to flatten
				// also the message is wrong  - bug #3896
				$errors = Arr::flatten($e->errors('hack'));
			}
			if ($user->id == null)
			{
				$this->show_add_form($user, $errors);
			}
			else
			{
				$this->show_edit_form($user, $errors);
			}
		}
		else
		{
			$this->redirect_to_list();
		}
	}

	// todo: this should be a POST not a GET -- possible to do this from the list?
	public function action_delete($id)
 	{
		$user = ORM::factory('user',$id);
		$user->delete();
		$this->redirect_to_list();
 	}

	private function redirect_to_list()
	{
		// Redirect the user to the list
		$uri = Route::get('normal')->uri(array('controller'=>'admin'));
		$this->request->redirect($uri);
	}
}
