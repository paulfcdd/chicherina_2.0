<?php
$app
	->get('/root', function () use ($app, $service) {
		$admins = $service->selectAllWithParameter('users', 'role', 'ROLE_ADMIN', 'fetchAll');
		return $app['twig']->render('root.twig', [
			'admins' => $admins,
			'title' => 'Войти как root'
		]);
	})
	->bind('root');