<?php

/*************************************************************
FRONT CONTROLLER (SLIM 3 FRAMEWORK + TWIG TEMPLATE ENGINE)
*************************************************************/

// define common folders
define('ROUTES', dirname(__DIR__) . '/app/routes/');

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
// $container['db'] = function() {
//     return new PDO('mysql:host=127.0.0.1', 'root' '');
// };

// register Twig view helper and configure it
$container['view'] = function($c) {
	$view = new Twig(ROOT . '/app/views/');
	$view->addExtension(new TwigExtension(
		$c['router'],
		$c['request']->getUri()
	));
	$view->parserOptions = [
		'debug' => true
	];
	return $view;
};

// instantiate app class
$app = new App($container);

// application routes
require ROOT . '/app/routes.php';

// run app
$app->run();