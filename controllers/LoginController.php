<?php
namespace Controllers;
use MVC\Router;
use Model\User;

class LoginController {
    public static function login(Router $router) {
        $router->render('auth/login');
    }

    public static function logout() {
        echo 'From Logout';
    }

    public static function forgot(Router $router) {
        $router->render('auth/olvide-password');
    }

    public static function recover() {
        echo 'From recuperar';
    }

    public static function createForm(Router $router) {
        $user = new User();
        $router->render('auth/crear-cuenta', [
            'user' => $user,
            'alerts' => []
        ]);
    }

    public static function create(Router $router) {
        $user = new User($_POST);
        $alerts = $user->validate();
        if (empty($alerts)) {
            if ($user->validateUnicity()) {
                $user->hashPassword();
                debug($user);
            }
            $alerts = User::getAlerts();
        }
        $router->render('auth/crear-cuenta', [
            'user' => $user,
            'alerts' => $alerts
        ]);
    }

    
}