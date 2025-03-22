<?php 

require_once __DIR__ . '/../includes/app.php';

use Controllers\LoginController;
use MVC\Router;

$router = new Router();

// Login
$router->get('/', [LoginController::class, 'login']);
$router->get('/', [LoginController::class, 'login']);
$router->get('/logout', [LoginController::class, 'logout']);

// Password
$router->get('/olvide', [LoginController::class, 'forgot']);
$router->post('/olvide', [LoginController::class, 'forgot']);
$router->get('/recuperar', [LoginController::class, 'recover']);
$router->post('/recuperar', [LoginController::class, 'recover']);

//Account
$router->get('/crear-cuenta', [LoginController::class, 'createForm']);
$router->post('/crear-cuenta', [LoginController::class, 'create']);





$router->checkRoute();