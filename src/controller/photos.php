<?php
$app
	->get('/фото', function () use ($app, $service) {
		$albumsFetched = $service->selectAll('albums');
		$albums = [];

		foreach ($albumsFetched as $album) {
			$albumId = $album['id'];
			$photosInAlbum = $service->selectAllWithParameter('photos', 'album_id', $albumId, 'fetchAll');
			$countPhotos = count($photosInAlbum);
			array_push($album, $countPhotos);
			array_push($albums, $album);
		}

		$albums = array_map(function ($album) {
			return [
				'id' => $album['id'],
				'name' => $album['name'],
				'date' => $album['date'],
				'countPhotos' => $album[0],
			];
		}, $albums);

		return $app['twig']->render('photos.twig', [
			'albums' => $albums,
			'title' => 'Фото',
		]);
	})
	->bind('photos');