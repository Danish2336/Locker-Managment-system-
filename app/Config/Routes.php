<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

$routes->post('issueLocker', 'Home::issueLocker');
$routes->post('releaseLocker', 'Home::releaseLocker');
$routes->get('issueLocker', 'Home::issueLocker');
$routes->get('releaseLocker', 'Home::releaseLocker');