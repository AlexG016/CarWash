<?php

$usuario = "root";
$contraseña = "";
$localhost = "localhost";
$database = "sistemalavadora";
$enlace = mysqli_connect($localhost, $usuario, $contraseña, $database);

if (!$enlace) {
    echo "Error: No se pudo conectar a MySQL." . PHP_EOL;
    echo "errno de depuración: " . mysqli_connect_errno() . PHP_EOL;
    echo "error de depuración: " . mysqli_connect_error() . PHP_EOL;
    exit;
}
