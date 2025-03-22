<?php

function connectDatabase() : mysqli {
    $db = new mysqli('localhost', 'root', 'root', 'appsalon');
    if ($db->connect_error) {
        die('Connection failed: ' . $db->connect_error);
    }
    return $db;
}
