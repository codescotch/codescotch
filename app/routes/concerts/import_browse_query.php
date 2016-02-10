<?php

// browse all records for given query
$app->get('/concerts_testing/import/browse/{query}', function($request, $response, $args) {

	$query = $args['query'];
	$stmt = $this->db->prepare("SELECT * FROM `testing` WHERE `query` = '{$query}' GROUP BY `video_id`");
	$stmt->execute();
	$videoList = $stmt->fetchAll(PDO::FETCH_ASSOC);
	
	$this->view->render($response, 'concerts/import_browse_query.twig', [
		'videoList' => $videoList,
		'query' => $query
	]);
});

// update quality score for given video_id
$app->get('/concerts_testing/update_video_quality_score/{video_id}/{score}', function($request, $response, $args) {

	if ($args['score'] = 'clear') {
		
		$stmt = $this->db->prepare(
			"UPDATE `testing` SET `quality_score` = NULL
			WHERE `video_id` = '{$args['video_id']}'");
		$stmt->execute();
	
	} else {

		$stmt = $this->db->prepare(
			"UPDATE `testing` SET `quality_score` = '{$args['score']}' 
			WHERE `video_id` = '{$args['video_id']}'");
		$stmt->execute();
	}	
});

// update relevance score for given video_id
$app->get('/concerts_testing/update_video_relevance_score/{video_id}/{score}', function($request, $response, $args) {

	if ($args['score'] = 'clear') {

		$stmt = $this->db->prepare(
			"UPDATE `testing` SET `relevance_score` = NULL
			WHERE `video_id` = '{$args['video_id']}'");
		$stmt->execute();
		
	} else {

		$stmt = $this->db->prepare(
			"UPDATE `testing` SET `relevance_score` = '{$args['score']}' 
			WHERE `video_id` = '{$args['video_id']}'");
		$stmt->execute();
	}
});

// set all null relevance_score values = 'bad' (use after rating all relevant videos for a query)
$app->get('/concerts_testing/update_video_relevance_null_values/{query}', function($request, $response, $args) {

	$stmt = $this->db->prepare(
		"UPDATE `testing SET `relevance_score` = 'bad' 
		WHERE `query` = '{$args['query']}' AND `relevance_score` is null");
	$stmt->execute();
});