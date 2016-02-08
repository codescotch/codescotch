<?php

// adding namespace breaks google api
// namespace Models\Concerts;

class YouTube {

	public function __construct() {

		// connect to api
		$this->client = new Google_Client();
		$this->client->setApplicationName('datavdata');
		$this->apiKey = "AIzaSyBc_Jr5X4lJXlSwXuBfA3u4cIx6MFTcuJA";
		$this->client->setDeveloperKey($this->apiKey);
		$this->youtube = new Google_Service_YouTube($this->client);
	}

	public function videoSearch($query, $keyword = "", $numResults = 20) {

		if (!empty($query)) {

			$searchQuery = trim($query) . " " . trim($keyword);
			$videoList = [];
			$errors = "";
			$i = 0;

			try {

				// retrieve results matching the specified query
				$results = $this->youtube->search->listSearch('snippet', [
					'q' => $searchQuery,
					'maxResults' => $numResults,
					'type' => 'video',
					'videoEmbeddable' => 'true',
					'videoDefinition' => 'high',
					'videoDuration' => 'long'
				]);
				// add each result to the list
				foreach ($results['items'] as $result) {

					$video['query'] = $query;
					$video['keyword'] = $keyword;
					$video['videoId'] = $result['id']['videoId'];
					$video['title'] = $result['snippet']['title'];
					$video['publishDate'] = $result['snippet']['publishedAt'];
					$video['shortDesc'] = $result['snippet']['description'];
					$video['channel'] = $result['snippet']['channelTitle'];
					$video['iframe'] = "<iframe src=\"https://www.youtube.com/embed/" . $result['id']['videoId'] . "\" frameborder=\"0\" allowfullscreen></iframe>";
					
					$videoList[$video['videoId']] = $video;
				}
			}
			catch (Google_Service_Exception $e) {

				$errors .= sprintf('<p>A service error occurred: <code>%s</code></p>',
				htmlspecialchars($e->getMessage()));
			}
			catch (Google_Exception $e) {

				$errors .= sprintf('<p>An client error occurred: <code>%s</code></p>',
				htmlspecialchars($e->getMessage()));
			}
			return $videoList;
		}
	}

	public function getVideoDetails($videoList) {

		foreach ($videoList as $video) {

			try {

				// retrieve results matching the specified query
				$result = $this->youtube->videos->listVideos('snippet,statistics', [
				'id' => $video['videoId']
				]);

				$video['views'] = $result['items'][0]['statistics']['viewCount'];
				$video['likes'] = $result['items'][0]['statistics']['likeCount'];
				$video['dislikes'] = $result['items'][0]['statistics']['dislikeCount'];
				$video['favorites'] = $result['items'][0]['statistics']['favoriteCount'];
				$video['comments'] = $result['items'][0]['statistics']['commentCount'];
				$video['description'] = $result['items'][0]['snippet']['description'];
				$video['channelId'] = $result['items'][0]['snippet']['channelId'];
				$tags = $result['items'][0]['snippet']['tags'];
				$video['tags'] = $tags;
				$video['catId'] = $result['items'][0]['snippet']['categoryId'];

			$videoList[$video['videoId']] = $video;
			}

			catch (Google_Service_Exception $e) {

				$errors .= sprintf('<p>A service error occurred: <code>%s</code></p>',
				htmlspecialchars($e->getMessage()));
				return $errors;
			}
			catch (Google_Exception $e) {

				$errors .= sprintf('<p>An client error occurred: <code>%s</code></p>',
				htmlspecialchars($e->getMessage()));
				return $errors;
			}
		}
		return $videoList;
	}

}