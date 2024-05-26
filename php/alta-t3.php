<?php
session_start();

// Verificar si el usuario está autenticado
// if (!isset($_SESSION['username'])) {
//     header("Location: login.php");
//     exit();
// }

$success_message = '';
$error_message = '';
$imagenContenido = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    require_once 'conexion_BD.php';

    $autores = $_POST["autores"];
    $objeto = $_POST["objeto"];
    $ubicacion = $_POST["ubicacion"];
    $inventario = $_POST["inventario"];
    $vale = $_POST["vale"];
    $fechprestamo = $_POST["fechprestamo"];
    $caracteristicas = $_POST["caracteristicas"];
    $observaciones = $_POST["observaciones"];

    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['imagen']['tmp_name'];
        $fileType = $_FILES['imagen']['type'];
        $fileSize = $_FILES['imagen']['size'];

        if ($fileType == 'application/pdf' && $fileSize < 5000000) { // 5 MB como límite
            $imagenContenido = file_get_contents($fileTmpPath);
            $sql = "INSERT INTO DatosGenerales (Autores, ObjetoObra, Ubicacion, NoInventario, NoVale,
                    FechaPrestamo, Caracteristicas, Observaciones, ImagenOficioVale) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

            $stmt = $conn->prepare($sql);
            if ($stmt === false) {
                $error_message = "Error al preparar la consulta: " . $conn->error;
            } else {
                $null = null; // This is important for the blob type parameter
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
    $pageTitle = "Altas Datos Generales";
    include 'header.php';
    echo '<br>';
    echo '<h1 class="text-center">Registro de Datos Generales</h1>';
    ?>

    <!-- Mostrar mensaje de éxito -->
    <?php if (!empty($success_message)) : ?>
        <div id="successAlert" class="alert alert-success text-center"><?php echo $success_message; ?></div>
    <?php endif; ?>

    <!-- Mostrar mensaje de error -->
    <?php if (!empty($error_message)) : ?>
        <div id="errorAlert" class="alert alert-danger text-center"><?php echo $error_message; ?></div>
    <?php endif; ?>

    <div class="container form mt-5">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="autores">Autor(ES)</label>
                <input type="text" class="form-control" id="autores" name="autores" required>
            </div>
            <br>
            <div class="form-group">
                <label for="objeto">Objeto / Obra</label>
                <input type="text" class="form-control" id="objeto" name="objeto">
            </div>
            <br>
            <div class="form-group">
                <label for="ubicacion">Ubicacion</label>
                <input type="text" class="form-control" id="ubicacion" name="ubicacion" required>
            </div>
            <br>
            <div class="form-group">
                <label for="inventario">Inventario</label>
                <input type="text" class="form-control" id="inventario" name="inventario" required>
            </div>
            <br>
            <div class="form-group">
                <label for="vale">No. Vale</label>
                <input type="text" class="form-control" id="vale" name="vale" required>
            </div>
            <br>
            <div class="form-group">
                <label for="fechprestamo">Fecha: (Prestamo)</label>
                <input type="date" class="form-control" id="fechprestamo" name="fechprestamo" required>
            </div>
            <br>
            <div class="form-group">
                <label for="caracteristicas">Caracteristicas</label>
                <textarea class="form-control" id="caracteristicas" name="caracteristicas" rows="3"></textarea>
            </div>
            <br>
            <div class="form-group">
                <label for="observaciones">Observaciones</label>
                <textarea class="form-control" id="observaciones" name="observaciones" rows="3"></textarea>
            </div>
            <br>
            <div class="form-group">
                <label for="imagen">Imagen de oficio/vale</label>
                <input type="file" class="form-control" id="imagen" name="imagen">
            </div>
            <br>
            <div class="container-btn">
                <button type="submit" class="btnEnviar">Guardar</button>
            </div>
        </form>
    </div>

    <br>

    <div class="container-back">
        <button onclick="goBack()" class="btn btn-secondary mt-3">Regresar</button>
    </div>

    <br>

    <script>
        function goBack() {
            window.history.back();
        }

        setTimeout(function() {
            var successAlert = document.getElementById("successAlert");
            var errorAlert = document.getElementById("errorAlert");
            if (errorAlert) {
                errorAlert.style.display = "none";
            } else if (successAlert) {
                successAlert.style.display = "none";
            }
        }, 5000); // 5000 milisegundos = 5 segundos
    </script>
</body>

</html>