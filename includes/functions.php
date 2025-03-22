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

function verifyVariable($variable, $location) {
    if (!$variable) {
        redirect($location);
    }
}

function redirect($location) {
    header('Location: ' . $location);
    exit;
}