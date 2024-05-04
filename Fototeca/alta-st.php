<?php
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['username'])) {
    // Si no está autenticado, redirigirlo a la página de inicio de sesión
    header("Location: login.php");
    exit();
}

// Si el usuario está autenticado, mostrar el nombre de usuario
$username = $_SESSION['username'];

// Definir las variables para los mensajes de éxito y error
$success_message = '';
$error_message = '';

// Procesar el formulario si se envió
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Conectar a la base de datos
    $servername = "localhost";
    $username = "root";
    $password = "Sandia2016.!";
    $dbname = "Fototeca_UAA";

    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }

    // Obtener los datos del formulario
    $nombre = $_POST["nombre"];
    $puesto = $_POST["puesto"];
    $observaciones = $_POST["observaciones"];

    // Preparar la consulta SQL
    $sql = "INSERT INTO Resguardante (Nombre, PuestoDepartamento, Observaciones) 
            VALUES ('$nombre', '$puesto', '$observaciones')";

    // Ejecutar la consulta SQL
    if ($conn->query($sql) === TRUE) {
        // Mensaje de éxito
        $success_message = "Resguardante registrado exitosamente.";
    } else {
        // Capturar el mensaje de error
        $error_message = "Error: " . $sql . "<br>" . $conn->error;
    }

    // Cerrar la conexión a la base de datos
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../resources/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/styles.css">
    <title>Formulario de Registro</title>
</head>

<body>

    <h2 class="titulo">Formulario de Registro</h2>

    <div class="container">
        <form action="/submit_formulario" method="post">
            <div class="row">
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="id">ID:</label>
                        <input type="text" class="form-control" id="id" name="id">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="numero_inventario">Número de Inventario:</label>
                        <input type="text" class="form-control" id="numero_inventario" name="numero_inventario">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="clave_tecnica">Clave Técnica:</label>
                        <input type="text" class="form-control" id="clave_tecnica" name="clave_tecnica">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="proceso_fotografico">Proceso Fotográfico:</label>
                        <input type="text" class="form-control" id="proceso_fotografico" name="proceso_fotografico">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="fondo_coleccion">Fondo de Colección:</label>
                        <input type="text" class="form-control" id="fondo_coleccion" name="fondo_coleccion">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="formato">Formato:</label>
                        <input type="text" class="form-control" id="formato" name="formato">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="numero_negativo_copia">Número del Negativo/Copia:</label>
                        <input type="text" class="form-control" id="numero_negativo_copia" name="numero_negativo_copia">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="tipo">Tipo:</label>
                        <input type="text" class="form-control" id="tipo" name="tipo">
                    </div>
                </div>
                <!-- Agrega más campos aquí si es necesario -->
            </div>
            <button type="submit" class="btn btn-primary">Enviar</button>
        </form>
    </div>

</body>

</html>