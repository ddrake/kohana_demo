<?php defined('SYSPATH') or die('No direct access allowed.');

class Model_User extends Model_Auth_User {

	public function labels()
	{
		return array(
			'username' => 'User Name',
			'email' => 'Email',
			'password' => 'Password',
		);
	}
	public static function get_password_validation($values)
	{
		return Validation::factory($values)
			->rule('password', 'min_length', array(':value', 8))
			->rule('password_confirm', 'matches', array(':validation', ':field', 'password'))
			->labels(array(
				'password' => 'Password',
				'password_confirm' => 'Password Confirmation'
			));
	}

} // End User Model