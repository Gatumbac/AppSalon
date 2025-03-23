<?php
namespace Model;

class Usuario extends ActiveRecord {

    protected static $tabla = 'USUARIOS';
    protected static $columnasDB = ['id', 'nombre', 'apellido', 'email', 'password', 'telefono', 'admin', 'confirmado', 'token'];

    protected $id;
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
        $this->admin = $args['admin'] ?? '0';
        $this->confirmado = $args['confirmado'] ?? '0';
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

    public function setAdmin($admin) {
        $this->admin = $admin;
    }

    public function setConfirmado($confirmado) {
        $this->confirmado = $confirmado;
    }

    public function setToken($token) {
        $this->token = $token;
    }

    public function validar() {
        self::$alertas = [];
        if (!$this->nombre || strlen($this->nombre) < 3) {
            self::$alertas['error'][] = 'El nombre es obligatorio y debe ser válido';
        }
        if (!$this->apellido || strlen($this->apellido) < 3) {
            self::$alertas['error'][] = 'El apellido es obligatorio y debe ser válido';
        }
        if (!$this->telefono) {
            self::$alertas['error'][] = "El teléfono es obligatorio";
        } else if (!preg_match('/^09\d{8}$/', $this->telefono)) {
            self::$alertas['error'][] = "Formato Inválido de Teléfono";
        }

        if (!$this->email) {
            self::$alertas['error'][] = 'El email es obligatorio';
        } else if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)){
            self::$alertas['error'][] = 'Email con formato inválido';
        }

        if (!$this->password) {
            self::$alertas['error'][] = 'La contraseña es obligatoria';
        } else if (strlen($this->password) < 6) {
            self::$alertas['error'][] = 'La contraseña debe tener al menos 6 caracteres';
        }
        return self::$alertas;
    }

    public function verificarExistencia() {
        $email = self::$db->escape_string($this->email);
        $query = "SELECT * FROM " . self::$tabla . " WHERE email = '{$email}' LIMIT 1";
        $resultado = self::$db->query($query);
        return $resultado->num_rows > 0;
    }

    public function validarUnicidad() {
        if ($this->verificarExistencia()) {
            self::$alertas['error'][] = 'El usuario ya está registrado';
            return false;
        }
        return true;
    }

    public function hashPassword() {
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
    }

    public function crearToken() {
        $this->token = uniqid();
    }

    public function validarLogin() {
        self::$alertas = [];
        if (!$this->email) {
            self::$alertas['error'][] = 'El email es obligatorio';
        } else if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)){
            self::$alertas['error'][] = 'Email con formato inválido';
        }

        if (!$this->password) {
            self::$alertas['error'][] = 'La contraseña es obligatoria';
        }
        return self::$alertas;
    }

    public function comprobarCredenciales($password) {
        $resultado = password_verify($password, $this->getPassword());
        if (!$resultado || !$this->getConfirmado()) {
            self::$alertas['error'][] = 'Password Incorrecto o tu cuenta aún no ha sido confirmada';
            return false;
        }
        return true;
    }

    public function autenticar() {
        session_start();
        $_SESSION['id'] = $this->id;
        $_SESSION['nombre'] = $this->nombre . ' ' . $this->apellido;
        $_SESSION['email'] = $this->email;
        $_SESSION['login'] = true;
        
        if ($this->admin === "1") {
            $_SESSION['admin'] = true;
            redirect('/admin');
        }
        redirect('/cita');
    }
}