<?php
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['username'])) {
    // Si no está autenticado, redirigirlo a la página de inicio de sesión
    header("Location: login.php");
    exit();
}

// Si el usuario está autenticado, mostrar el nombre de usuario
$username = $_SESSION['username'];

// Definir las variables para los mensajes de éxito y error
$success_message = '';
$error_message = '';
$imagenContenido = '';

// Procesar el formulario si se envió
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Conectar a la base de datos
    $servername = "localhost";
    $username = "root";
    $password = "Sandia2016.!";
    $dbname = "fototeca_ob_uaa";

    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }

    // Obtener los datos del formulario
    $autores = $_POST["autores"];
    $objeto = $_POST["objeto"];
    $ubicacion = $_POST["ubicacion"];
    $inventario = $_POST["inventario"];
    $vale = $_POST["vale"];
    $fechprestamo = $_POST["fechprestamo"];
    $caracteristicas = $_POST["caracteristicas"];
    $observaciones = $_POST["observaciones"];

    // Procesar la carga de archivos
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        // Obtener el contenido binario de la imagen
        $imagenContenido = file_get_contents($_FILES['imagen']['tmp_name']);

        // Preparar la consulta SQL con marcadores de posición
        $sql = "INSERT INTO DatosGenerales (Autores, ObjetoObra, Ubicacion, NoInventario, NoVale,
                                        FechaPrestamo, Caracteristicas, Observaciones, ImagenOficioVale) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

        // Preparar la declaración
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssssb", $autores, $objeto, $ubicacion, $inventario, $vale, $fechprestamo, $caracteristicas, $observaciones, $imagenContenido);

        // Ejecutar la consulta preparada
        if ($stmt->execute()) {
            // Mensaje de éxito
            $success_message = "Registro exitoso.";
        } else {
            // Error al ejecutar la consulta
            $error_message = "Error al registrar el dato en la base de datos.";
        }
    } else {
        // No se ha cargado ningún archivo o se ha producido un error
        $error_message = "Por favor, seleccione una imagen válida.";
    }


    // Cerrar la conexión a la base de datos
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
        <!-- Formulario de alta utilizando componentes de Bootstrap -->
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

        // Función para ocultar la alerta después de un cierto período de tiempo
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