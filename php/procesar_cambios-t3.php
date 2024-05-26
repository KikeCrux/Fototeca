<?php
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['username'])) {
    // Si no está autenticado, redirigirlo a la página de inicio de sesión
    header("Location: login.php");
    exit();
}

require_once 'conexion_BD.php';

// Verificar si se recibió el ID del registro a cambiar
if (!isset($_GET['id'])) {
    // Si no se recibió el ID, redirigir a la página de cambios
    header("Location: cambio-t3.php");
    exit();
}

$id_DatosGenerales = $_GET['id'];

// Consultar los datos del registro a cambiar
$sql = "SELECT * FROM DatosGenerales WHERE ID_DatosGenerales = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_DatosGenerales);
$stmt->execute();
$result = $stmt->get_result();

// Verificar si se encontró el registro
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
} else {
    // Si no se encontró el registro, redirigir a la página de cambios
    header("Location: cambio-t3.php");
    exit();
}

// Si se envió el formulario de cambios
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nuevos_autores = $_POST['autores'];
    $nuevo_objeto = $_POST['objeto'];
    $nueva_ubicacion = $_POST['ubicacion'];
    $nuevo_inventario = $_POST['inventario'];
    $nuevo_vale = $_POST['vale'];
    $nueva_fechprestamo = $_POST['fechprestamo'];
    $nuevas_caracteristicas = $_POST['caracteristicas'];
    $nuevas_observaciones = $_POST['observaciones'];
    $nueva_imagen = isset($_FILES['imagen']) ? $_FILES['imagen'] : null;

    // Actualizar el registro en la base de datos
    $sql = "UPDATE DatosGenerales SET Autores=?, ObjetoObra=?, Ubicacion=?, NoInventario=?, NoVale=?, FechaPrestamo=?, Caracteristicas=?, Observaciones=?, ImagenOficioVale=? WHERE ID_DatosGenerales=?";
    $stmt = $conn->prepare($sql);
    $null = NULL; // Para bind_param con tipo blob

    if ($nueva_imagen && $nueva_imagen['error'] === UPLOAD_ERR_OK) {
        $tmp_name = $nueva_imagen['tmp_name'];
        $pdfData = file_get_contents($tmp_name);
        $stmt->bind_param("ssssssssbi", $nuevos_autores, $nuevo_objeto, $nueva_ubicacion, $nuevo_inventario, $nuevo_vale, $nueva_fechprestamo, $nuevas_caracteristicas, $nuevas_observaciones, $null, $id_DatosGenerales);
        $stmt->send_long_data(8, $pdfData);
    } else {
        $stmt->bind_param("sssssssssi", $nuevos_autores, $nuevo_objeto, $nueva_ubicacion, $nuevo_inventario, $nuevo_vale, $nueva_fechprestamo, $nuevas_caracteristicas, $nuevas_observaciones, $row['ImagenOficioVale'], $id_DatosGenerales);
    }

    if ($stmt->execute()) {
        header("Location: cambio-t3.php?success_message=Cambios realizados exitosamente.");
        exit();
    } else {
        $error_message = "Error al realizar cambios: " . $stmt->error;
    }
}

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
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?id=' . $id_DatosGenerales; ?>" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="nombre">Nuevos Autor(ES):</label>
                <input type="text" class="form-control" id="autores" name="autores" value="<?php echo htmlspecialchars($row['Autores']); ?>" required>
            </div>
            <div class="form-group">
                <label for="puesto">Nuevo objeto / obra:</label>
                <input type="text" class="form-control" id="objeto" name="objeto" value="<?php echo htmlspecialchars($row['ObjetoObra']); ?>">
            </div>
            <div class="form-group">
                <label for="observaciones">Nueva ubicacion:</label>
                <input type="text" class="form-control" id="ubicacion" name="ubicacion" value="<?php echo htmlspecialchars($row['Ubicacion']); ?>" required>
            </div>
            <div class="form-group">
                <label for="puesto">Nuevo inventario</label>
                <input type="text" class="form-control" id="inventario" name="inventario" value="<?php echo htmlspecialchars($row['NoInventario']); ?>" required>
            </div>
            <div class="form-group">
                <label for="puesto">Nuevo No. Vale :</label>
                <input type="text" class="form-control" id="vale" name="vale" value="<?php echo htmlspecialchars($row['NoVale']); ?>" required>
            </div>
            <div class="form-group">
                <label for="puesto">Nueva Fecha (Prestamo):</label>
                <input type="date" class="form-control" id="fechprestamo" name="fechprestamo" value="<?php echo htmlspecialchars($row['FechaPrestamo']); ?>" required>
            </div>
            <div class="form-group">
                <label for="puesto">Nuevas caracteristicas:</label>
                <textarea class="form-control" id="caracteristicas" name="caracteristicas" rows="3"><?php echo htmlspecialchars($row['Caracteristicas']); ?></textarea>
            </div>
            <div class="form-group">
                <label for="puesto">Nuevas observaciones:</label>
                <textarea class="form-control" id="observaciones" name="observaciones" rows="3"><?php echo htmlspecialchars($row['Observaciones']); ?></textarea>
            </div>
            <div class="form-group">
                <label for="imagen">Nueva imagen (PDF solo):</label>
                <input type="file" class="form-control" id="imagen" name="imagen">
                <?php if (!empty($row['ImagenOficioVale'])) : ?>
                    <small>Un archivo ya está guardado. Subir uno nuevo reemplazará el existente.</small>
                <?php endif; ?>
            </div>
            <br>
            <button type="submit" class="btn btn-primary">Guardar cambios</button>
        </form>
    </div>
</body>

</html>