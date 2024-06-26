<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

require_once 'conexion_BD.php';

if (!isset($_GET['id'])) {
    header("Location: cambio-t3.php");
    exit();
}

$id_DatosGenerales = $_GET['id'];

$sql = "SELECT * FROM DatosGenerales WHERE ID_DatosGenerales = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_DatosGenerales);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
} else {
    header("Location: cambio-t3.php");
    exit();
}

// Consulta para obtener los datos de personal para los selectores
$personalQuery = "SELECT ID_Personal, Clave, Nombre FROM Personal WHERE Estatus = 'Vigente'";
$personalResult = $conn->query($personalQuery);

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
    $nuevo_tipo_obra = $_POST['tipoObra'];
    $nuevo_resguardante = $_POST['idResguardante'];
    $nuevo_asignado = $_POST['idAsignado'];

    $imagenContenido = '';
    $imagenObra = '';

    // Manejo de la imagen de oficio/vale
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['imagen']['tmp_name'];
        $fileType = $_FILES['imagen']['type'];
        $fileSize = $_FILES['imagen']['size'];

        if ($fileType == 'application/pdf' && $fileSize <= 10000000) { // 10 MB limit
            $imagenContenido = file_get_contents($fileTmpPath);
            $imagenContenido = $conn->real_escape_string($imagenContenido);
        } else {
            $error_message = "Archivo de oficio/vale no válido. Asegúrese de que es un PDF y no supera los 10 MB.";
        }
    }

    // Manejo de la imagen de la obra
    if (isset($_FILES['imagenObra']) && $_FILES['imagenObra']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPathObra = $_FILES['imagenObra']['tmp_name'];
        $fileTypeObra = $_FILES['imagenObra']['type'];
        $fileSizeObra = $_FILES['imagenObra']['size'];

        if ($fileTypeObra == 'application/pdf' && $fileSizeObra <= 10000000) { // 10 MB limit
            $imagenObra = file_get_contents($fileTmpPathObra);
            $imagenObra = $conn->real_escape_string($imagenObra);
        } else {
            $error_message .= "\nArchivo de obra no válido. Asegúrese de que es un PDF y no supera los 10 MB.";
        }
    }

    if (!$error_message) {
        if (!empty($imagenContenido) && !empty($imagenObra)) {
            $sql = "UPDATE DatosGenerales SET Autores='$nuevos_autores', ObjetoObra='$nuevo_objeto', Ubicacion='$nueva_ubicacion', 
                    NoInventario='$nuevo_inventario', NoVale='$nuevo_vale', FechaPrestamo='$nueva_fechprestamo', 
                    Caracteristicas='$nuevas_caracteristicas', Observaciones='$nuevas_observaciones', TipoObra='$nuevo_tipo_obra', 
                    ImagenOficioVale='$imagenContenido', ImagenObra='$imagenObra', ID_Resguardante=$nuevo_resguardante, 
                    ID_Asignado=$nuevo_asignado WHERE ID_DatosGenerales=$id_DatosGenerales";
        } elseif (!empty($imagenContenido)) {
            $sql = "UPDATE DatosGenerales SET Autores='$nuevos_autores', ObjetoObra='$nuevo_objeto', Ubicacion='$nueva_ubicacion', 
                    NoInventario='$nuevo_inventario', NoVale='$nuevo_vale', FechaPrestamo='$nueva_fechprestamo', 
                    Caracteristicas='$nuevas_caracteristicas', Observaciones='$nuevas_observaciones', TipoObra='$nuevo_tipo_obra', 
                    ImagenOficioVale='$imagenContenido', ID_Resguardante=$nuevo_resguardante, ID_Asignado=$nuevo_asignado 
                    WHERE ID_DatosGenerales=$id_DatosGenerales";
        } elseif (!empty($imagenObra)) {
            $sql = "UPDATE DatosGenerales SET Autores='$nuevos_autores', ObjetoObra='$nuevo_objeto', Ubicacion='$nueva_ubicacion', 
                    NoInventario='$nuevo_inventario', NoVale='$nuevo_vale', FechaPrestamo='$nueva_fechprestamo', 
                    Caracteristicas='$nuevas_caracteristicas', Observaciones='$nuevas_observaciones', TipoObra='$nuevo_tipo_obra', 
                    ImagenObra='$imagenObra', ID_Resguardante=$nuevo_resguardante, ID_Asignado=$nuevo_asignado 
                    WHERE ID_DatosGenerales=$id_DatosGenerales";
        } else {
            $sql = "UPDATE DatosGenerales SET Autores='$nuevos_autores', ObjetoObra='$nuevo_objeto', Ubicacion='$nueva_ubicacion', 
                    NoInventario='$nuevo_inventario', NoVale='$nuevo_vale', FechaPrestamo='$nueva_fechprestamo', 
                    Caracteristicas='$nuevas_caracteristicas', Observaciones='$nuevas_observaciones', TipoObra='$nuevo_tipo_obra', 
                    ID_Resguardante=$nuevo_resguardante, ID_Asignado=$nuevo_asignado WHERE ID_DatosGenerales=$id_DatosGenerales";
        }

        if ($conn->query($sql) === TRUE) {
            header("Location: cambio-t3.php?success_message=Cambios realizados exitosamente.");
            exit();
        } else {
            $error_message = "Error al realizar cambios: " . $conn->error;
        }
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Procesar Cambios</title>
    <link rel="stylesheet" href="../css/login.css">
    <style>
        .form-group {
            margin-bottom: 20px;
        }
    </style>
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
                <label for="autores">Nuevos Autor(ES):</label>
                <input type="text" class="form-control" id="autores" name="autores" value="<?php echo htmlspecialchars($row['Autores']); ?>" required>
            </div>
            <div class="form-group">
                <label for="objeto">Nuevo objeto / obra:</label>
                <input type="text" class="form-control" id="objeto" name="objeto" value="<?php echo htmlspecialchars($row['ObjetoObra']); ?>">
            </div>
            <div class="form-group">
                <label for="ubicacion">Nueva ubicacion:</label>
                <input type="text" class="form-control" id="ubicacion" name="ubicacion" value="<?php echo htmlspecialchars($row['Ubicacion']); ?>" required>
            </div>
            <div class="form-group">
                <label for="inventario">Nuevo inventario:</label>
                <input type="text" class="form-control" id="inventario" name="inventario" value="<?php echo htmlspecialchars($row['NoInventario']); ?>" required>
            </div>
            <div class="form-group">
                <label for="vale">Nuevo No. Vale:</label>
                <input type="text" class="form-control" id="vale" name="vale" value="<?php echo htmlspecialchars($row['NoVale']); ?>" required>
            </div>
            <div class="form-group">
                <label for="fechprestamo">Nueva Fecha (Prestamo):</label>
                <input type="date" class="form-control" id="fechprestamo" name="fechprestamo" value="<?php echo htmlspecialchars($row['FechaPrestamo']); ?>" required>
            </div>
            <div class="form-group">
                <label for="caracteristicas">Nuevas caracteristicas:</label>
                <textarea class="form-control" id="caracteristicas" name="caracteristicas" rows="3"><?php echo htmlspecialchars($row['Caracteristicas']); ?></textarea>
            </div>
            <div class="form-group">
                <label for="observaciones">Nuevas observaciones:</label>
                <textarea class="form-control" id="observaciones" name="observaciones" rows="3"><?php echo htmlspecialchars($row['Observaciones']); ?></textarea>
            </div>
            <div class="form-group">
                <label for="tipoObra">Nuevo Tipo Obra:</label>
                <input type="text" class="form-control" id="tipoObra" name="tipoObra" value="<?php echo htmlspecialchars($row['TipoObra']); ?>" required>
            </div>
            <div class="form-group">
                <label for="idResguardante">Nuevo ID Resguardante:</label>
                <select class="form-control" id="idResguardante" name="idResguardante">
                    <?php while ($personal = $personalResult->fetch_assoc()) : ?>
                        <option value="<?php echo $personal['ID_Personal']; ?>" <?php if ($row['ID_Resguardante'] == $personal['ID_Personal']) echo 'selected'; ?>>
                            <?php echo $personal['Clave'] . " - " . $personal['Nombre']; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="idAsignado">Nuevo ID Asignado:</label>
                <select class="form-control" id="idAsignado" name="idAsignado">
                    <?php
                    // Re-query the same personal data for asignado options
                    $personalResult->data_seek(0);
                    while ($personal = $personalResult->fetch_assoc()) : ?>
                        <option value="<?php echo $personal['ID_Personal']; ?>" <?php if ($row['ID_Asignado'] == $personal['ID_Personal']) echo 'selected'; ?>>
                            <?php echo $personal['Clave'] . " - " . $personal['Nombre']; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="imagen">Nueva imagen de oficio/vale (PDF)</label>
                <input type="file" class="form-control" id="imagen" name="imagen">
                <?php if (!empty($row['ImagenOficioVale'])) : ?>
                    <small>Un archivo ya está guardado. Subir uno nuevo reemplazará el existente.</small>
                <?php endif; ?>
            </div>
            <div class="form-group">
                <label for="imagenObra">Nueva imagen de la obra (PDF)</label>
                <input type="file" class="form-control" id="imagenObra" name="imagenObra">
                <?php if (!empty($row['ImagenObra'])) : ?>
                    <small>Un archivo ya está guardado. Subir uno nuevo reemplazará el existente.</small>
                <?php endif; ?>
            </div>
            <br>
            <button type="submit" class="btn btn-primary">Guardar cambios</button>
        </form>
        <br>
    </div>
</body>

</html>