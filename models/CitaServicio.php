<?php
namespace Model;

class CitaServicio extends ActiveRecord {

    protected static $tabla = 'CITAS_SERVICIOS';
    protected static $columnasDB = ['id', 'cita_id', 'servicio_id'];

    protected $id;
    protected $cita_id;
    protected $servicio_id;

    public function __construct($args = []) {
        $this->id = $args['id'] ?? null;
        $this->cita_id = $args['cita_id'] ?? '';
        $this->servicio_id = $args['servicio_id'] ?? '';
    }

    public function getId() {
        return $this->id;
    }

    public function getCitaId() {
        return $this->cita_id;
    }

    public function getServicioId() {
        return $this->servicio_id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setCitaId($cita_id) {
        $this->cita_id = $cita_id;
    }

    public function setServicioId($servicio_id) {
        $this->servicio_id = $servicio_id;
    }

    public function validar() {
        self::$alertas = [];
        
        if(!$this->cita_id) {
            self::$alertas['error'][] = 'La cita es obligatoria';
        }
        
        if(!$this->servicio_id) {
            self::$alertas['error'][] = 'El servicio es obligatorio';
        }
        
        return self::$alertas;
    }

}