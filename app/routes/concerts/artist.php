<?php

// should combine with search route

$app->get('/concerts/{name}', function($request, $response, $args) {

	// create name variable from url value
	$name = $args['name'];
	$videoList = [];
	$errors = "";

	$youtube = new YouTube();
	$videoList = $youtube->nameSearch($name);
	$videoList = $youtube->getVideoListStats($videoList);

	return $this->view->render($response, 'concerts.twig', [
		'name' => $name,
		'videoList' => $videoList
	]);

})->setName('concerts-artist');