<?php

// adding namespace breaks google api
// use Models\Concerts\YouTube;

require ROOT . '/app/models/concerts/youtube.php';

// search results page
$app->get('/concerts', function($request, $response) {

	$query = "";
	$videoList = [];
	$keywordList = [];
	$errors = "";
	
	if (isset($_GET['search']) && !empty($_GET['search'])) {

		$youtube = new YouTube();
		$query = $_GET['search'];
		$videoList = $youtube->videoSearch($query, "live");
		$videoList = $youtube->getVideoDetails($videoList);
	}
	return $this->view->render($response, 'concerts/search.twig', [

		'query' => $query,
		'videoList' => $videoList
	]);
})->setName('concerts');