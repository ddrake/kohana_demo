<?php defined('SYSPATH') OR die('No direct access allowed.');
class Controller_Album extends Controller_Auth
{
   public $auth_required = FALSE;  // this should only be TRUE for the admin controller

	public $secure_actions = array(
		// user actions
		'add' => 'login',
		'edit' => 'login',
		'save' => 'login',
		'delete' => 'login',
		// the others are public (index)
	);

	protected function login_required() {
		$uri = Route::get('normal')->uri(array('controller'=>'user', 'action'=>'login'));
		$this->request->redirect($uri);
	}

	public function action_index()
 	{
		$view = new View_Pages_Album_List;
		echo $view;  // . View::factory('profiler/stats');
 	}

 	public function action_add()
 	{
		$album = ORM::factory('album');
		$this->show_add_form($album);
 	}

 	public function action_edit($id)
 	{
		$album = ORM::factory('album', $id);
		$this->show_edit_form($album);
 	}

	// This method can be called by action_add
	// or by the save action if validation fails.
	private function show_add_form($album, $errors = NULL)
	{
		$view = new View_Pages_Album_Add;
		$view->set('album',$album->as_array())->set('errors', $errors);
		echo $view;
	}
	// This method can be called by action_edit
	// or by the save action if validation fails.
	private function show_edit_form($album, $errors = NULL)
	{
		$view = new View_Pages_Album_Edit;
		$view->set('album',$album->as_array())->set('errors',$errors);
		echo $view;
	}

	public function action_save()
	{
		if ($_POST)
		{
			$album = ORM::factory('album',$this->request->post('id'))->values($_POST);
			try
			{
				$album->save();
				$this->redirect_to_list();
			}
			catch (ORM_Validation_Exception $e)
			{
				// todo: specify a real messages file here...
				$errors = $e->errors('dummy');
			}
			if ($album->id == null)
			{
				$this->show_add_form($album, $errors);
			}
			else
			{
				$this->show_edit_form($album, $errors);
			}
		}
		else
		{
			$this->redirect_to_list();
		}
	}

	// Note: this is a GET so not possible to do this from the list?
	public function action_delete($id)
 	{
		$album = ORM::factory('album',$id);
		$album->delete();
		$this->redirect_to_list();
 	}

	// Redirect the user to the list
	private function redirect_to_list()
	{
		$uri = Route::get('normal')->uri(array('controller'=>'album'));
		$this->request->redirect($uri);
	}
}
