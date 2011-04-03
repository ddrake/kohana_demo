<?php defined('SYSPATH') OR die('No direct access allowed.');

class Model_Genre extends ORM
{
	protected $_has_many = array('albums' => array());
}
