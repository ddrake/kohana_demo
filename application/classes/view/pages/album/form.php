<?php defined('SYSPATH') or die('No direct script access.');

class View_Pages_Album_Form extends Kostache_Layout {

	public $meta_keywords = 'cool, musick';
	public $meta_copyright = 'dowdrake.com';

	public $title = '';
	public $meta_description = '';
	public $editor_title = '';

	public function __construct($template = NULL, array $partials = NULL)
	{
		parent::__construct('pages/album/form', $partials);
	}

	public function base()
	{
		return URL::base(Request::$initial, TRUE);
	}

	public function links()
	{
		$route = Route::get('default');
		return array(
			'save' => $route->uri(array('controller'=>'album', 'action'=>'save')),
		);
	}

	public function genre_list()
	{
		$genre_list = array();
		$genre_model = ORM::Factory('genre');
		foreach ($genre_model->find_all()->as_array() as $genre)
		{
			$genre = $genre->as_array();
			$genre['selected'] = ($genre['id'] == $this->album['genre_id']);
			$genre_list[] = $genre;
		}
		return $genre_list;
 	}

	public function styles()
	{
		return array(
			'assets/css/form.css',
		);
	}

	public function scripts()
	{
		return array(
			'assets/js/jquery-1_5_2_min.js',
			'assets/js/jqtest.js'
		);
	}

}
