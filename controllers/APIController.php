<?php
namespace Controllers;

use MVC\Router;
use Model\Servicio;

class APIController {
    public static function index(Router $router) {
        $servicios = Servicio::all();
        $json =  json_encode($servicios, JSON_UNESCAPED_UNICODE);
        echo $json;
    } 
}