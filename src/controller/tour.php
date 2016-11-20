<?php
$app
	->get('/афиша', function () use ($app, $service) {
		$tours = $service->selectAll('tours');
		return $app['twig']->render('tour.twig', [
			'tours' => $tours,
			'title' => 'Афиша',
		]);
	})
	->bind('tour');