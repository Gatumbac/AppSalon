<?php

namespace Controllers;

use MVC\Router;
use Model\Servicio;
use Model\Cita;
use Model\CitaServicio;
use Model\AdminCita;

class AdminController {
    public static function index(Router $router) {
        session_start();
        isAdmin();

        date_default_timezone_set('America/Guayaquil');
        $fecha = date('Y-m-d');
        $citas = self::getCitas($fecha);

        $router->render('admin/index', [
            'nombre' => $_SESSION['nombre'],
            'id' => $_SESSION['id'],
            'citas' => $citas,
            'fecha' => $fecha
        ]);
    }

    public static function getCitas($fecha) {

        $consulta = "SELECT c.id AS id, c.hora, CONCAT(u.nombre, ' ', u.apellido) AS cliente, u.telefono, u.email, s.nombre AS servicio, s.precio ";
        $consulta .= " FROM citas c ";
        $consulta .= " LEFT JOIN usuarios u ON c.usuario_id = u.id ";
        $consulta .= " LEFT JOIN citas_servicios cs ON c.id = cs.cita_id ";
        $consulta .= " LEFT JOIN servicios s ON s.id = cs.servicio_id ";
        $consulta .= " WHERE c.fecha = '{$fecha}' ";

        $resultado = AdminCita::SQL($consulta);
        return $resultado;
    }
}