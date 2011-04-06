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
        // Uses Valid::max_length($value, 60);
        array('max_length',array(':value', 80)),
      ),
      'artist' => array(
        array('not_empty'),
        array('max_length',array(':value', 60)),
      ),
    );
  }

  public function filters()
  {
    return array(
      'name' => array(
        array('HTML::chars', array(':value')),
        array('trim', array(':value')),
      ),
      'artist' => array(
        array('HTML::chars', array(':value')),
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

}
