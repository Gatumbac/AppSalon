<?php
namespace Model;

class AdminCita extends ActiveRecord {

    protected static $tabla = '';
    protected static $columnasDB = ['id', 'hora', 'cliente', 'telefono', 'email', 'servicio', 'precio'];

    protected $id;
    protected $hora;
    protected $cliente;
    protected $telefono;
    protected $email;
    protected $servicio;
    protected $precio;

    public function __construct($args = []) {
        $this->id = $args['id'] ?? null;
        $this->hora = $args['hora'] ?? '';
        $this->cliente = $args['cliente'] ?? '';
        $this->telefono = $args['telefono'] ?? '';
        $this->email = $args['email'] ?? '';
        $this->servicio = $args['servicio'] ?? '';
        $this->precio = $args['precio'] ?? '';
    }

    // Getters
    public function getId() {
        return $this->id;
    }
    
    public function getHora() {
        return $this->hora;
    }
    
    public function getCliente() {
        return $this->cliente;
    }
    
    public function getTelefono() {
        return $this->telefono;
    }

    public function getEmail() {
        return $this->email;
    }
    
    public function getServicio() {
        return $this->servicio;
    }
    
    public function getPrecio() {
        return $this->precio;
    }

    // Setters
    public function setId($id) {
        $this->id = $id;
    }
    
    public function setHora($hora) {
        $this->hora = $hora;
    }
    
    public function setCliente($cliente) {
        $this->cliente = $cliente;
    }
    
    public function setTelefono($telefono) {
        $this->telefono = $telefono;
    }

    public function setEmail($email) {
        $this->email = $email;
    }
    
    public function setServicio($servicio) {
        $this->servicio = $servicio;
    }
    
    public function setPrecio($precio) {
        $this->precio = $precio;
    }

    public function validar() {
        return self::$alertas;
    }
}