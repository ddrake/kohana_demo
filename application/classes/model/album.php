<?php defined('SYSPATH') OR die('No direct access allowed.');

class Model_Album extends ORM
{
  protected $_belongs_to = array('genre' => array());

  public function rules()
  {
    return array(
      'name' => array(
        // Uses Valid::not_empty($value);
        array('not_empty'),
        // Uses Valid::max_length($value, 80);
        array('max_length',array(':value', 80)),
      ),
      'artist' => array(
        array('not_empty'),
        array('max_length',array(':value', 60)),
      ),
    );
  }
	public function labels()
	{
		return array(
			'name' => 'Album name',
			'artist' => 'Artist',
			'genre_id' => 'Genre',
		);
	}
  public function filters()
  {
    return array(
      'name' => array(
        array('trim', array(':value')),
      ),
      'artist' => array(
        array('trim', array(':value')),
      ),
    );
  }

	public function save_album($values, $expected)
	{
		// Extra validation for album name - ensure that the album name is unique for the artist.
		$extra_validation = Validation::factory($values)
			->rule('name','not_exists',array(':validation', 'album', ':field', array('artist')));

		return $this->values($values, $expected)->save($extra_validation);
	}

	public static function xml_list()
	{
		$xw = new XMLWriter();
		$xw->openMemory();
		$xw->startDocument('1.0','UTF-8');
		$xw->startElement('Albums');
		$xw->setIndent(true);
		foreach(ORM::factory('Album')->with('genre')->order_by('artist')->order_by('name')->find_all()->as_array() as $album)
		{
			$xw->startElement('Album');
				$xw->startElement('ID');
					$xw->text($album->id);
				$xw->endElement();
				$xw->startElement('Artist');
					$xw->text($album->artist);
				$xw->endElement();
				$xw->startElement('Name');
					$xw->text($album->name);
				$xw->endElement();
				$xw->startElement('Genre');
					$xw->text($album->genre->name);
				$xw->endElement();
			$xw->endElement();  // Album
		}
		$xw->endElement(); // Albums
		$data = $xw->outputMemory(true);
		$xw->flush();
		unset($xw);
		return $data;
	}

}
