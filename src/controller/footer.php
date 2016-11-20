<?php
$app
	->get('/footer', function () use($app) {
		return $app['twig']->render('footer.twig', [

		]);
	})
	->bind('footer');