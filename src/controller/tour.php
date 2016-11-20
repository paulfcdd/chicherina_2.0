<?php
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

$app
	->get('/афиша', function () use ($app, $service) {
		$tours = $service->selectAll('tours');
		return $app['twig']->render('tour.twig', [
			'tours' => $tours,
			'title' => 'Афиша',
		]);
	})
	->bind('tour');

$app
	->post('/add_tour', function (Request $request) use ($app) {
		$status[] = '';
		$date = trim($request->get('date'));
		$city = trim($request->get('city'));
		$place = trim($request->get('place'));

		try {
			$app['db']->insert(
				'tours', [
				'date' => $date,
				'city' => $city,
				'location' => $place,
			]);
			$status = [
				'type' => 'success',
				'message' => 'Концерт успешно добавлен',
			];
			return new JsonResponse($status);

		} catch (\Exception $e) {
			$status = [
				'type' => 'success',
				'message' => $e->getMessage(),
			];
			return new JsonResponse($status);
		}
	})
	->bind('add_tour');

$app
	->post('/delete_tour', function () use ($app) {
		$id = $_POST['tour_id'];

		try {
			$app['db']->delete('tours', ['id' => $id]);
			return $app->redirect($app["url_generator"]->generate("dashboard"));
		} catch (\Exception $e) {
			throw new Exception($e->getMessage());
		}
	})
	->bind('delete_tour');

$app
	->post('/edit_tour', function (Request $request) use ($app, $service) {
		$status[] = '';
		$id = trim($request->get('id'));
		$date = trim($request->get('date'));
		$city = trim($request->get('city'));
		$place = trim($request->get('place'));

		try {
			$app['db']->update(
				'tours', [
				'date' => $date,
				'city' => $city,
				'location' => $place,
			], [
				'id' => $id,
			]);
			$status = [
				'type' => 'success',
				'message' => 'Концерт успешно изменен',
			];
			return new JsonResponse($status);

		} catch (\Exception $e) {
			$status = [
				'type' => 'success',
				'message' => $e->getMessage(),
			];
			return new JsonResponse($status);
		}
	})
	->bind('edit_tour');