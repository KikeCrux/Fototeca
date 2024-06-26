<?php
// Iniciar sesión y manejar el control de acceso
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username']; // Usuario actual autenticado

// Inicialización de mensajes para la interfaz de usuario
$success_message = '';
$error_message = '';

// Proceso del formulario al recibir datos por POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Incluir archivo de conexión centralizado a la BD
    require_once 'conexion_BD.php';

    // Recuperar datos del formulario
    $nombre = $_POST["nombre"];
    $puesto = $_POST["puesto"];
    $observaciones = $_POST["observaciones"];
    $clave = $_POST["clave"];  // Asumiendo que el formulario incluye un campo para 'clave'
    $estatus = $_POST["estatus"];  // Asumiendo que el formulario incluye un menú desplegable para 'estatus'

    // Construcción y ejecución de la consulta SQL para inserción de datos
    $sql = "INSERT INTO Personal (Nombre, PuestoDepartamento, Observaciones, Clave, Estatus) 
            VALUES (?, ?, ?, ?, ?)";

    // Preparar la consulta para evitar inyecciones SQL
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("sssii", $nombre, $puesto, $observaciones, $clave, $estatus);
        if ($stmt->execute()) {
            $success_message = "Personal registrado exitosamente.";
        } else {
            $error_message = "Error al registrar el personal: " . $stmt->error;
        }
        $stmt->close(); // Cerrar el statement
    } else {
        $error_message = "Error al preparar la consulta: " . $conn->error;
    }

    $conn->close(); // Cerrar conexión a la base de datos
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alta de Personal</title>
    <link rel="stylesheet" href="../css/login.css">
</head>

<body>
    <?php
    include 'header.php'; // Incluir cabecera
    ?>
    <!-- Mensajes de alerta para el usuario -->
    <?php if (!empty($success_message)) : ?>
        <div id="successAlert" class="alert alert-success text-center"><?php echo $success_message; ?></div>
    <?php endif; ?>
    <?php if (!empty($error_message)) : ?>
        <div id="errorAlert" class="alert alert-danger text-center"><?php echo $error_message; ?></div>
    <?php endif; ?>

    <br>

    <h1 class="text-center">Registro de Personal</h1>

    <!-- Formulario de registro de personal -->
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
            <div class="form-group">
                <label for="clave">Clave</label>
                <input type="number" class="form-control" id="clave" name="clave">
            </div>
            <br>
            <div class="form-group">
                <label for="estatus">Estatus</label>
                <select class="form-control" id="estatus" name="estatus">
                    <option value="1">Vigente</option>
                    <option value="2">No Vigente</option>
                    <option value="3">Jubilado</option>
                </select>
            </div>
            <br>
            <div class="container-btn">
                <button type="submit" class="btnEnviar">Guardar</button>
            </div>
        </form>
    </div>

    <br>

    <script>
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