<?php defined('SYSPATH') OR die('No direct access allowed.');
class Controller_User extends Controller_Auth {

	//Controls access for the whole controller, if not set to FALSE we will only allow user roles specified.
	// Can be set to a string or an array, for example array('login', 'admin') or 'login'
   public $auth_required = FALSE;  // this is TRUE only for the admin controller

    // Controls access for separate actions
	public $secure_actions = array(
		// user actions
		'index' => 'login', // this is edit profile
		'save' => 'login', // this is save profile
		// the others are public (login, logout)
	);

	protected function login_required() {
		$this->action_login();
	}

	public function action_login() {
		// If user already signed-in
		$message = NULL;
		$auth = Auth::instance();
		if($auth->logged_in() != 0){
			$this->redirect_to_albums();
		}
		if ($_POST)
		{
			$auth->login(Arr::get($_POST,'username'),Arr::get($_POST,'password'));
			if ($auth->logged_in())
			{
				$this->redirect_to_albums();
			}
			$message = 'Login failed.  Password is case-sensitive.  Is Caps Lock on?';
		}
		$this->show_login_form($message);
	}

	// This method can be called by action_edit
	// or by the save action if validation fails.
	private function show_login_form($message)
	{
		$view = new View_Pages_User_Login;
		$view->set('message', $message);
		$this->response->body($view);
	}

	public function action_logout() {
		// Sign out the user
		Auth::instance()->logout();
		$this->redirect_to_albums();
	}

	public function action_index()
	{
		// show the edit profile form
		if ( Auth::instance()->logged_in() == false ){
			// No user is currently logged in
			$this->request->redirect('user/login');
		}
		$this->show_profile_form();
	}

	public function action_noaccess()
 	{
		$view = new View_Pages_User_Noaccess;
		$this->response->body($view);
 	}

	public function action_save()
	{
		if ($_POST)
		{
			$errors = NULL;
			try
			{
				$user = Auth::instance()->get_user();
				$user = $user->update_user($_POST, array('password', 'email'));
				$user->save();
				$this->redirect_to_albums();
			}
			catch (ORM_Validation_Exception $e)
			{
				// todo: specify a real messages file here...
				// external errors are still in a sub-array, so we have to flatten
				// also the message is wrong  - bug #3896
				$errors = Arr::flatten($e->errors('hack'));
			}
			$this->show_profile_form($user, $errors);
		}
		else
		{
			$this->redirect_to_albums();
		}
	}

	// This method can be called by action_edit
	// or by the save action if validation fails.
	private function show_profile_form($user = NULL, $errors = NULL)
	{
		if ($user === NULL)
		{
			$user = Auth::instance()->get_user();
		}
		$view = new View_Pages_User_Profile;
		$view->set('user', $user->as_array())
			 ->set('errors',$errors);
		$this->response->body($view);
	}

	private function redirect_to_albums()
	{
		// Redirect the user to the list
		// specify the controller in case later we change the default controller for the route.
		$uri = Route::get('normal')->uri(array('controller'=>'album'));
		$this->request->redirect($uri);
	}

}
