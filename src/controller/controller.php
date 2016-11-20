<?php
use Silexpack\Service\Service;

$service = new Service($app);

$pages = $service->selectAll('pages');

$app
	->get('/header', function () use ($app, $pages) {
		return $app['twig']->render('header.twig', [
			'siteName' => 'Чичерина',
			'pages' => $pages,
		]);
	})
	->bind('header');

$app
	->get('/footer', function () use($app) {
		return $app['twig']->render('footer.twig', [

		]);
	})
	->bind('footer');

//$app
//	->get('/', function () use ($app) {
//		return $app['twig']->render('index.twig', [
//			'title' => 'Чичерина. Главная',
//		]);
//	})
//	->bind('home');
//
//$app
//	->get('/афиша', function () use ($app, $service) {
//		$tours = $service->selectAll('tours');
//		return $app['twig']->render('tour.twig', [
//			'tours' => $tours,
//			'title' => 'Афиша',
//		]);
//	})
//	->bind('tour');
//
//$app
//	->get('/группа', function () use ($app, $service) {
//		$band = $service->selectAll('band');
//		return $app['twig']->render('band.twig', [
//			'band' => $band,
//			'title' => 'Группа',
//		]);
//	})
//	->bind('band');
//
//$app
//	->get('/фото', function () use ($app) {
//		$getAlbumsQuery = "SELECT * FROM albums";
//		$albumsFetched = $app['db']->fetchAll($getAlbumsQuery);
//		$albums = [];
//
//		foreach ($albumsFetched as $album) {
//			$albumId = $album['id'];
//			$photosInAlbum = $app['db']->fetchAll("SELECT * FROM photos WHERE album_id='$albumId'");
//			$countPhotos = count($photosInAlbum);
//			array_push($album, $countPhotos);
//			array_push($albums, $album);
//		}
//
//		$albums = array_map(function ($album){
//			return [
//				'id' => $album['id'],
//				'name' => $album['name'],
//				'date' => $album['date'],
//				'countPhotos'=>$album[0],
//			];
//		}, $albums);
//
//		return $app['twig']->render('photos.twig', [
//			'albums' => $albums,
//			'title' => 'Фото',
//		]);
//	})
//	->bind('photos');
//
//$app
//	->get('/райдер', function () use ($app) {
//		$rider = $app['db']->fetchAll("SELECT * FROM rider");
//
//		return $app['twig']->render('rider.twig', [
//			'title' => 'Райдер',
//			'riders' => $rider,
//		]);
//	})
//	->bind('rider');
//
//$app
//	->get('/контакты', function () use ($app) {
//		$contacts = $app['db']->fetchAll("SELECT * FROM contacts");
//		return $app['twig']->render('contacts.twig', [
//			'title' => 'Контакты',
//			'contacts' => $contacts,
//		]);
//	})
//	->bind('contacts');
//
//$app
//	->get('/фото/альбом/{id}', function ($id) use($app) {
//		$album = $app['db']->fetchAssoc("SELECT * FROM albums WHERE id = '$id'");
//		$photos = $app['db']->fetchAll("SELECT * FROM photos WHERE album_id='$id'");
//		return $app['twig']->render('singleAlbum.twig', [
//			'title' => $album['name'],
//			'id' => $id,
//			'photos' => $photos,
//		]);
//	})
//	->bind('single_album');
//
//$app
//	->get('/root', function () use ($app) {
//		$sql = "SELECT * FROM `users` WHERE role = 'ROLE_ADMIN'";
//		$admins = $app['db']->fetchAll($sql);
//		return $app['twig']->render('root.twig', [
//			'admins' => $admins,
//			'title' => 'Войти как root'
//		]);
//	})
//	->bind('root');
//foreach ($pages as $page) {
//	$app->get($page['pattern'], function () use ($app, $service, $page) {
//		$data = $service->pagesWithoutTable($page['routeName']);
//		return $app['twig']->render($page['pageName'] . '.twig', [
//			'title' => $page['title'],
//			'description' => $page['description'],
//			'keywords' => $page['keywords'],
//			$page['routeName'] => $data,
//		]);
//	})
//		->bind($page['routeName']);
//}