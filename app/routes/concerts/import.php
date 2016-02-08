<?php

// import page
$app->get('/concerts_testing/import', function($request, $response) {

	$this->view->render($response, 'concerts_imports.twig');

})->setName('import');

// process import list
$app->post('/concerts_testing/import_list', function($request, $response) {

	if (isset($_POST['submit'])) {

		$queryList = $_POST['list'];
		$queryList = preg_replace('[\r\n?|\n]',',',trim($queryList));
		$queryList = explode(",", $queryList);
		$keywordList = $_POST['keywords'];
		$keywordList = preg_replace('[\r\n?|\n]',',',trim($keywordList));
		$keywordList = explode(",", $keywordList);
		$numResults = intval($_POST['numResults']);
		$videoList = [];
		$youtube = new YouTube();

		$this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		foreach ($queryList as $query) {

			foreach ($keywordList as $keyword) {

				$videoList = $youtube->videoSearch($query, $keyword, $numResults);
				$videoList = $youtube->getVideoDetails($videoList);
				
				$stmt = $this->db->prepare("INSERT INTO testing (query, keyword, video_id, title, description, tags, cat_id, publish_date, channel, channel_id, views, likes, dislikes, favorites, comments, iframe) VALUES (:query, :keyword, :video_id, :title, :description, :tags, :cat_id, :publish_date, :channel, :channel_id, :views, :likes, :dislikes, :favorites, :comments, :iframe)");

				$rowCount = 0;

				foreach ($videoList as $video) {

					try {

						if (!empty($video['tags'])) {

							$tags = implode(", ", $video['tags']);
						}
						$stmt->execute([
						'query' => $video['query'],
						'keyword' => $video['keyword'],
						'video_id' => $video['videoId'],
						'title' => $video['title'],
						'description' => $video['description'],
						'tags' => $tags,
						'cat_id' => $video['catId'],
						'publish_date' => $video['publishDate'],
						'channel' => $video['channel'],
						'channel_id' => $video['channelId'],
						'views' => $video['views'],
						'likes' => $video['likes'],
						'dislikes' => $video['dislikes'],
						'favorites' => $video['favorites'],
						'comments' => $video['comments'],
						'iframe' => $video['iframe']
						]);
					}
					catch (PDOException $e) {

						$response->write($e->getMessage());
					}
					$rowCount += $stmt->rowCount();
				}
				$message = "Query: " . $query . " " . $keyword . " | Rows inserted: " . $rowCount . "<br>";
				$response->write($message);
			}	
		}
	}
	$response->write("<br><a href=\"" . $this->router->pathFor('import') . "\">Back to imports</a>");

})->setName('import-list');