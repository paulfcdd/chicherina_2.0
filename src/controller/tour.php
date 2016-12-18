<?php
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @var Request $request
 */
$request = new Request();

$app
    ->get('/афиша', function () use ($app, $service) {
        return $app['twig']->render('tour.twig', [
            'tours' => $service->selectAll('tours'),
            'title' => $service->getMetaData('tour')['title'],
            'description' => $service->getMetaData('tour')['description'],
            'keywords' => $service->getMetaData('tour')['keywords'],
        ]);
    })
    ->bind('tour');

$app
    ->post('/add_tour', function () use ($app, $request) {

        try {
            $app['db']->insert(
                'tours', [
                'date' => trim($request->request->get('date')),
                'city' => trim($request->request->get('city')),
                'location' => trim($request->request->get('place')),
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
    ->post('/delete_tour', function () use ($app, $request) {
        if ($request->isMethod('POST')) {
            $id = $request->request->get('tour_id');
            try {
                $app['db']->delete('tours', ['id' => $id]);

                return $app->redirect(
                    $app["url_generator"]->generate("dashboard")
                );
            } catch (\Exception $e) {
                throw new Exception($e->getMessage());
            }
        }
    })
    ->bind('delete_tour');

$app
    ->post('/edit_tour', function () use ($app, $service, $request) {

        try {
            $app['db']->update(
                'tours', [
                'date' => trim($request->request->get('date')),
                'city' => trim($request->request->get('city')),
                'location' => trim($request->request->get('place')),
            ], [
                'id' => trim($request->request->get('id')),
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