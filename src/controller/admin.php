<?php
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

$app
	->get('/admin', function (\Symfony\Component\HttpFoundation\Request $request) use ($app) {
		return $app['twig']->render('admin.twig', [
			'error' => $app['security.last_error']($request),
			'last_username' => $app['session']->get('_security.last_username'),
			'title' => 'Войти как администратор',
		]);
	})
	->bind('admin');

$app
	->get('/dashboard', function () use ($app, $service) {

		return $app['twig']->render('dashboard.twig', [
			'title' => 'Админинстрирование сайта',
			'logo' => 'Управление сайтом',
			'tours' => $service->selectAll('tours'),
			'albums' => $service->selectAll('albums_test'),
			'riders' => $service->selectAll('rider'),
			'contacts' => $service->selectAll('contacts'),
			'pages' => $service->selectAll('pages'),
		]);
	})
	->bind('dashboard');

$app
	->post('/add_admin', function (Request $request) use ($app) {

		$status[] = '';
		$login = trim($request->get('login'));
		$email = trim($request->get('email'));
		$password = password_hash($request->get('password'), PASSWORD_BCRYPT);
		$role = trim($request->get('role'));

		$findUsername = "SELECT username FROM users WHERE username = '$login'";
		$findEmail = "SELECT email FROM users WHERE email = '$email'";
		$username = $app['db']->fetchAll($findUsername);
		$emailData = $app['db']->fetchAll($findEmail);

		if (!empty($username) or !empty($emailData)) {
			$status = [
				'type' => 'warning',
				'message' => 'Пользователь с данными ' . $login . '/' . $email . ' существует',
			];
		} else {
			try {
				$app['db']->insert('users', [
					'username' => $login,
					'email' => $email,
					'password' => $password,
					'created' => date('Y-m-d'),
					'role' => 'ROLE_' . $role,
				]);
				$status = [
					'type' => 'success',
					'message' => 'Пользователь ' . $login . ' успешно создан',
				];
			} catch (\Exception $e) {
				$status = [
					'type' => 'danger',
					'message' => $e->getMessage(),
				];
			}
		}
		return new JsonResponse($status);
	})
	->bind('add_admin');

$app
	->post('/delete_admin', function () use ($app) {
		$id = $_POST['admin_id'];
		try {
			$app['db']->delete('users', ['id' => $id]);
			return $app->redirect($app["url_generator"]->generate("root"));
		} catch (\Exception $e) {
			throw new Exception($e->getMessage());
		}
	})
	->bind('delete_admin');
