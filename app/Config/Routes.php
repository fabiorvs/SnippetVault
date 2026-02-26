<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('login', 'AuthController::login');
$routes->post('login', 'AuthController::attempt');
$routes->get('logout', 'AuthController::logout');

$routes->get('/', 'VaultController::index', ['filter' => 'auth']);

$routes->group('api', ['filter' => 'auth'], function ($routes) {
    $routes->get('snippets', 'Api\SnippetsController::index');
    $routes->post('snippets', 'Api\SnippetsController::create');
    $routes->get('snippets/(:segment)', 'Api\SnippetsController::show/$1');
    $routes->put('snippets/(:segment)', 'Api\SnippetsController::update/$1');
    $routes->delete('snippets/(:segment)', 'Api\SnippetsController::destroy/$1');
});
