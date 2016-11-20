<?php
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

$app
	->get('/райдер', function () use ($app, $service) {
		$rider = $service->selectAll('rider');

		return $app['twig']->render('rider.twig', [
			'title' => 'Райдер',
			'riders' => $rider,
		]);
	})
	->bind('rider');

$app
	->post('/add_rider', function () use($app, $service){
		$valid_formats = ['pdf', 'doc', 'docx'];
		$max_file_size = 1024000;
		$path = '/web/media/rider/';

		$file_upload = $service->uploadFiles($_FILES['rider'], $max_file_size,$valid_formats, $path);
		if (is_array($file_upload)) {

			for ($i = 0; $i < count($file_upload); $i++) {
				$app['db']->insert(
					'rider', [
						'path' => $file_upload[$i],
						'date' => date('Y-m-d'),
					]
				);
			}
			return $app->redirect($app["url_generator"]->generate("dashboard"));
		} else {
			return $app->redirect($app["url_generator"]->generate("dashboard"));
		}
	})
	->bind('add_rider');

$app
	->post('/delete_rider', function (Request $request) use ($app) {
		$id = $request->get('id');
		$status[] = null;
		$file = $app['db']->fetchAssoc("SELECT path FROM rider WHERE id = '$id'");

		try {
			$app['db']->delete('rider', ['id' => $id]);
			unlink(__DIR__ . $file['path']);
			$status = [
				'type' => 'success',
				'message' => 'Документ успешно удален',
			];
			return new JsonResponse($status);
		} catch (\Exception $e) {
			$status = [
				'type' => 'warning',
				'message' => $e->getMessage(),
			];
			return new JsonResponse($status);
		}


	})
	->bind('delete_rider');