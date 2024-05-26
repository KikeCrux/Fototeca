<?php
// Inicia la sesión y verifica si el usuario está autenticado
session_start();
if (!isset($_SESSION['username'])) {
    // Si no hay una sesión de usuario, redirige al login
    header("Location: login.php");
    exit();
}

// Variables para almacenar mensajes de éxito o error
$success_message = '';
$error_message = '';
$imagenContenido = '';

// Procesa la información cuando se envía el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Incluye la conexión a la base de datos
    require_once 'conexion_BD.php';

    // Recoge los datos del formulario
    $autores = $_POST["autores"];
    $objeto = $_POST["objeto"];
    $ubicacion = $_POST["ubicacion"];
    $inventario = $_POST["inventario"];
    $vale = $_POST["vale"];
    $fechprestamo = $_POST["fechprestamo"];
    $caracteristicas = $_POST["caracteristicas"];
    $observaciones = $_POST["observaciones"];

    // Verifica si se ha subido un archivo PDF y procesa el archivo
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['imagen']['tmp_name'];
        $fileType = $_FILES['imagen']['type'];
        $fileSize = $_FILES['imagen']['size'];

        // Asegura que el archivo es un PDF y no excede el tamaño máximo permitido
        if ($fileType == 'application/pdf' && $fileSize < 5000000) {
            $imagenContenido = file_get_contents($fileTmpPath);
            $sql = "INSERT INTO DatosGenerales (Autores, ObjetoObra, Ubicacion, NoInventario, NoVale,
                    FechaPrestamo, Caracteristicas, Observaciones, ImagenOficioVale) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

            $stmt = $conn->prepare($sql);
            if ($stmt === false) {
                $error_message = "Error al preparar la consulta: " . $conn->error;
            } else {
                $null = null;
                $stmt->bind_param("ssssssssb", $autores, $objeto, $ubicacion, $inventario, $vale, $fechprestamo, $caracteristicas, $observaciones, $null);
                $stmt->send_long_data(8, $imagenContenido);

                if ($stmt->execute()) {
                    $success_message = "Registro exitoso.";
                } else {
                    $error_message = "Error al registrar el dato en la base de datos: " . $stmt->error;
                }
                $stmt->close();
            }
        } else {
            $error_message = "Archivo no válido. Asegúrese de que es un PDF y no supera los 5 MB.";
        }
    } else {
        $error_message = "Por favor, seleccione un archivo PDF válido.";
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
    <title>Alta de Datos Generales</title>
    <link rel="stylesheet" href="../css/login.css">
</head>

<body>
    <?php
    // Incluye la cabecera de la página
    include 'header.php';
    echo '<br>';
    echo '<h1 class="text-center">Registro de Datos Generales</h1>';
    ?>

    <!-- Sección para mostrar mensajes de éxito o error -->
    <?php if (!empty($success_message)) : ?>
        <div id="successAlert" class="alert alert-success text-center"><?php echo $success_message; ?></div>
    <?php endif; ?>
    <?php if (!empty($error_message)) : ?>
        <div id="errorAlert" class="alert alert-danger text-center"><?php echo $error_message; ?></div>
    <?php endif; ?>

    <!-- Formulario para el ingreso de datos generales -->
    <div class="container form mt-5">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
            <!-- Campos del formulario para capturar información relevante -->
            <div class="form-group">
                <label for="autores">Autor(ES)</label>
                <input type="text" class="form-control" id="autores" name="autores" required>
            </div>
            <div class="form-group">
                <label for="objeto">Objeto / Obra</label>
                <input type="text" class="form-control" id="objeto" name="objeto">
            </div>
            <div class="form-group">
                <label for="ubicacion">Ubicacion</label>
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
                <label for="fechprestamo">Fecha: (Prestamo)</label>
                <input type="date" class="form-control" id="fechprestamo" name="fechprestamo" required>
            </div>
            <div class="form-group">
                <label for="caracteristicas">Caracteristicas</label>
                <textarea class="form-control" id="caracteristicas" name="caracteristicas" rows="3"></textarea>
            </div>
            <div class="form-group">
                <label for="observaciones">Observaciones</label>
                <textarea class="form-control" id="observaciones" name="observaciones" rows="3"></textarea>
            </div>
            <div class="form-group">
                <label for="imagen">Imagen de oficio/vale</label>
                <input type="file" class="form-control" id="imagen" name="imagen">
            </div>
            <div class="container-btn">
                <button type="submit" class="btnEnviar">Guardar</button>
            </div>
        </form>
    </div>

    <div class="container-back">
        <button onclick="goBack()" class="btn btn-secondary mt-3">Regresar</button>
    </div>

    <script>
        // Funciones de JavaScript para mejorar la experiencia del usuario
        function goBack() {
            window.history.back();
        }
        // Oculta las alertas después de un tiempo establecido
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