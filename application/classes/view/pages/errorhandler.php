<?php defined('SYSPATH') or die('No direct script access.');

class View_Pages_ErrorHandler extends Kostache {

	public $data = array();

	public function base()
	{
		return URL::base(Request::$initial, TRUE);
	}

	public function page()
	{
		return Arr::get($this->data,'page','');
	}

	public function message()
	{
		switch (Arr::get($this->data,'code',''))
		{
			case 404:
				return "that page wasn't found on our server.";
			case 503:
				return "the site is currently down for maintenance.  Please check back in a few minutes...";
			case 500:
				return "there was an internal server error.  Please try again later...";
		}
	}

	public function title()
	{
		return Arr::get($this->data,'title','');
	}

	public function local()
	{
		return Arr::get($this->data,'local','');
	}
}
