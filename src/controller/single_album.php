<?php
$app
	->get('/фото/альбом/{id}', function ($id) use($app, $service) {
		$album = $service->selectAllWithParameter('albums_test', 'id', $id, 'fetchAssoc');
		$photos = $service->selectAllWithParameter('photos_test', 'album', $id, 'fetchAll');
		return $app['twig']->render('singleAlbum.twig', [
			'title' => $album['name'],
			'id' => $id,
			'photos' => $photos,
		]);
	})
	->bind('single_album');