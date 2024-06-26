<?php
// Definición de variables para la conexión a la base de datos

$servername = "localhost";  // Dirección del servidor de la base de datos
$username = "root";         // Nombre de usuario para acceder al servidor de la base de datos
$password = "Sandia2016.!"; // Contraseña del usuario para acceder al servidor de la base de datos
$dbname = "fototeca_ob_uaa"; // Nombre de la base de datos a conectar
$port = 3307; // Cambie esto al puerto que esté usando MySQL

// Crear conexión a la base de datos usando la extensión MySQLi
$conn = new mysqli($servername, $username, $password, $dbname, $port);

// Verificar la conexión
if ($conn->connect_error) {
    // Si hay un error en la conexión, terminar el script y mostrar un mensaje de error
    die("Error de conexión: " . $conn->connect_error);
}
