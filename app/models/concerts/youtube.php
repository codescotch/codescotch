<?php

// adding namespace breaks google api
// namespace Models\Concerts;

class YouTube {

	public function __construct() {
		// youtube api
		$this->client = new Google_Client();
		$this->client->setApplicationName('datavdata');
		$this->apiKey = "AIzaSyBc_Jr5X4lJXlSwXuBfA3u4cIx6MFTcuJA";
		$this->client->setDeveloperKey($this->apiKey);
		$this->youtube = new Google_Service_YouTube($this->client);
	}

	public function nameSearch($name) {
		if (!empty($name)) {
			$query = $name . " live";			

			try {
				// Call the search->list method to retrieve results matching the specified query
				$searchResponse = $this->youtube->search->listSearch('id,snippet', array(
				'q' => $query,
				'maxResults' => 20,
				'type' => 'video',
				'videoEmbeddable' => 'true',
				'videoDefinition' => 'high',
				'videoDuration' => 'long'
			));

		    $video = [];

			// Add each result to the list, then display the list
			foreach ($searchResponse['items'] as $searchResult) {
				$video['iframe'] = "<iframe src=\"https://www.youtube.com/embed/" . $searchResult['id']['videoId'] . "\" frameborder=\"0\" allowfullscreen></iframe>";
				$videoList[] = $video;
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
}