<?php
$app
	->get('/login', function (\Symfony\Component\HttpFoundation\Request $request) use ($app) {
		return $app['twig']->render('login.twig', [
			'error' => $app['security.last_error']($request),
			'last_username' => $app['session']->get('_security.last_username'),
			'title' => 'Войти как Root'
		]);
	})
	->bind('login');

$app
	->get('/root', function () use ($app, $service) {
		$admins = $service->selectAllWithParameter('users', 'role', 'ROLE_ADMIN', 'fetchAll');
		return $app['twig']->render('root.twig', [
			'admins' => $admins,
			'title' => 'Войти как root'
		]);
	})
	->bind('root');