<?php
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

$app
	->get('/группа', function () use ($app, $service) {
		$band = $service->selectAll('band');
		return $app['twig']->render('band.twig', [
			'band' => $band,
			'title' => 'Группа',
		]);
	})
	->bind('band');