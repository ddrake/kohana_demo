<?php defined('SYSPATH') or die('No direct script access.');

class View_Pages_Album_List extends Kostache_Layout {

	public $title = 'My Albums';
	public $meta_keywords = 'cool, musick';
	public $meta_description = 'A list of awesome albums';
	public $meta_copyright = 'dowdrake.com';
	public $list_title = 'Current Album List';
	private $query;
	
	public function __construct($template = NULL, array $partials = NULL)
	{
		parent::__construct($template, $partials);
		$this->query = Request::current()->query();
		$session = Session::instance();
		$session->set('album_list',$this->query);
		
		// Create pagination instance
		$this->pagination = Kostache_Pagination::factory(array(
			'kostache' => $this,
			'items_per_page' => 5,
			// unless we pass the count here, the number of pages, etc. don't get calculated in the setup
			'total_items' => $this->get_count(), 
			'partial' => 'pagination',
			'view' => 'pagination/basic',
		));
	}	
	// PUBLIC METHODS FOR USE BY THE TEMPLATE
	public function base()
	{
		return URL::base(Request::$initial, TRUE);
	}
    public function pagination()
    {
        return $this->pagination->render();
    }
	public function genre_list_filter()
	{
		$genre_list = array(array('id'=>NULL, 'name'=>'<No Selection>'));
		$genre_model = ORM::Factory('genre');
		$val = Request::current()->query('fc-album:genre_id');
		if (!empty($val))
		{
			list($op,$val) = explode('|',$val,2);
		}
		foreach ($genre_model->find_all()->as_array() as $g)
		{
			$genre = $g->as_array();
			$genre['selected'] = ($genre['id'] == (int)$val);
			$genre_list[] = $genre;
		}
		return $genre_list;
	}
	public function name_filter()
	{
		$val = Request::current()->query('fc-album:name');
		if (!empty($val))
		{
			list($op,$val) = explode('|',$val,2);
		}
		return $val;
	}
	public function artist_filter()
	{
		$val = Request::current()->query('fc-album:artist');
		if (!empty($val))
		{
			list($op,$val) = explode('|',$val,2);
		}
		return $val;
	}
	public function albums()
	{
		// using with('genre') improves efficiency by pre-loading the genre info (avoids lazy loading)
		$route = Route::get('default');
		$as = ORM::factory('Album')
			->with('genre');
		$as->select(array('genre.name','genre_name'));
		
		$this->albums_apply_filters($as);
		
		$sort = $this->get_sort_from_request(TRUE);
		if ($sort !== NULL and count($sort) > 0) 
		{
			$as->order_by($sort['column'], $sort['dir']);
		}
		if ($sort['column'] != 'name') 
		{
			$as->order_by('name', 'asc');
		}
		
		$as = $as->limit($this->pagination->items_per_page)
			->offset($this->pagination->offset)
			->find_all()
			->as_array();
		
		$albums = array();
		$odd = TRUE;
		foreach($as as $album)
		{
			$album = $album->as_array();
			$album['details_link'] = $route->uri(array('controller'=>'album', 'action'=>'details', 'id'=>$album['id']));
			$album['edit_link'] = $route->uri(array('controller'=>'album', 'action'=>'edit', 'id'=>$album['id']));
			$album['delete_link'] = $route->uri(array('controller'=>'album', 'action'=>'delete', 'id'=>$album['id']));
			$album['odd'] = $odd;
			$albums[] = $album;
			$odd = ! $odd;
		}
		return $albums;
	}
	public function links()
	{
		$route = Route::get('default');
		return array(
			'album_add' => $route->uri(array('controller'=>'album', 'action'=>'add')),
			'user_logout' => $route->uri(array('controller'=>'user', 'action'=>'logout')),
			'user_login' => $route->uri(array('controller'=>'user', 'action'=>'login')),
			'user_profile' => $route->uri(array('controller'=>'user')),
			'admin_list' => $route->uri(array('controller'=>'admin')),
		);
	}
	public function scripts()
	{
		return array(
			'assets/js/jquery-1_5_2_min.js',
			'assets/js/ajax_list.js',
			'assets/js/jquery.url.js',
			'assets/js/filter_list.js',
		);
	}
	public function logged_in_user()
	{
		$user = Auth::instance()->get_user();
		return empty($user) ? NULL : $user->username;
	}
	public function user_is_admin()
	{
		return Auth::instance()->logged_in('admin');
	}
	
	// HELPERS
	// Count for use by pagination
	private function get_count()
	{
		// initially, just get a count of all the albums here.  In general, we may want this to be based on some filter settings
		// from the controller -- probably want to insert a where clause or two in there...
		$as = ORM::factory('Album');
		$this->albums_apply_filters($as);
		return $as->count_all();
	}
	
	// FILTERS
	// Takes an ORM Album object and applies the filters to it.
	private function albums_apply_filters($as)
	{
		$filters = $this->get_filters();
		if (count($filters) > 0) 
		{
			$f = $filters[0];
			$as = $as->where($f['column'], $f['op'], $f['value']);
		}
		for ($i = 1; $i < count($filters); $i++)
		{
			$f = $filters[$i];
			$as = $as->and_where($f['column'], $f['op'], $f['value']);
		}	
	}
	// get the filters for use by the orm 
	private function get_filters()
	{
		$filters = array();
		foreach (Request::current()->query() as $key => $value)
		{
			$ka = explode('-',$key,2);
			if ($ka[0] == 'fc')
			{
				list($op,$val) = explode('|',$value,2);
				if ($op == 'eq') $op = '=';
				else $val = $val . '%';
				$filters[] = array('column' => str_replace(':','.',$ka[1]), 'op' => $op, 'value' => $val);
			}
		}
		return $filters;
	}
	
	// SORT
	// get the sort spec for use by the orm or template
	private function get_sort_from_request($colon_to_dot = FALSE)
	{
		$sort = NULL;
		foreach (Request::current()->query() as $key => $value)
		{
			$ka = explode('-',$key,2);
			if ($ka[0] == 'sc')
			{
				$sort=array('column' => $ka[1], 'dir' => $value);
				if ($colon_to_dot)
				{
					$sort['column'] = str_replace(':','.',$sort['column']);
				}
				break;
			}
		}
		return $sort;
	}
	// get the sort array for use by the template
	public function sort()
	{
		$s = $this->get_sort_from_request();
		$sort = array();
		if ($s !== NULL)
		{
			$sort[$s['column']] =  'sort-' . $s['dir'];
		}
		return $sort;
	}
}
