<?php
namespace Controllers;

use MVC\Router;
use Model\Usuario;

class CitaController {
    public static function index(Router $router) {
        session_start();

        $router->render('citas/index', [
            'nombre' => $_SESSION['nombre']
        ]);
    }
}