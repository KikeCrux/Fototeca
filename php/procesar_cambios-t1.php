<?php
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['username'])) {
    // Si no está autenticado, redirigirlo a la página de inicio de sesión
    header("Location: login.php");
    exit();
}

// Realizar la conexión a la base de datos
$servername = "localhost";
$db_username = "root";
$db_password = "Sandia2016.!";
$dbname = "fototeca_ob_uaa";

$conn = new mysqli($servername, $db_username, $db_password, $dbname);
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Verificar si se recibió el ID del resguardante a cambiar
if (!isset($_GET['id'])) {
    // Si no se recibió el ID, redirigir a la página de cambios
    header("Location: cambio-t1.php");
    exit();
}

// Obtener el ID del resguardante
$id_resguardante = $_GET['id'];

// Consultar los datos del resguardante a cambiar
$sql = "SELECT * FROM Resguardante WHERE ID_Resguardante = $id_resguardante";
$result = $conn->query($sql);

// Verificar si se encontró el resguardante
if ($result->num_rows > 0) {
    // Obtener los datos del resguardante
    $row = $result->fetch_assoc();
    $nombre = $row["Nombre"];
    $puesto = $row["PuestoDepartamento"];
    $observaciones = $row["Observaciones"];
} else {
    // Si no se encontró el resguardante, redirigir a la página de cambios
    header("Location: cambio-t1.php");
    exit();
}

// Si se envió el formulario de cambios
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los nuevos datos del formulario
    $nuevo_nombre = $_POST['nombre'];
    $nuevo_puesto = $_POST['puesto'];
    $nuevas_observaciones = $_POST['observaciones'];

    // Actualizar el registro en la base de datos
    $sql = "UPDATE Resguardante SET Nombre='$nuevo_nombre', PuestoDepartamento='$nuevo_puesto', Observaciones='$nuevas_observaciones' WHERE ID_Resguardante=$id_resguardante";

    if ($conn->query($sql) === TRUE) {
        // Redirigir a la página de cambios con un mensaje de éxito
        header("Location: cambio-t1.php?success_message=Cambios realizados exitosamente.");
        exit();
    } else {
        // Mostrar un mensaje de error
        $error_message = "Error al realizar cambios: " . $conn->error;
    }
}

// Cerrar la conexión a la base de datos
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Procesar Cambios</title>
    <link rel="stylesheet" href="../css/login.css">
</head>

<body>
    <?php
    $pageTitle = "Procesar Cambios";
    include 'header.php';
    echo '<br>';
    ?>

    <div class="container mt-5">
        <h1 class="text-center">Procesar Cambios de Resguardante</h1>
        <br>
        <?php if (!empty($error_message)) : ?>
            <div class="alert alert-danger text-center"><?php echo $error_message; ?></div>
        <?php endif; ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?id=' . $id_resguardante; ?>" method="post">
            <div class="form-group">
                <label for="nombre">Nuevo nombre:</label>
                <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo $nombre; ?>">
            </div>
            <br>
            <div class="form-group">
                <label for="puesto">Nuevo puesto / departamento:</label>
                <input type="text" class="form-control" id="puesto" name="puesto" value="<?php echo $puesto; ?>">
            </div>
            <br>
            <div class="form-group">
                <label for="observaciones">Nuevas observaciones:</label>
                <textarea class="form-control" id="observaciones" name="observaciones" rows="3"><?php echo $observaciones; ?></textarea>
            </div>
            <br>
            <button type="submit" class="btn btn-primary">Guardar cambios</button>
        </form>
    </div>
</body>

</html>