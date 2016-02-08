<?php

// should combine with search route

$app->get('/concerts/{query}', function($request, $response, $args) {

	// create name variable from url value
	$query = $args['query'];
	$videoList = [];
	$keywordList = [];
	$errors = "";

	$youtube = new YouTube();
	$videoList = $youtube->videoSearch($query, "live");
	$videoList = $youtube->getVideoDetails($videoList);

	return $this->view->render($response, 'concerts.twig', [
		'query' => $query,
		'videoList' => $videoList
	]);
})->setName('concerts-artist');