<?php
use Silexpack\Service\Service;

$service = new Service($app);

$pages = $service->selectAll('pages');

$app
	->get('/header', function () use ($app, $pages) {
		return $app['twig']->render('default/header.twig', [
			'siteName' => 'Silexpack',
			'pages' => $pages,
		]);
	})
	->bind('header');

$app
	->get('/footer', function () use($app) {
		return $app['twig']->render('default/footer.twig', [

		]);
	})
	->bind('footer');

foreach ($pages as $page) {
	$app->get($page['pattern'], function () use ($app, $service, $page) {
		return $app['twig']->render('default/' . $page['pageName'] . '.twig', [
			'title' => $page['title'],
			'description' => $page['description'],
			'keywords' => $page['keywords'],
		]);
	})
		->bind($page['routeName']);
}