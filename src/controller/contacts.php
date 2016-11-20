<?php
$app
	->get('/контакты', function () use ($app, $service) {
		$contacts = $service->selectAll('contacts');
		return $app['twig']->render('contacts.twig', [
			'title' => 'Контакты',
			'contacts' => $contacts,
		]);
	})
	->bind('contacts');