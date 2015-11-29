<?php defined('SYSPATH') OR die('No direct access allowed.');

return array(
	'driver'       => 'ORM',
	'hash_method'  => 'sha256',
	'hash_key'     => '316ae06052f7',
	'lifetime'     => Date::HOUR * 2,
	'session_type' => Session::$default,
	'session_key'  => 'auth_user',
);
