<?php
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

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

$app
	->post('/add_album', function (Request $request) use ($app) {
		$status[] = '';
		$name = trim($request->get('name'));

		try {
			$app['db']->insert(
				'albums', [
				'name' => $name,
				'date' => date('Y-m-d'),
			]);
			$status = [
				'type' => 'success',
				'message' => 'Альбом успешно добавлен',
			];
			return new JsonResponse($status);

		} catch (\Exception $e) {
			$status = [
				'type' => 'danger',
				'message' => $e->getMessage(),
			];
			return new JsonResponse($status);
		}
	})
	->bind('add_album');

$app
	->post('/delete_photo', function () use($app, $service) {
		$id = $_POST['deletePhoto'];
		$albumId = $_POST['albumId'];
		$file = $service->selectAllWithParameter('photos', 'id', $id, 'fetchAssoc');
		try {
			$app['db']->delete('photos', ['id' => $id]);
			unlink(__DIR__ . $file['name']);
			return $app->redirect($app["url_generator"]->generate("album", ['id' => $albumId]));
		} catch (\Exception $e) {
			throw new Exception($e->getMessage());
		}
	})
	->bind('delete_photo');

$app
	->post('/delete_album', function (Request $request) use($app) {
		$albumId = $request->get('id');
		$status[] = null;
		$photos = $app['db']->fetchAll("SELECT * FROM photos WHERE album_id = '$albumId'");

		if (!empty($photos)) {
			$status = [
				'type' => 'warning',
				'message' => 'В альбоме есть фотографии',
			];
			return new JsonResponse($status);
		} else {
			$app['db']->delete('albums', ['id' => $albumId]);
			$status = [
				'type' => 'success',
				'message' => 'Альбом успешно удален',
			];
			return new JsonResponse($status);
		}
	})
	->bind('delete_album');

$app
	->post('/upload_photo', function () use ($app, $service) {
		$albumId = $_POST['albumId'];
		$valid_formats = $app['config']['file.upload']['valid_formats'];
		$max_file_size = intval($app['config']['file.upload']['max_file_size']);
		$path = $app['config']['file.upload']['path'];

		$file_upload = $service->uploadFiles($_FILES['photos'], $max_file_size,$valid_formats, $path);

		if (is_array($file_upload)) {

			for ($i = 0; $i < count($file_upload); $i++) {
				$app['db']->insert(
					'photos', [
						'album_id' => $albumId,
						'name' => $file_upload[$i],
						'date' => date('Y-m-d'),
					]
				);
			}
			return $app->redirect($app["url_generator"]->generate("album", ['id' => $albumId]));
		} else {
			return $app->redirect($app["url_generator"]->generate("album", ['id' => $albumId]));
		}
	})
	->bind('upload_photo');