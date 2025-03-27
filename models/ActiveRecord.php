<?php
namespace Model;

abstract class ActiveRecord implements \JsonSerializable {
    protected static $db;
    protected static $columnasDB = [];
    protected static $alertas = [];
    protected static $tabla = '';
    protected $id;

    protected static $ultimoError = '';

    protected static function registrarError($mensaje) {
        self::$ultimoError = $mensaje;
    }
    
    public static function getUltimoError() {
        return self::$ultimoError;
    }

    public abstract function validar();

    public static function setDB($database) {
        self::$db = $database;
    }

    public static function getAlertas() {
        return static::$alertas;
    }

    public static function setAlerta($type, $message) {
        static::$alertas[$type][] = $message;
    }

    public static function all() {
        $query = "SELECT * FROM " . static::$tabla;
        $array = static::consultarTabla($query);
        return $array;
    }

    public static function get($cantidad, $order) {
        $query = "SELECT * FROM " . static::$tabla . " ORDER BY id {$order} LIMIT {$cantidad}";
        $array = static::consultarTabla($query);
        return $array;
    }

    public static function find($id) {
        $query = "SELECT * FROM " . static::$tabla . " WHERE id = {$id}";
        $resultado = static::consultarTabla($query);
        return array_shift($resultado);
    }

    public static function where($columna, $valor) {
        $query = "SELECT * FROM " . static::$tabla . " WHERE " . $columna . " = '{$valor}'";
        $resultado = static::consultarTabla($query);
        return array_shift($resultado);
    }

    public function guardar() {
        if(!$this->id) {
            $resultado = $this->crear();
        } else {
            $resultado = $this->actualizar();
        }
        return $resultado ?? false;
    }
    
    public function crear() {
        $atributos = $this->sanitizarDatos();
        $stringColumnas = join(", ", array_keys($atributos));
        $stringValores = join("', '", array_values($atributos));

        $query = "INSERT INTO " . static::$tabla ."(" . $stringColumnas . ") VALUES ('" . $stringValores . "')";

        try {
            $resultado = self::$db->query($query);
        } catch (\Throwable $th) {
            static::registrarError($th->getMessage());
        }

        return [
            'resultado' =>  $resultado ?? false,
            'error' => static::getUltimoError(),
            'id' => self::$db->insert_id
         ];
    }

    public function actualizar() {
        $atributos = $this->sanitizarDatos();
        $valores = [];

        foreach($atributos as $atributo=>$valor) {
            if ($valor === "NULL") {
                $valores[] = "{$atributo}=NULL";
            } else {
                $valores[] = "{$atributo}='{$valor}'";
            }
        }

        $id = self::$db->escape_string($this->id);
        $query = "UPDATE ". static::$tabla ." SET " . join(", ", $valores) . " WHERE id = '{$id}' LIMIT 1";

        try {
            $resultado = self::$db->query($query);
        } catch (\Throwable $th) {
            static::registrarError($th->getMessage());
        }

        return $resultado ?? false;
    }

    public function eliminar() {
        $id = self::$db->escape_string($this->id);
        $query = "DELETE FROM " . static::$tabla . " WHERE id={$id} LIMIT 1";

        try {
            $resultado = self::$db->query($query);
        } catch (\Throwable $th) {
            static::registrarError($th->getMessage());
        }

        return $resultado ?? false;
    }

    public function getAtributos() {
        $atributos = [];
        foreach (static::$columnasDB as $columna) {
            if ($columna === 'id') continue;
            $atributos[$columna] = $this->$columna;
        }
        return $atributos;
    }

    public function sanitizarDatos() {
        $atributos = $this->getAtributos();
        $sanitizados = [];

        foreach ($atributos as $columna => $valor) {
            if ($valor === null) {
                $sanitizados[$columna] = "NULL";
            } else {
                if (is_string($valor)) {
                    $valor = trim($valor);
                }
                $sanitizados[$columna] = self::$db->escape_string($valor);
            }
        }
        return $sanitizados;
    }

    public static function consultarTabla($query) {
        $resultado = self::$db->query($query);
        $array = [];
        while ($registro = $resultado->fetch_assoc()) {
            $array[] = static::crearObjeto($registro);
        }
        return $array;
    }

    public static function crearObjeto(array $registro) {
        $objeto = new static($registro);
        return $objeto;
    }

    public function sincronizar(array $args = []) {
        foreach($args as $atributo=>$valor) {
            if (property_exists($this, $atributo)) {
                $this->$atributo = $valor;
            }
        }
    }

    public function jsonSerialize(): mixed {
        $vars = get_object_vars($this);
        return $vars;
    }
}