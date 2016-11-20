<?php
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

$app
	->get('/контакты', function () use ($app, $service) {
		$contacts = $service->selectAll('contacts');
		return $app['twig']->render('contacts.twig', [
			'title' => 'Контакты',
			'contacts' => $contacts,
		]);
	})
	->bind('contacts');

$app
	->post('/add_contact', function (Request $request) use($app) {
		$position = trim($request->get('position'));
		$firstname = trim($request->get('firstname'));
		$lastname = trim($request->get('lastname'));
		$phone = trim($request->get('phone'));
		$email = trim($request->get('email'));
		$status[] = '';

		try {
			$app['db']->insert(
				'contacts', [
					'position' => $position,
					'firstname' => $firstname,
					'lastname' => $lastname,
					'phone' => $phone,
					'email' => $email,
				]
			);

			$status = [
				'type' => 'success',
				'message' => 'Контакт успешно добавлен',
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
	->bind('add_contact');

$app
	->post('/delete_contact', function () use($app, $service) {
		$id = $_POST['contact_id'];
		$service->delete('contacts', 'id', $id);
		return $app->redirect($app["url_generator"]->generate('dashboard'));
	})
	->bind('delete_contact');

$app
	->post('/delete_contact', function () use($app) {
		$id = $_POST['contact_id'];
		$app['db']->delete('contacts', ['id'=>$id]);
		return $app->redirect($app["url_generator"]->generate('dashboard'));
	})
	->bind('delete_contact');

$app
	->post('/edit_contact', function () use($app) {
		try {
			$app['db']->update(
				'contacts', [
				'position' => trim($_POST['position']),
				'firstname' => trim($_POST['firstname']),
				'lastname' => trim($_POST['lastname']),
				'phone' => trim($_POST['phone']),
				'email' => trim($_POST['email']),
			], [
					'id' => $_POST['id'],
				]
			);
			return $app->redirect($app["url_generator"]->generate("dashboard"));
		} catch (\Exception $e) {
			return $e->getMessage();
		}
	})
	->bind('edit_contact');