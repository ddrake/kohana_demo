<?php defined('SYSPATH') OR die('No Direct Script Access');

class Controller_Error extends Controller
{
	public $view_data;

	public function before()
	{
		parent::before();

		$this->view_data['page'] = URL::site(rawurldecode(Request::$initial->uri()));

		// Internal request only!
		if (Request::$initial !== $this->request)
		{
			if ($message = rawurldecode($this->request->param('message')))
			{
				$this->view_data['message'] = $message;
			}
		}
		else
		{
			$this->request->action(404);
		}
		$action = $this->request->action();
		$this->response->status((int) $action);
		$this->view_data['code'] = $action;
	}

	public function action_404()
	{

		$this->view_data['title'] = 'Page Not Found';

		// Here we check to see if a 404 came from our website. This allows the
		// webmaster to find broken links and update them in a shorter amount of time.
		if (isset ($_SERVER['HTTP_REFERER']) AND strstr($_SERVER['HTTP_REFERER'], $_SERVER['SERVER_NAME']) !== FALSE)
		{
			// Set a local flag so we can display different messages in our template.
			$this->view_data['local'] = TRUE;
		}

		// HTTP Status code.
		//$this->response->status(404);
		$this->show_page();
	}

	public function action_503()
	{
		$this->view_data['title'] = 'Maintenance Mode';
		$this->show_page();
	}

	public function action_500()
	{
		$this->view_data['title'] = 'Internal Server Error';
		$this->show_page();
	}
	private function show_page()
	{
		$view = new View_Pages_ErrorHandler;
		$view->data = $this->view_data;
		echo $view;
	}
}
