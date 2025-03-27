<?php
namespace Controllers;
use MVC\Router;
use Model\Usuario;
use Classes\Email;

class LoginController {
    public static function formularioLogin(Router $router) {
        $user = new Usuario();
        $router->render('auth/login', [
            'user' => $user,
            'alertas' => []
        ]);
    }

    public static function login(Router $router) {
        $userForm = new Usuario($_POST);
        $alertas = $userForm->validarLogin();
        $auth = false;
        
        if (empty($alertas)) {
            $usuarioBD = Usuario::where('email', $userForm->getEmail());
            if (!empty($usuarioBD)) {
                $auth = $usuarioBD->comprobarCredenciales($userForm->getPassword());
            } else {
                Usuario::setAlerta('error', 'Usuario no encontrado');
            }

            if($auth) {
                $usuarioBD->autenticar();
            }
            $alertas = Usuario::getAlertas();
        }

        $router->render('auth/login', [
            'user' => $userForm,
            'alertas' => $alertas
        ]);
    }

    public static function logout() {
        session_start();
        $_SESSION = [];
        redirect('/');
    }

    public static function formularioOlvide(Router $router) {
        $user = new Usuario();
        $router->render('auth/olvide-password', [
            'alertas' => [],
            'user' => $user
        ]);
    }

    public static function olvide(Router $router) {
        $userForm = new Usuario($_POST);
        $alertas = $userForm->validarEmail();

        if (empty($alertas)) {
            $usuarioBD = Usuario::where('email', $userForm->getEmail());

            if(!empty($usuarioBD) && $usuarioBD->getConfirmado() === "1") {
                $usuarioBD->crearToken();
                $usuarioBD->guardar();

                $email = new Email($usuarioBD->getEmail(), $usuarioBD->getNombre(), $usuarioBD->getToken());
                $email->enviarInstruccionesPassword();

                Usuario::setAlerta('exito', 'Revisa tu bandeja de entrada');
            } else {
                Usuario::setAlerta('error', 'El usuario no existe o no está confirmado');
            }

            $alertas = Usuario::getAlertas();
        }

        $router->render('auth/olvide-password', [
            'alertas' => $alertas,
            'user' => $userForm
        ]);
    }

    public static function formularioRecuperar(Router $router) {
        $error = false;
        $token = s($_GET['token'] ?? '');
        $user = Usuario::where('token', $token);

        if (empty($user)) {
            Usuario::setAlerta('error', 'Token no válido');
            $error = true;
        }

        $alertas = Usuario::getAlertas();
        $router->render('auth/recuperar-password', [
            'alertas' => $alertas,
            'error' => $error
        ]);
    }

    public static function recuperar(Router $router) {
        $token = s($_GET['token'] ?? '');
        $userBD = Usuario::where('token', $token);
        verificarVariable($userBD, '/');

        $userForm = new Usuario($_POST);
        $alertas = $userForm->validarPassword();
        
        if(empty($alertas)) {
            $userBD->setPassword($userForm->getPassword());
            $userBD->hashPassword();
            $userBD->setToken(null);
            if ($userBD->guardar()) {
                redirect('/');
            }
        }

        $router->render('auth/recuperar-password', [
            'alertas' => $alertas,
            'error' => false
        ]);
    }

    public static function formularioCrear(Router $router) {
        $user = new Usuario();
        $router->render('auth/crear-cuenta', [
            'user' => $user,
            'alertas' => []
        ]);
    }

    public static function crear(Router $router) {
        $user = new Usuario($_POST);
        $alertas = $user->validar();
        if (empty($alertas)) {
            if ($user->validarUnicidad()) {
                $user->hashPassword();
                $user->crearToken();

                $email = new Email($user->getEmail(), $user->getNombre(), $user->getToken());
                $email->enviarConfirmacion();

                if ($user->guardar()) {
                    redirect('/mensaje');
                }
            }
            $alertas = Usuario::getAlertas();
        }

        $router->render('auth/crear-cuenta', [
            'user' => $user,
            'alertas' => $alertas
        ]);
    }

    public static function mensaje(Router $router) {
        $router->render('auth/mensaje');
    }

    public static function confirmar(Router $router) {
        $token = $_GET['token'] ?? '';
        $user = Usuario::where('token', s($token));

        if (empty($user)) {
            Usuario::setAlerta('error', 'Token no válido');
        } else {
            $user->setToken(null);
            $user->setConfirmado('1');
            
            if ($user->guardar()) {
                Usuario::setAlerta('exito', 'Cuenta Confirmada Correctamente');
            }
        }

        $alertas = Usuario::getAlertas();

        $router->render('auth/confirmar-cuenta', [
            'alertas' => $alertas
        ]);
    }
}