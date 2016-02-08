<?php

$app->get('/', function($request, $response) {
	// $this = app, view = view controller
	return $this->view->render($response, 'home.twig');

})->setName('home');