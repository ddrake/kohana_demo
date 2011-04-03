<?php defined('SYSPATH') OR die('No Direct Script Access');

class Controller_Error extends Controller
{
	private $view;
	public function before()
	{
		parent::before();

		$this->view = new View_Pages_ErrorHandler;
		$this->view->set('page', URL::site(rawurldecode(Request::$initial->uri())));

		// Internal request only!
		if (Request::$initial !== $this->request)
		{
			if ($message = rawurldecode($this->request->param('message')))
			{
				$this->view->set('raw_message',$message);
			}
		}
		else
		{
			$this->request->action(404);
		}
		$action = $this->request->action();
		$this->response->status((int) $action);
		$this->view->set('code',$action);
	}

	public function action_404()
	{

		// Here we check to see if a 404 came from our website. This allows the
		// webmaster to find broken links and update them in a shorter amount of time.
		if (isset ($_SERVER['HTTP_REFERER']) AND strstr($_SERVER['HTTP_REFERER'], $_SERVER['SERVER_NAME']) !== FALSE)
		{
			// Set a local flag so we can display different messages in our template.
			$this->view->set('local',TRUE);
		}
		echo $this->view->set('title','Page Not Found');
	}

	public function action_503()
	{
		echo $this->view->set('title','Maintenance Mode');
	}

	public function action_500()
	{
		echo $this->view->set('title','Internal Server Error');
	}
}
