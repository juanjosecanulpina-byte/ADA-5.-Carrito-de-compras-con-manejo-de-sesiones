<?php
$servidor = "localhost";
$usuario = "root";
$password = ""; // En XAMPP por defecto va vacío
$base_datos = "tienda_ada5_db";

$conn = new mysqli($servidor, $usuario, $password, $base_datos);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
?>