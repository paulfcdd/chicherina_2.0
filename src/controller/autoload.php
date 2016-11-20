<?php
use Silexpack\Service\Service;

$service = new Service($app);

$dir = __DIR__;
$files = scandir($dir);

unset($files[0]);
unset($files[1]);

foreach ($files as $file) {
	require_once $file;
}