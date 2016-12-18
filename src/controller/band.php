<?php
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

$app
	->get('/группа', function () use ($app, $service) {

		return $app['twig']->render('band.twig', [
			'band' => $service->selectAll('band'),
			'title' => 'Группа',
		]);
	})
	->bind('band');