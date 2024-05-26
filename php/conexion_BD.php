<?php

$servername = "localhost";
$username = "root";
$password = "Sandia2016.!";
$dbname = "fototeca_ob_uaa";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
