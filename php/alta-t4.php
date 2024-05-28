<?php
// Inicia sesión y verifica si el usuario está autenticado
session_start();
if (!isset($_SESSION['username'])) {
    // Redirige a la página de inicio de sesión si no hay usuario autenticado
    header("Location: login.php");
    exit();
}

// Variables para almacenar mensajes para el usuario
$username = $_SESSION['username']; // Usuario autenticado

$success_message = ''; // Mensaje de éxito de operación
$error_message = ''; // Mensaje de error de operación

// Procesa la información del formulario cuando se envía
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Incluye el script de conexión a la base de datos
    require_once 'conexion_BD.php';

    // Recupera datos del formulario
    $usuario = $_POST["usuario"];
    $contraseña = $_POST["contraseña"];
    $tipoUsuario = $_POST["tipoUsuario"];

    // Prepara y ejecuta la consulta SQL para registrar un nuevo usuario
    $sql = "INSERT INTO Usuarios (Usuario, Contraseña, TipoUsuario) 
            VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("sss", $usuario, $contraseña, $tipoUsuario);
        if ($stmt->execute()) {
            $success_message = "Usuario registrado exitosamente.";
        } else {
            $error_message = "Error al registrar el usuario: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $error_message = "Error al preparar la consulta: " . $conn->error;
    }

    // Cierra la conexión a la base de datos
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alta de Usuario</title>
    <link rel="stylesheet" href="../css/login.css">
</head>

<body>
    <?php
    // Incluye la cabecera de la página web
    include 'header.php';
    echo '<br>';
    echo '<h1 class="text-center">Registro de Usuarios</h1>';
    ?>

    <!-- Sección para mostrar mensajes de éxito o error -->
    <?php if (!empty($success_message)) : ?>
        <div id="successAlert" class="alert alert-success text-center"><?php echo $success_message; ?></div>
    <?php endif; ?>
    <?php if (!empty($error_message)) : ?>
        <div id="errorAlert" class="alert alert-danger text-center"><?php echo $error_message; ?></div>
    <?php endif; ?>

    <!-- Formulario para la creación de un nuevo usuario -->
    <div class="container form mt-5">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label for="usuario">Usuario</label>
                <input type="text" class="form-control" id="usuario" name="usuario" required>
            </div>
            <br>
            <div class="form-group">
                <label for="contraseña">Contraseña</label>
                <input type="password" class="form-control" id="contraseña" name="contraseña" required>
            </div>
            <br>
            <div class="form-group">
                <label for="tipoUsuario">Tipo de Usuario</label>
                <select class="form-control" id="tipoUsuario" name="tipoUsuario">
                    <option value="Admin">Admin</option>
                    <option value="Arte">Arte</option>
                </select>
            </div>
            <br>
            <div class="container-btn">
                <button type="submit" class="btnEnviar">Guardar</button>
            </div>
        </form>
    </div>

    <!-- Botón para regresar a la página anterior -->
    <div class="container-back">
        <button onclick="goBack()" class="btn btn-secondary mt-3">Regresar</button>
    </div>

    <script>
        // Función para regresar a la página anterior
        function goBack() {
            window.history.back();
        }

        // Función para ocultar las alertas después de un cierto tiempo
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