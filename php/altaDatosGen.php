<?php

/*
 * Página para registrar datos generales.
 * Verifica la sesión del usuario antes de permitir el acceso.
 */

session_start();

// Redirige a la página de inicio de sesión si no hay sesión activa
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

$username = $_SESSION['username'];

/*
 * Incluye el encabezado y define variables para mensajes de éxito y error.
 * También inicializa variables para manejar imágenes de oficio/vale y de obra.
 */
require_once 'conexion_BD.php';

$success_message = '';
$error_message = '';
$imagenContenido = '';
$imagenObra = '';

/*
 * Consulta SQL para obtener datos de personal vigente para selectores.
 */
$personalQuery = "SELECT ID_Personal, Clave, Nombre FROM Personal WHERE Estatus = 'Vigente'";
$personalResult = $conn->query($personalQuery);

/*
 * Procesamiento del formulario enviado por POST.
 * Valida y guarda los datos ingresados, incluyendo manejo de archivos PDF.
 */
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recoge datos del formulario
    $autores = $_POST["autores"];
    $objeto = $_POST["objeto"];
    $tipoObra = $_POST["tipoObra"];
    $ubicacion = $_POST["ubicacion"];
    $inventario = $_POST["inventario"];
    $vale = $_POST["vale"];
    $fechprestamo = $_POST["fechprestamo"];
    $caracteristicas = $_POST["caracteristicas"];
    $observaciones = $_POST["observaciones"];
    $idResguardante = $_POST["idResguardante"];
    $idAsignado = $_POST["idAsignado"];

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

    // Inserta los datos en la base de datos si no hay errores
    if (!$error_message) {
        $sql = "INSERT INTO DatosGenerales (Autores, ObjetoObra, TipoObra, Ubicacion, NoInventario, NoVale,
                FechaPrestamo, Caracteristicas, Observaciones, ImagenOficioVale, ImagenObra, ID_Resguardante, ID_Asignado) 
                VALUES ('$autores', '$objeto', '$tipoObra', '$ubicacion', '$inventario', '$vale', '$fechprestamo', '$caracteristicas', '$observaciones', '$imagenContenido', '$imagenObra', $idResguardante, $idAsignado)";

        if ($conn->query($sql) === TRUE) {
            $success_message = "Registro exitoso.";
        } else {
            $error_message = "Error al registrar: " . $conn->error;
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
    <title>Alta de Datos Generales</title>
    <link rel="stylesheet" href="../css/login.css">
    <style>
        .form-group {
            margin-bottom: 20px;
        }

        .container-btn {
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <!-- Mensajes de alerta exitosa o error en las altas -->
    <?php include 'header.php'; ?>
    <?php if (!empty($success_message)) : ?>
        <div id="successAlert" class="alert alert-success text-center"><?php echo $success_message; ?></div>
    <?php endif; ?>
    <?php if (!empty($error_message)) : ?>
        <div id="errorAlert" class="alert alert-danger text-center"><?php echo nl2br(htmlspecialchars($error_message)); ?></div>
    <?php endif; ?>

    <br>
    <h1 class="text-center">Registro de Datos Generales</h1>

    <!-- Formulario de altas Datos Generales -->
    <div class="container form mt-5">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="autores">Autor(es)</label>
                <input type="text" class="form-control" id="autores" name="autores" required>
            </div>
            <div class="form-group">
                <label for="objeto">Objeto / Obra</label>
                <input type="text" class="form-control" id="objeto" name="objeto">
            </div>
            <div class="form-group">
                <label for="tipoObra">Tipo de Obra</label>
                <input type="text" class="form-control" id="tipoObra" name="tipoObra">
            </div>
            <div class="form-group">
                <label for="ubicacion">Ubicación</label>
                <input type="text" class="form-control" id="ubicacion" name="ubicacion" required>
            </div>
            <div class="form-group">
                <label for="inventario">Inventario</label>
                <input type="text" class="form-control" id="inventario" name="inventario" required>
            </div>
            <div class="form-group">
                <label for="vale">No. Vale</label>
                <input type="text" class="form-control" id="vale" name="vale" required>
            </div>
            <div class="form-group">
                <label for="fechprestamo">Fecha Préstamo</label>
                <input type="date" class="form-control" id="fechprestamo" name="fechprestamo" required>
            </div>
            <div class="form-group">
                <label for="caracteristicas">Características</label>
                <textarea class="form-control" id="caracteristicas" name="caracteristicas" rows="3"></textarea>
            </div>
            <div class="form-group">
                <label for="observaciones">Observaciones</label>
                <textarea class="form-control" id="observaciones" name="observaciones" rows="3"></textarea>
            </div>

            <div class="form-group">
                <label for="idResguardante">ID Resguardante</label>
                <select class="form-control" id="idResguardante" name="idResguardante">
                    <?php while ($personal = $personalResult->fetch_assoc()) : ?>
                        <option value="<?php echo $personal['ID_Personal']; ?>"><?php echo $personal['Clave'] . " - " . $personal['Nombre']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="idAsignado">ID Asignado</label>
                <select class="form-control" id="idAsignado" name="idAsignado">
                    <?php
                    $personalResult->data_seek(0);
                    while ($personal = $personalResult->fetch_assoc()) : ?>
                        <option value="<?php echo $personal['ID_Personal']; ?>"><?php echo $personal['Clave'] . " - " . $personal['Nombre']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="imagen">Imagen de oficio/vale (PDF)</label>
                <input type="file" class="form-control" id="imagen" name="imagen">
            </div>
            <div class="form-group">
                <label for="imagenObra">Imagen de la obra (PDF)</label>
                <input type="file" class="form-control" id="imagenObra" name="imagenObra">
            </div>
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