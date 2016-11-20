<?php
$app
	->get('/фото/альбом/{id}', function ($id) use($app, $service) {
		$album = $service->selectAllWithParameter('albums', 'id', $id, 'fetchAssoc');
		$photos = $service->selectAllWithParameter('photos', 'album_id', $id, 'fetchAll');
		return $app['twig']->render('singleAlbum.twig', [
			'title' => $album['name'],
			'id' => $id,
			'photos' => $photos,
		]);
	})
	->bind('single_album');