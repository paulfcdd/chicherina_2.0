<?php
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;


$app
    ->get('/фото', function () use ($app, $service) {
        $albumsFetched = $service->selectAll('albums_test');
        $albums = [];

        foreach ($albumsFetched as $album) {
            $albumId = $album['id'];
            $photosInAlbum = $service->selectAllWithParameter('photos_test', 'album', $albumId, 'fetchAll');
            $countPhotos = count($photosInAlbum);
            array_push($album, $countPhotos);
            array_push($albums, $album);
        }

        $albums = array_map(function ($album) {
            return [
                'id' => $album['id'],
                'name' => $album['name'],
                'date_created' => $album['date'],
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
        try {
            $app['db']->insert(
                'albums_test', [
                'name' => trim($request->request->get('name')),
                'date_created' => date('Y-m-d'),
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
    ->post('/delete_photo', function (Request $request) use ($app, $service) {


        if ($request->isMethod('POST')) {
            $file = $service->selectAllWithParameter('photos_test', 'id', $request->request->get('photoId'), 'fetchAssoc');
            try {
                $app['db']->delete('photos_test', [
                    'id' => $request->request->get('photoId'),
                ]);
                unlink(__DIR__ . '/../../web' . $file['path']);
                return $app->redirect($app["url_generator"]->generate("album", ['id' => $request->request->get('albumId')]));
            } catch (\Exception $e) {
                throw new Exception($e->getMessage());
            }
        }
    })
    ->bind('delete_photo');

$app
    ->post('/delete_album', function (Request $request) use ($app) {
        $albumId = $request->request->get('id');
        $status[] = null;
        $photos = $app['db']->fetchAll("SELECT * FROM photos_test WHERE album = ?", [(string) $albumId]);

        $app['db']->delete('albums_test', ['id' => $albumId]);

        if (!empty($photos)) {
            foreach ($photos as $photo) {
                unlink(__DIR__ . '/../../web'.$photo['path']);
            }
        }
        $status = [
            'type' => 'success',
            'message' => 'Альбом успешно удален',
        ];
        return new JsonResponse($status);
    })
    ->bind('delete_album');

$app
    ->post('/upload_photo', function (Request $request) use ($app, $service) {

        if ($request->isMethod('POST')) {
            $albumId = $request->request->get('albumId');
            $valid_formats = $app['config']['config']['valid_formats'];
            $max_file_size = intval($app['config']['config']['max_file_size']);
            $path = $app['config']['config']['path'];


            $file_upload = $service->uploadFiles($_FILES['photos'], $max_file_size, $valid_formats, $path);

            if (is_array($file_upload)) {

                for ($i = 0; $i < count($file_upload); $i++) {
                    $app['db']->insert(
                        'photos_test', [
                            'album' => $albumId,
                            'path' => str_replace('web', '', $file_upload[$i]),
                            'date_uploaded' => date('Y-m-d'),
                        ]
                    );
                }
                return $app->redirect($app["url_generator"]->generate("album", ['id' => $albumId]));
            } else {
                return $app->redirect($app["url_generator"]->generate("album", ['id' => $albumId]));
            }
        }

    })
    ->bind('upload_photo');