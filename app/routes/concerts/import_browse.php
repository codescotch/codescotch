<?php

// browse summary of records by query
$app->get('/concerts_testing/import/browse', function($request, $response) {

	$stmt = $this->db->prepare("SELECT DISTINCT `query` FROM `testing` ORDER BY `query` ASC");
	$stmt->execute();
	$queryList = $stmt->fetchAll(PDO::FETCH_ASSOC);

	$i = 0;

	foreach ($queryList as $query) {
		
		$stmt = $this->db->prepare("SELECT
		    SUM(CASE WHEN `id` LIKE '%%' THEN 1 ELSE 0 END) AS numRecords,
		    SUM(CASE WHEN `quality_score` LIKE '%%' THEN 1 ELSE 0 END) AS numQualityScores,
		    SUM(CASE WHEN `quality_score` LIKE '%perfect%' THEN 1 ELSE 0 END) AS perfectQuality,
		    SUM(CASE WHEN `quality_score` LIKE '%good%' THEN 1 ELSE 0 END) AS goodQuality,
		    SUM(CASE WHEN `quality_score` LIKE '%ok%' THEN 1 ELSE 0 END) AS okQuality,
		    SUM(CASE WHEN `quality_score` LIKE '%bad%' THEN 1 ELSE 0 END) AS badQuality,
		    SUM(CASE WHEN `relevance_score` LIKE '%%' THEN 1 ELSE 0 END) AS numRelevanceScores,
		    SUM(CASE WHEN `relevance_score` LIKE '%good%' THEN 1 ELSE 0 END) AS goodRelevance,
		    SUM(CASE WHEN `relevance_score` LIKE '%ok%' THEN 1 ELSE 0 END) AS okRelevance,
		    SUM(CASE WHEN `relevance_score` LIKE '%bad%' THEN 1 ELSE 0 END) AS badRelevance
			FROM `testing` WHERE `query` = '{$query['query']}'");

		$stmt->execute();
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

		$queryList[$i]['stats']['numRecords'] = $result[0]['numRecords'];
		$queryList[$i]['stats']['numQualityScores'] = $result[0]['numQualityScores'];
		$queryList[$i]['stats']['perfectQuality'] = $result[0]['perfectQuality'];
		$queryList[$i]['stats']['goodQuality'] = $result[0]['goodQuality'];
		$queryList[$i]['stats']['okQuality'] = $result[0]['goodQuality'];
		$queryList[$i]['stats']['badQuality'] = $result[0]['badQuality'];
		$queryList[$i]['stats']['numRelevanceScores'] = $result[0]['numRelevanceScores'];
		$queryList[$i]['stats']['goodRelevance'] = $result[0]['goodRelevance'];
		$queryList[$i]['stats']['okRelevance'] = $result[0]['okRelevance'];
		$queryList[$i]['stats']['badRelevance'] = $result[0]['badRelevance'];
		
		$i++;
	}

	$this->view->render($response, 'concerts/import_browse.twig', [
		
		'queryList' => $queryList
	]);
	
})->setName('import-browse');