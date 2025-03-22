<?php
namespace Model;

class User extends ActiveRecord {

    protected static $table = 'USUARIOS';
    protected static $columns = ['id', 'nombre', 'apellido', 'email', 'password', 'telefono', 'admin', 'confirmado', 'token'];

    protected $nombre;
    protected $apellido;
    protected $email;
    protected $password;
    protected $telefono;
    protected $admin;
    protected $confirmado;
    protected $token;

    public function __construct($args = []) {
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->apellido = $args['apellido'] ?? '';
        $this->email = $args['email'] ?? '';
        $this->password = $args['password'] ?? '';
        $this->telefono = $args['telefono'] ?? '';
        $this->admin = $args['admin'] ?? null;
        $this->confirmado = $args['confirmado'] ?? null;
        $this->token = $args['token'] ?? '';
    }

    // Getters
    public function getId() {
        return $this->id;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function getApellido() {
        return $this->apellido;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getPassword() {
        return $this->password;
    }

    public function getTelefono() {
        return $this->telefono;
    }

    public function getAdmin() {
        return $this->admin;
    }

    public function getConfirmado() {
        return $this->confirmado;
    }

    public function getToken() {
        return $this->token;
    }

    public function validate() {
        self::$alerts = [];
        if (!$this->nombre || strlen($this->nombre) < 3) {
            self::$alerts['error'][] = 'El nombre es obligatorio y debe ser válido';
        }
        if (!$this->apellido || strlen($this->apellido) < 3) {
            self::$alerts['error'][] = 'El apellido es obligatorio y debe ser válido';
        }
        if (!$this->telefono) {
            self::$alerts['error'][] = "El teléfono es obligatorio";
        } else if (!preg_match('/^09\d{8}$/', $this->telefono)) {
            self::$alerts['error'][] = "Formato Inválido de Teléfono";
        }

        if (!$this->email) {
            self::$alerts['error'][] = 'El email es obligatorio';
        } else if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)){
            self::$alerts['error'][] = 'Email con formato inválido';
        }

        if (!$this->password) {
            self::$alerts['error'][] = 'La contraseña es obligatoria';
        } else if (strlen($this->password) < 6) {
            self::$alerts['error'][] = 'La contraseña debe tener al menos 6 caracteres';
        }
        return self::$alerts;
    }

    public function verifyExistence() {
        $email = self::$db->escape_string($this->email);
        $query = "SELECT * FROM " . self::$table . " WHERE email = '{$email}' LIMIT 1";
        $result = self::$db->query($query);
        return $result->num_rows > 0;
    }

    public function validateUnicity() {
        if ($this->verifyExistence()) {
            self::$alerts['error'][] = 'El usuario ya está registrado';
            return false;
        }
        return true;
    }

    public function hashPassword() {
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
    }
}