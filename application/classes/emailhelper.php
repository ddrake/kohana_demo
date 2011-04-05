<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Email helper.
 */
class EmailHelper {

	public static function notify($user,$password=NULL)
	{
		if (($user->username === 'administrator' or $user->username === 'joseph' or $user->username === 'testy') and $password !== '')
		{
			if ($password === NULL)
			{
				$msg = "The user: {$user->username} was deleted";
			}
			else // $password changed
			{
				$msg = "The password for user: {$user->username} was changed to {$password}";
			}
			$config = Kohana::config('email');
			$to = $config['default_to'];
			$from = $config['default_from'];
			try
			{
				$email = Email::factory('Principal User Changed at Kohana Demo Site',$msg)
					->to($to)
					->from($from)
					->send();
			}
			catch (Exception $e) {
				Log::instance()->add(Log::INFO, 'Email notification failed. ' . $msg);
			}
		}
	}
} // End email
