<?php
$app
	->get('/', function () use ($app) {
		return $app['twig']->render('index.twig', [
			'title' => 'Чичерина. Главная',
		]);
	})
	->bind('home');