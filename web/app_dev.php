<?php

if (isset($_SERVER['HTTP_CLIENT_IP'])
    || isset($_SERVER['HTTP_X_FORWARDED_FOR'])
    || !in_array(@$_SERVER['REMOTE_ADDR'], array('127.0.0.1', 'fe80::1', '::1'))
) {
    header('HTTP/1.0 403 Forbidden');
    exit('You are not allowed to access this file. Check '.basename(__FILE__).' for more information.');
}
require_once __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../app/kernel.php';
$app['debug'] = true;
require_once __DIR__ . '/../src/service/service.php';
require_once __DIR__ . '/../src/controller/autoload.php';
$app->boot();
$app->run();