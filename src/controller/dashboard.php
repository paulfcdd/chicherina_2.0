<?php
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

$app
	->get('/dashboard/album/{id}', function ($id) use ($app, $service) {
		$photos = $service->selectAllWithParameter('photos_test', 'album', $id, 'fetchAll');
		$albumData = $service->selectAllWithParameter('albums_test', 'id', $id, 'fetchAssoc');


		return $app['twig']->render('dashboard/photos/album.twig', [
			'id' => $id,
			'logo' => 'Вернуться на главную',
			'photos' => $photos,
			'albumName' => $albumData['name']
		]);
	})
	->bind('album');