<?php

function debug($variable) : string {
    echo "<pre>";
    var_dump($variable);
    echo "</pre>";
    exit;
}

// Escapa / Sanitizar el HTML
function s($html) : string {
    $s = htmlspecialchars($html);
    return $s;
}

function verificarVariable($variable, $location) {
    if (!$variable) {
        redirect($location);
    }
}

function redirect($location) {
    header('Location: ' . $location);
    exit;
}

function isAuth() {
    if (!isset($_SESSION['login'])) {
        header('Location: /');
        exit;
    }
}

function isAdmin() {
    if (!isset($_SESSION['admin'])) {
        header('Location: /');
        exit;
    }
}

function esUltimo(string $actual, string $next) {
    return $actual !== $next;
}