<?php

// adding namespace breaks google api
// use Models\Concerts\YouTube;

require_once ROOT . '/app/models/concerts/youtube.php';

$app->get('/concerts_testing', function($request, $response) {
	
	$query = "";
	$videoList = [];

	if (isset($_GET['search']) && !empty($_GET['search'])) {

		$youtube = new YouTube();
		$query = $_GET['search'];
		$videoList = $youtube->videoSearch($query, "live", 30);
		$videoList = $youtube->getVideoDetails($videoList);
	}
	return $this->view->render($response, 'concerts/search_testing.twig', [

		'query' => $query,
		'videoList' => $videoList
	]);
})->setName('concerts-testing');