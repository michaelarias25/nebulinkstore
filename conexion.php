<?php
$host = "localhost";
$usuario = "root";   // XAMPP por defecto
$clave = "";         // XAMPP por defecto
$base_datos = "nebulink_store";

$conexion = new mysqli($host, $usuario, $clave, $base_datos);

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}
?>
