<?php
// Inicia la sesión y verifica si el usuario está autenticado, redirigiendo al login si no lo está.
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Incluye el script de conexión a la base de datos.
require_once 'conexion_BD.php';

// Procesa la solicitud de eliminación si se ha proporcionado un ID válido.
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id_DatosGenerales = $_GET['id'];
    $sql = "DELETE FROM DatosGenerales WHERE ID_DatosGenerales = $id_DatosGenerales";
    if ($conn->query($sql) === TRUE) {
        $success_message = "El registro se eliminó correctamente.";
    } else {
        $error_message = "Error al eliminar el registro: " . $conn->error;
    }
}

// Realiza una consulta para obtener todos los registros de la tabla DatosGenerales.
$sql = "SELECT ID_DatosGenerales, Autores, ObjetoObra, Ubicacion, NoInventario, NoVale,
                FechaPrestamo, Caracteristicas, Observaciones, ImagenOficioVale FROM DatosGenerales";
$result = $conn->query($sql);

// Cierra la conexión a la base de datos.
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bajas Datos Generales</title>
    <link rel="stylesheet" href="../css/login.css">
    <link rel="stylesheet" href="../css/tablas.css">
</head>

<body>
    <?php
    // Incluye el encabezado de la página web, mostrando el título de la página.
    $pageTitle = "Bajas Datos Generales";
    include 'header.php';
    echo '<br>';
    echo '<h1 class="text-center">Bajas de Datos Generales</h1>';
    ?>

    <!-- Sección para mostrar mensajes de éxito o error tras las operaciones de eliminación. -->
    <?php if (!empty($success_message)) : ?>
        <div id="successAlert" class="alert alert-success text-center"><?php echo $success_message; ?></div>
    <?php endif; ?>
    <?php if (!empty($error_message)) : ?>
        <div id="errorAlert" class="alert alert-danger text-center"><?php echo $error_message; ?></div>
    <?php endif; ?>

    <!-- Botón para regresar a la página anterior y tabla para visualizar los registros de Datos Generales. -->
    <div class="container-back">
        <button onclick="goBack()" class="btn btn-secondary mt-3">Regresar</button>
    </div>
    <div class="container mt-5">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Autor(ES)</th>
                    <th>Objeto / Obra</th>
                    <th>Ubicacion</th>
                    <th>Inventario</th>
                    <th>No. Vale</th>
                    <th>Fecha: (Prestamo)</th>
                    <th>Caracteristicas</th>
                    <th>Observaciones</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <!-- Muestra los registros existentes permitiendo su gestión mediante enlaces de acción. -->
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row["ID_DatosGenerales"] . "</td>";
                        echo "<td>" . $row["Autores"] . "</td>";
                        echo "<td>" . $row["ObjetoObra"] . "</td>";
                        echo "<td>" . $row["Ubicacion"] . "</td>";
                        echo "<td>" . $row["NoInventario"] . "</td>";
                        echo "<td>" . $row["NoVale"] . "</td>";
                        echo "<td>" . $row["FechaPrestamo"] . "</td>";
                        echo "<td>" . $row["Caracteristicas"] . "</td>";
                        echo "<td>" . $row["Observaciones"] . "</td>";
                        echo "<td><a href='baja-t3.php?id=" . $row["ID_DatosGenerales"] . "' class='btn btn-danger'>Eliminar</a></td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='10'>No hay registros</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <script>
        // Función JavaScript para regresar a la página anterior.
        function goBack() {
            window.history.back();
        }
        // Temporizador para ocultar automáticamente las alertas de éxito o error.
        setTimeout(function() {
            var successAlert = document.getElementById("successAlert");
            var errorAlert = document.getElementById("errorAlert");
            if (errorAlert) {
                errorAlert.style.display = "none";
            } else {
                successAlert.style.display = "none";
            }
        }, 5000); // 5000 milisegundos = 5 segundos
    </script>

</body>

</html>