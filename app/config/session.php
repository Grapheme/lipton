<?php

return array(
	'driver' => 'database',
	'lifetime' => 30,
	'expire_on_close' => true,
	'files' => storage_path().'/sessions',
	'connection' => null,
	'table' => 'sessions',
	'lottery' => array(2, 100),
	'cookie' => 'site_session',
	'path' => '/',
	'domain' => null,
	'secure' => false
);