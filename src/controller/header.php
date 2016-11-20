<?php
$app
	->get('/header', function () use ($app, $pages) {
		return $app['twig']->render('header.twig', [
			'siteName' => 'Чичерина',
			'pages' => $pages,
		]);
	})
	->bind('header');