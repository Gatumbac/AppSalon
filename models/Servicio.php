<?php
namespace Model;

class Servicio extends ActiveRecord {

    protected static $tabla = 'SERVICIOS';
    protected static $columnasDB = ['id', 'nombre', 'precio'];

    protected $id;
    protected $nombre;
    protected $precio;

    public function __construct($args = []) {
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->precio = $args['precio'] ?? '';
    }

    // Getters
    public function getId() {
        return $this->id;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function getPrecio() {
        return $this->precio;
    }

    // Setters
    public function setId($id) {
        $this->id = $id;
    }

    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    public function setPrecio($precio) {
        $this->precio = $precio;
    }

    public function validar() {
        self::$alertas = [];
        
        if(!$this->nombre) {
            self::$alertas['error'][] = 'El nombre del servicio es obligatorio';
        }
        
        if(!$this->precio) {
            self::$alertas['error'][] = 'El precio del servicio es obligatorio';
        }
        
        return self::$alertas;
    }

}