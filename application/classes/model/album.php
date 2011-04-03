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
        array('HTML::chars', array(':value'))
      ),
      'artist' => array(
        array('HTML::chars', array(':value'))
      ),
    );
  }
}
