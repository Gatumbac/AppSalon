<?php
namespace MVC;

class Router {
    public $getRoutes = [];
    public $postRoutes = [];

    public function get($url, $fn) {
        $this->getRoutes[$url] = $fn;
    }

    public function post($url, $fn) {
        $this->postRoutes[$url] = $fn;
    }

    public function checkRoute() {
        $actualUrl = $_SERVER['PATH_INFO'] ?? '/';
        $method = $_SERVER['REQUEST_METHOD'];
        if (in_array($actualUrl, [])) {
            self::verifyAuth();
        }

        $fn = $this->getRoutes[$actualUrl] ?? null;
        if ($method === 'POST') {
            $fn = $this->postRoutes[$actualUrl] ?? null;
        }

        $this->executeFunction($fn);
    }


    public static function verifyAuth() {
        session_start();
        $auth = $_SESSION['login'] ?? false;

        if (!$auth) {
            header('Location: /');
            exit;
        }
    }

    public function executeFunction($fn) {
        if ($fn) {
            call_user_func($fn, $this);
        } else {
            header("HTTP/1.0 404 Not Found");
            $this->render('pages/default');
        }
    }

    public function render($view, $data = []) {
        foreach($data as $key=>$value) {
            $$key = $value;
        }
        
        ob_start();
        include_once __DIR__ . '/views/' . $view . '.php';
        $contenido = ob_get_clean();
        include __DIR__ . '/views/layout.php';
    }
}