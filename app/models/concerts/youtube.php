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

	public function nameSearch($name) {
		if (!empty($name)) {
			$query = $name . " live";			
			$errors = "";
			$video = [];
			$videoList = [];

			try {
				// retrieve results matching the specified query
				$results = $this->youtube->search->listSearch('snippet', array(
				'q' => $query,
				'maxResults' => 50,
				'type' => 'video',
				'videoEmbeddable' => 'true',
				'videoDefinition' => 'high',
				'videoDuration' => 'long'
			));

			// add each result to the list
			foreach ($results['items'] as $result) {
				$video['id'] = $result['id']['videoId'];
				$video['title'] = $result['snippet']['title'];
				$video['publishedAt'] = $result['snippet']['publishedAt'];
				$video['shortDesc'] = $result['snippet']['description'];
				$video['channelTitle'] = $result['snippet']['channelTitle'];
				$video['iframe'] = "<iframe src=\"https://www.youtube.com/embed/" . $result['id']['videoId'] . "\" frameborder=\"0\" allowfullscreen></iframe>";
				
				$videoList[$video['id']] = $video;
			}

			} catch (Google_Service_Exception $e) {
				$errors .= sprintf('<p>A service error occurred: <code>%s</code></p>',
				htmlspecialchars($e->getMessage()));
			} catch (Google_Exception $e) {
				$errors .= sprintf('<p>An client error occurred: <code>%s</code></p>',
				htmlspecialchars($e->getMessage()));
			}
			return $videoList;
		}
	}

	public function getVideoListStats($videoList) {
		foreach ($videoList as $video) {
			try {
				// retrieve results matching the specified query
				$result = $this->youtube->videos->listVideos('snippet,statistics', array(
				'id' => $video['id']
			));

			$video['views'] = $result['items'][0]['statistics']['viewCount'];
			$video['likes'] = $result['items'][0]['statistics']['likeCount'];
			$video['dislikes'] = $result['items'][0]['statistics']['dislikeCount'];
			$video['favorites'] = $result['items'][0]['statistics']['favoriteCount'];
			$video['comments'] = $result['items'][0]['statistics']['commentCount'];
			$video['description'] = $result['items'][0]['snippet']['description'];
			$video['channelId'] = $result['items'][0]['snippet']['channelId'];
			$video['tags'] = $result['items'][0]['snippet']['tags'];
			$video['categoryId'] = $result['items'][0]['snippet']['categoryId'];

			$videoList[$video['id']] = $video;

			} catch (Google_Service_Exception $e) {
				$errors .= sprintf('<p>A service error occurred: <code>%s</code></p>',
				htmlspecialchars($e->getMessage()));
			} catch (Google_Exception $e) {
				$errors .= sprintf('<p>An client error occurred: <code>%s</code></p>',
				htmlspecialchars($e->getMessage()));
			}
		}
		return $videoList;
	}

}