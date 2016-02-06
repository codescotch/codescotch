<?php

$app->get('/concerts/{name}/import', function($request, $response, $args) {
	
	$name = $args['name'];
	$videoList = [];
	$errors = "";

	$youtube = new YouTube();
	$videoList = $youtube->nameSearch($name);
	$videoList = $youtube->getVideoListStats($videoList);

	foreach ($videoList as $video) {
		$query = "INSERT INTO table_name (";
		$query .= "video_id, title, description, tags, cat_id, publish_date, channel, channel_id, views, likes, dislikes, favorites, comments) VALUES (";
		$query .= $video['id'] . ", ";
		$query .= $video['title'] . ", ";
		$query .= $video['description'] . ", ";
		$query .= $video['tags'] . ", ";
		$query .= $video['categoryId'] . ", ";
		$query .= $video['publishedAt'] . ", ";
		$query .= $video['channelTitle'] . ", ";
		$query .= $video['channelId'] . ", ";
		$query .= $video['views'] . ", ";
		$query .= $video['likes'] . ", ";
		$query .= $video['dislikes'] . ", ";
		$query .= $video['favorites'] . ", ";
		$query .= $video['comments'] . ", ";
		$query .= " LIMIT 1)";
		$this->db->query($query);
	}	

});