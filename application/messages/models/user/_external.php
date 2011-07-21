<?php defined('SYSPATH') or die('No direct script access.');

return array(
    'password' => array(
        'not_empty' => 'You must provide a password.',
    ),
    'password_confirm' => array(
			'matches'       => 'The passwords must match.',
    ),
);