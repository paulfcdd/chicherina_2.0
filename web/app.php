<?php

ini_set('display_errors', 0);

require_once __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../app/kernel.php';
require_once __DIR__ . '/../src/service/service.php';
require_once __DIR__ . '/../src/service/service.php';
require_once __DIR__ . '/../src/controller/autoload.php';
$app->boot();
$app->run();