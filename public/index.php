<?php

/**
 * Front controller
 *
 * PHP version 7.0
 */

ini_set('session.cookie_lifetime', '864000');

/**
 * Composer
 */
require dirname(__DIR__) . '/vendor/autoload.php';


error_reporting(E_ALL);
set_error_handler('Core\Error::errorHandler');
set_exception_handler('Core\Error::exceptionHandler');



session_start();

/**
 * Routing
 */
$router = new Core\Router();

// Add the routes
$router->add('', ['controller' => 'Home', 'action' => 'index']);
$router->add('login', ['controller' => 'Login', 'action' => 'new']);
$router->add('logout', ['controller' => 'Login', 'action' => 'destroy']);
$router->add('password/reset/{token:[\da-f]+}', ['controller' => 'Password', 'action' => 'reset']);
$router->add('signup/activate/{token:[\da-f]+}', ['controller' => 'Signup', 'action' => 'activate']);
$router->add('{controller}/{action}');

$router->add('expenses/getExpenses/{id:[\d]+}/{date:(19|20)\d\d[- /.](0[1-9]|1[012])[- /.](0[1-9]|[12][0-9]|3[01])}/{lastdate:(19|20)\d\d[- /.](0[1-9]|1[012])[- /.](0[1-9]|[12][0-9]|3[01])}', ['controller' => 'Expenses', 'action' => 'getExpenses']);
$router->add('expenses/getCategoryLimit/{id:[\d]+}', ['controller' => 'Expenses', 'action' => 'getCategoryLimit']);


$router->dispatch($_SERVER['QUERY_STRING']);
