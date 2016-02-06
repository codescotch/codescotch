<?php

// adding namespace breaks google api
// use Models\Concerts\YouTube;

require ROOT . '/app/models/concerts/youtube.php';

$app->get('/concerts', function($request, $response, $args) {
	
	$name = "";
	$videoList = [];
	$errors = "";
	
	if (isset($_GET['search']) && !empty($_GET['search'])) {
		$name = $_GET['search'];
		$youtube = new YouTube();
		$videoList = $youtube->nameSearch($name);
		$videoList = $youtube->getVideoListStats($videoList);
	}
	
	return $this->view->render($response, 'concerts.twig', [
		'name' => $name,
		'videoList' => $videoList
	]);

})->setName('concerts');