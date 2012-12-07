<?php

require 'src/Routy/autoload.php';

use Routy\Router;

$router = new Router('router');

$router->get('user/{num}', function($id)
{
	echo "User id: {$id}";
});

$router->get('user/{name}', function($name)
{
	echo "User name: {$name}";
});

$router->run();