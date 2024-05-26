<?php
// Inicia la sesión y verifica la autenticación del usuario
session_start();
if (!isset($_SESSION['username'])) {
    // Redirige a la página de inicio de sesión si el usuario no está autenticado
    header("Location: login.php");
    exit();
}

// Usuario autenticado, almacenado en la variable para uso futuro
$username = $_SESSION['username'];

// Variables para almacenar mensajes de éxito y error que se mostrarán al usuario
$success_message = '';
$error_message = '';

// Procesamiento del formulario cuando se envía mediante método POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Incluye el archivo que establece la conexión con la base de datos
    require_once 'conexion_BD.php';

    // Recuperación de datos del formulario enviado
    $nombre = $_POST["nombre"];
    $puesto = $_POST["puesto"];
    $observaciones = $_POST["observaciones"];

    // Construcción y ejecución de la consulta SQL para insertar un nuevo registro
    $sql = "INSERT INTO Asignado (Nombre, PuestoDepartamento, Observaciones) 
            VALUES ('$nombre', '$puesto', '$observaciones')";

    // Ejecución de la consulta y manejo de resultados
    if ($conn->query($sql) === TRUE) {
        // Si la inserción es exitosa, muestra un mensaje de éxito
        $success_message = "Asignado registrado exitosamente.";
    } else {
        // En caso de error en la consulta, captura y muestra el mensaje de error
        $error_message = "Error: " . $sql . "<br>" . $conn->error;
    }

    // Cierra la conexión a la base de datos para liberar recursos
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alta de Asignado</title>
    <link rel="stylesheet" href="../css/login.css">
</head>

<body>
    <?php
    // Incluye el encabezado de la página
    include 'header.php';
    echo '<br>';
    echo '<h1 class="text-center">Registro de Asignado</h1>';
    ?>

    <!-- Sección para mostrar mensajes de éxito o error tras el envío del formulario -->
    <?php if (!empty($success_message)) : ?>
        <div id="successAlert" class="alert alert-success text-center"><?php echo $success_message; ?></div>
    <?php endif; ?>
    <?php if (!empty($error_message)) : ?>
        <div id="errorAlert" class="alert alert-danger text-center"><?php echo $error_message; ?></div>
    <?php endif; ?>

    <!-- Formulario para el registro de nuevos asignados -->
    <div class="container form mt-5">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label for="nombre">Nombre</label>
                <input type="text" class="form-control" id="nombre" name="nombre" required>
            </div>
            <br>
            <div class="form-group">
                <label for="puesto">Puesto / Departamento</label>
                <input type="text" class="form-control" id="puesto" name="puesto">
            </div>
            <br>
            <div class="form-group">
                <label for="observaciones">Observaciones</label>
                <textarea class="form-control" id="observaciones" name="observaciones" rows="3"></textarea>
            </div>
            <br>
            <div class="container-btn">
                <button type="submit" class="btnEnviar">Guardar</button>
            </div>
        </form>
    </div>

    <br>

    <!-- Botón para regresar a la página anterior -->
    <div class="container-back">
        <button onclick="goBack()" class="btn btn-secondary mt-3">Regresar</button>
    </div>

    <br>

    <script>
        // Funciones de JavaScript para mejorar la interacción del usuario
        function goBack() {
            window.history.back();
        }
        // Oculta los mensajes de alerta después de 5 segundos
        setTimeout(function() {
            var successAlert = document.getElementById("successAlert");
            var errorAlert = document.getElementById("errorAlert");
            if (errorAlert) {
                errorAlert.style.display = "none";
            } else if (successAlert) {
                successAlert.style.display = "none";
            }
        }, 5000);
    </script>
</body>

</html>