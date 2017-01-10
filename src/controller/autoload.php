<?php
use Services\Service;
use Intervention\Image\ImageManager;

/**
 * @var Service $service
 */
$service = new Service($app);

/**
 * @var ImageManager $image
 */
$image = new ImageManager();

$dir = __DIR__;
$files = scandir($dir);

unset($files[0]);
unset($files[1]);

foreach ($files as $file) {
	require_once $file;
}