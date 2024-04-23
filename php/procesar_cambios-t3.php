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
    header("Location: cambio-t3.php");
    exit();
}

// Obtener el ID del resguardante
$id_DatosGenerales = $_GET['id'];

// Consultar los datos del resguardante a cambiar
$sql = "SELECT * FROM DatosGenerales WHERE ID_DatosGenerales = $id_DatosGenerales";
$result = $conn->query($sql);

// Verificar si se encontró el resguardante
if ($result->num_rows > 0) {
    // Obtener los datos del resguardante
    $row = $result->fetch_assoc();
    $autores = $row["Autores"];
    $objeto = $row["ObjetoObra"];
    $ubicacion = $row["Ubicacion"];
    $inventario = $row["NoInventario"];
    $vale = $row["NoVale"];
    $fechprestamo = $row["FechaPrestamo"];
    $caracteristicas = $row["Caracteristicas"];
    $observaciones = $row["Observaciones"];
    $imagen = $row["ImagenOficioVale"];
} else {
    // Si no se encontró el resguardante, redirigir a la página de cambios
    header("Location: cambio-t3.php");
    exit();
}

// Si se envió el formulario de cambios
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los nuevos datos del formulario
    $nuevos_autores = $_POST['autores'];
    $nuevo_objeto = $_POST['objeto'];
    $nueva_ubicacion = $_POST['ubicacion'];
    $nuevo_inventario = $_POST['inventario'];
    $nuevo_vale = $_POST['vale'];
    $nueva_fechprestamo = $_POST['fechprestamo'];
    $nuevas_caracteristicas = $_POST['caracteristicas'];
    $nuevas_observaciones = $_POST['observaciones'];
    $nueva_imagen = $_POST['imagen'];

    // Actualizar el registro en la base de datos
    $sql = "UPDATE DatosGenerales SET Autores='$nuevos_autores', ObjetoObra='$nuevo_objeto', Ubicacion='$nueva_ubicacion', NoInventario='$nuevo_inventario', NoVale='$nuevo_vale',
                                      FechaPrestamo='$nueva_fechprestamo', Caracteristicas='$nuevas_caracteristicas', Observaciones='$nuevas_observaciones', ImagenOficioVale='$nueva_imagen' WHERE ID_DatosGenerales=$id_DatosGenerales";

    if ($conn->query($sql) === TRUE) {
        // Redirigir a la página de cambios con un mensaje de éxito
        header("Location: cambio-t3.php?success_message=Cambios realizados exitosamente.");
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
        <h1 class="text-center">Procesar Cambios de Datos Generales</h1>
        <br>
        <?php if (!empty($error_message)) : ?>
            <div class="alert alert-danger text-center"><?php echo $error_message; ?></div>
        <?php endif; ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?id=' . $id_resguardante; ?>" method="post">
            <div class="form-group">
                <label for="nombre">Nuevos Autor(ES):</label>
                <input type="text" class="form-control" id="autores" name="autores" value="<?php echo $autores; ?>">
            </div>
            <div class="form-group">
                <label for="puesto">Nuevo objeto / obra:</label>
                <input type="text" class="form-control" id="objeto" name="objeto" value="<?php echo $objeto; ?>">
            </div>
            <div class="form-group">
                <label for="observaciones">Nueva ubicacion:</label>
                <input type="text" class="form-control" id="ubicacion" name="ubicacion" value="<?php echo $ubicacion; ?>">
            </div>
            <div class="form-group">
                <label for="puesto">Nuevo inventario</label>
                <input type="text" class="form-control" id="inventario" name="inventario" value="<?php echo $objeto; ?>">
            </div>
            <div class="form-group">
                <label for="puesto">Nuevo No. Vale :</label>
                <input type="text" class="form-control" id="vale" name="vale" value="<?php echo $vale; ?>">
            </div>
            <div class="form-group">
                <label for="puesto">Nueva Fecha (Prestamo):</label>
                <input type="text" class="form-control" id="fechprestamo" name="fechprestamo" value="<?php echo $fechprestamo; ?>">
            </div>
            <div class="form-group">
                <label for="puesto">Nuevas caracteristicas:</label>
                <textarea class="form-control" id="caracteristicas" name="caracteristicas" rows="3"><?php echo $caracteristicas; ?></textarea>
            </div>
            <div class="form-group">
                <label for="puesto">Nuevas observaciones:</label>
                <textarea class="form-control" id="observaciones" name="observaciones" rows="3"><?php echo $observaciones; ?></textarea>
            </div>
            <div class="form-group">
                <label for="puesto">Nueva imagen:</label>
                <input type="text" class="form-control" id="imagen" name="imagen" value="<?php echo $imagen; ?>">
            </div>

            <button type="submit" class="btn btn-primary">Guardar cambios</button>
        </form>
    </div>
</body>

</html>