<?php
namespace Controllers;

use MVC\Router;
use Model\Servicio;
use Model\Cita;
use Model\CitaServicio;


class APIController {
    public static function index(Router $router) {
        $servicios = Servicio::all();
        $json =  json_encode($servicios, JSON_UNESCAPED_UNICODE);
        echo $json;
    } 

    public static function guardar(Router $router) {
        //Almacena la cita
        $cita = new Cita($_POST);
        $idServicios = self::getServicios($_POST);

        $errores = $cita->validar();
        $resultado = ['resultado' => false];
        
        if (empty($errores) && !empty($idServicios)) {
            $resultado = $cita->guardar();
        } 

        //Almacena los servicios asociados
        if($resultado['resultado']) {
            self::almacenarServicios($resultado['id'], $idServicios);
        }

        //Almacena los servicios
        echo json_encode($resultado);
    } 

    public static function getServicios(array $post) {
        $serviciosStr = $post['servicios'] ?? '';
        $idServicios = explode(',', $serviciosStr);

        $idServicios = array_filter($idServicios, function($id) {
            return is_numeric($id) && $id > 0;
        });

        return $idServicios;
    }

    public static function almacenarServicios($cita_id , $idServicios) {
        foreach ($idServicios as $idServicio) {
            $args = [
                'cita_id' => $cita_id,
                'servicio_id' => $idServicio
            ];
            $citaServicio = new CitaServicio($args);
            $citaServicio->guardar();
        }
    }
}