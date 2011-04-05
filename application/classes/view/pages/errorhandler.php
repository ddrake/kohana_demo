<?php defined('SYSPATH') or die('No direct script access.');

class View_Pages_ErrorHandler extends Kostache {

	public $meta_keywords = 'cool, musick';
	public $meta_copyright = 'dowdrake.com';
	public $meta_description = 'Error description page';

	public function message()
	{
		switch ($this->code)
		{
			case 404:
				return "that page wasn't found on our server.";
			case 503:
				return "the site is currently down for maintenance.  Please check back in a few minutes...";
			case 500:
				return "there was an internal server error.  Please try again later...";
			default:
				return $this->raw_message;
		}
	}

	public function base()
	{
		return URL::base(Request::$initial, TRUE);
	}

	public function scripts()
	{
		return array(
			'assets/js/jquery-1.5.2.min.js',
			'assets/js/jqtest.js'
		);
	}
}
