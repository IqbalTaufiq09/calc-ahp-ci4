<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'AHP::index');
$routes->post('/calculate', 'AHP::calculate');

