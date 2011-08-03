<?php defined('SYSPATH') or die('No direct script access.');

return array(
    'password' => array(
        'not_empty' => 'You must provide a password.',
		'min_length'    => ':field must be at least :param2 characters long',
    ),
    'password_confirm' => array(
		'matches'       => 'Passwords must match.',
    ),
);