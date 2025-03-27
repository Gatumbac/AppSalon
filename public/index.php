<?php 

require_once __DIR__ . '/../includes/app.php';

use Controllers\LoginController;
use Controllers\CitaController;
use Controllers\APIController;


use MVC\Router;

$router = new Router();

// Login
$router->get('/', [LoginController::class, 'formularioLogin']);
$router->post('/', [LoginController::class, 'login']);
$router->get('/logout', [LoginController::class, 'logout']);

// Password
$router->get('/olvide', [LoginController::class, 'formularioOlvide']);
$router->post('/olvide', [LoginController::class, 'olvide']);
$router->get('/recuperar', [LoginController::class, 'formularioRecuperar']);
$router->post('/recuperar', [LoginController::class, 'recuperar']);

//Cuenta
$router->get('/crear-cuenta', [LoginController::class, 'formularioCrear']);
$router->post('/crear-cuenta', [LoginController::class, 'crear']);
$router->get('/confirmar-cuenta', [LoginController::class, 'confirmar']);
$router->get('/mensaje', [LoginController::class, 'mensaje']);

//Area privada 
$router->get('/cita', [CitaController::class, 'index']);

//API de CITAS
$router->get('/api/servicios', [APIController::class, 'index']);
$router->post('/api/citas', [APIController::class, 'guardar']);




$router->checkRoute();