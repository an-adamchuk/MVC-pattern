<?php

require_once 'core/Model.php';
require_once 'core/Response.php';
require_once 'core/JsonResponse.php';
require_once 'core/Controller.php';
require_once 'core/Request.php';
require_once 'core/Route.php';
require_once 'core/Router.php';
require_once 'core/DB.php';


// Define connection parameters
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASSWORD', '');
define('DB_DB', 'addresses_app');

$router = new \App\Core\Router();
$router->get('/addresses', 'Addresses@index');
$router->post('/addresses','Addresses@create');
$router->get('/addresses/([0-9]{1,6})', 'Addresses@show', ['id']);
$router->put('/addresses/([0-9]{1,6})', 'Addresses@update', ['id']);
$router->start();
