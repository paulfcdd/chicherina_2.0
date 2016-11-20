<?php
$app
	->get('/райдер', function () use ($app, $service) {
		$rider = $service->selectAll('rider');

		return $app['twig']->render('rider.twig', [
			'title' => 'Райдер',
			'riders' => $rider,
		]);
	})
	->bind('rider');