<?php

require_once('constants.php');

function conexion(){
    $servidor="IPServerSQL";
    $usuario="pquot";
    $password="pquotwebdb";
    $bd="pquot";
    $puerto=3306;

    $conexion = new mysqli($servidor, $usuario, $password, $bd, $puerto);

    if ($conexion->connect_error) {
        die("Connection failed: " . $conexion->connect_error);
    }

    $conexion->set_charset("utf8");

    return $conexion;
}
?>
