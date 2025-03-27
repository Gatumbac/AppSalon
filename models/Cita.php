<?php
namespace Model;

class Cita extends ActiveRecord {

    protected static $tabla = 'CITAS';
    protected static $columnasDB = ['id', 'fecha', 'hora', 'usuario_id'];

    protected $id;
    protected $fecha;
    protected $hora;
    protected $usuario_id;

    public function __construct($args = []) {
        $this->id = $args['id'] ?? null;
        $this->fecha = $args['fecha'] ?? '';
        $this->hora = $args['hora'] ?? '';
        $this->usuario_id = $args['usuario_id'] ?? '';
    }

    // Getters
    public function getId() {
        return $this->id;
    }

    public function getFecha() {
        return $this->fecha;
    }

    public function getHora() {
        return $this->hora;
    }

    public function getUsuarioId() {
        return $this->usuario_id;
    }

    // Setters
    public function setId($id) {
        $this->id = $id;
    }

    public function setFecha($fecha) {
        $this->fecha = $fecha;
    }

    public function setHora($hora) {
        $this->hora = $hora;
    }

    public function setUsuarioId($usuario_id) {
        $this->usuario_id = $usuario_id;
    }

    public function validar() {
        self::$alertas = [];
        if(!$this->fecha) {
            self::$alertas['error'][] = 'La fecha es obligatoria';
        }
        
        if(!$this->hora) {
            self::$alertas['error'][] = 'La hora es obligatoria';
        }
        
        if(!$this->usuario_id) {
            self::$alertas['error'][] = 'El cliente es obligatorio';
        }
        return self::$alertas;
    }

}