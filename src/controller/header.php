<?php
$app
	->get('/header', function () use ($app, $service) {
		return $app['twig']->render('header.twig', [
			'siteName' => 'Чичерина',
			'pages' => $service->selectAll('pages'),
		]);
	})
	->bind('header');