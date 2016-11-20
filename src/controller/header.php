<?php
$app
	->get('/header', function () use ($app, $service) {
		$pages = $service->selectAll('pages');
		return $app['twig']->render('header.twig', [
			'siteName' => 'Чичерина',
			'pages' => $pages,
		]);
	})
	->bind('header');