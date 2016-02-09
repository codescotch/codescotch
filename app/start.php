<?php

/*************************************************************
FRONT CONTROLLER (SLIM 3 FRAMEWORK + TWIG TEMPLATE ENGINE)
*************************************************************/

// autoload vendor classes
require ROOT . '/vendor/autoload.php';

use Slim\App;
use Slim\Container;
use Slim\Views\Twig;
use Slim\Views\TwigExtension;

// default error handler for now, should use a custom error handler
$config = [
    'settings' => [
        'displayErrorDetails' => true,
    ],
];

// create depencency injection container
$container = new Container($config);

// configure database connection
$container['db'] = function() {
	
    return new PDO('mysql:host=79.170.44.110;dbname=cl26-dataadmin', 'cl26-dataadmin', 'pi550ff');
};

// register Twig view helper and configure it
$container['view'] = function($c) {
	
	$view = new Twig(ROOT . '/app/views/');
	$view->addExtension(new TwigExtension(
		$c['router'],
		$c['request']->getUri()
	));
	$view->addExtension(new Twig_Extension_Debug());
	$view->parserOptions = [
		'debug' => true
	];
	return $view;
};

// instantiate app class
$app = new App($container);

// application routes
require 'routes.php';

// run app
$app->run();