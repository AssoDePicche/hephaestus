<?php

declare(strict_types=1);

ini_set('display_errors', 1);

error_reporting(E_ALL);

require_once '../vendor/autoload.php';

$dotenv = \Dotenv\Dotenv::createUnsafeImmutable('../');

$dotenv->load();

$router = \Http\Router::from('../resources/routes.json');

$response = $router->dispatch(new \Http\Request());

$response->send();
