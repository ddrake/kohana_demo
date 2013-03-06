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
		$uri = Route::get('default')->uri(array('controller'=>'user', 'action'=>'login'));
		$this->request->redirect($uri);
	}

	public function action_index()
 	{
		$view = new View_Pages_Album_List;
		$this->response->body($view); // . View::factory('profiler/stats');
 	}
	
 	public function action_add()
 	{
		$album = ORM::factory('album');
		$this->show_add_form($album);
 	}

 	public function action_edit()
 	{
		$id = $this->request->param('id');
		$album = ORM::factory('album', $id);
		if (! $album->loaded())
		{
			$this->redirect_to_list();
		}
		$this->show_edit_form($album);
 	}

	// This method can be called by action_add
	// or by the save action if validation fails.
	private function show_add_form($album, $errors = NULL)
	{
		$view = new View_Pages_Album_Add;
		$view->set('album',$album->as_array())->set('errors', $errors);
		$this->response->body($view);
	}
	// This method can be called by action_edit
	// or by the save action if validation fails.
	private function show_edit_form($album, $errors = NULL)
	{
		$view = new View_Pages_Album_Edit;
		$view->set('album',$album->as_array())->set('errors',$errors);
		$this->response->body($view);
	}

	public function action_save()
	{
		if (count($_POST) > 0)
		{
			$album = ORM::factory('album',$this->request->post('id'))->values($_POST);
			try
			{
				$album->save_album($_POST, array('name','artist'));
				$this->delete_album_fragment($album->id);
				$this->redirect_to_list();
			}
			catch (ORM_Validation_Exception $e)
			{
				$errors = $e->errors('models');
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

	// Note: this is a GET (not REST-ful, but only logged-in users can execute, so I don't have a problem)
	public function action_delete()
 	{
		$id = $this->request->param('id');
		$album = ORM::factory('album', $id);
		if (! $album->loaded())
		{
			$this->redirect_to_list();
		}
		$album->delete();
		$this->delete_album_fragment($album->id);
		$this->redirect_to_list();
	}

	public function action_details()
	{
		$id = $this->request->param('id');
		$album = ORM::factory('album', $id);
		if (! $album->loaded())
		{
			$this->redirect_to_list();
		}
		$default_msg = "<h3>click a row to view album details...</h3>";
		$error_msg = "<h3>couldn't find info for that album...</h3>";
		if ( ! Fragment::load("album_{$id}", Date::DAY * 7))
		{
			$album = ORM::factory('album',$id);
			try
			{
				$config = Kohana::$config->load('lastfm');
				$details = simplexml_load_file("http://ws.audioscrobbler.com/2.0/?method=album.getinfo&api_key={$config['api_key']}&artist={$album->artist}&album={$album->name}");
				$view = new View_Pages_Album_Details;
				$view->set('details',$details);
				$this->response->body($view);
				Fragment::save();
			}
			catch (Exception $e) {
				Log::instance()->add(Log::ERROR, $e);
				echo $error_msg;
			}
		}
		else {
			echo $default_msg;
		}
	}

	public function action_listxml()
	{
		$this->response->headers(array('Content-Type' => 'text/xml', 'Cache-Control' => 'no-cache'));
		$this->response->body(Model_Album::xml_list());
	}

	private function delete_album_fragment($id)
	{
		try
		{
			Fragment::delete("album_{$id}");
		}
		catch (Exception $e) {}
	}

	// Redirect the user to the list
	private function redirect_to_list()
	{
		$session = Session::instance();
		$query = $session->get('album_list');
		$uri = Route::get('default')->uri(array('controller'=>'album', 'action'=>'index')) . URL::query($query);
		$this->request->redirect($uri);
	}

	public function after()
	{
		// Add the profiler output to the controller
		//$this->response->body($this->response->body() . View::factory('profiler/stats'));
	}
}
