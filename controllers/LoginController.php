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
        echo 'From Logout';
    }

    public static function olvide(Router $router) {
        $router->render('auth/olvide-password');
    }

    public static function recuperar() {
        echo 'From recuperar';
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