<?php defined('SYSPATH') or die('No direct script access.');

class View_Pages_Album_Details extends Kostache_Layout {

	public $title = 'My Albums';
	public $meta_keywords = 'cool, musick';
	public $meta_description = 'A list of awesome albums';
	public $meta_copyright = 'dowdrake.com';

	public function base()
	{
		return URL::base(Request::$initial, TRUE);
	}

	public function name()
	{
		return $this->details->album->name;
	}

	public function artist()
	{
		return $this->details->album->artist;
	}
	public function image()
	{
		return $this->details->album->image[2];
	}
	public function tracks()
	{
		$track_arr = $this->details->album->tracks->track;
		$tracks = array();
		foreach ($track_arr as $t)
		{
			$track = array('track' => array('name' => $t->name,
											'url'  => $t->url,
						   ));
			$tracks[] = $track;
		}
		return $tracks;
	}
	public function links()
	{
		return array(
			'album_list' => Route::get('normal')->uri(array('controller'=>'album')),
		);
	}
}
