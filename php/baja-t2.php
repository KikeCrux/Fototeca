<?php
// Iniciar sesión y verificar si el usuario está autenticado.
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Carga la configuración de conexión a la base de datos.
require_once 'conexion_BD.php';

// Procesar eliminación si se recibe un ID válido por método GET.
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id_asignado = $_GET['id'];
    $sql = "DELETE FROM Asignado WHERE ID_Asignado = $id_asignado";
    if ($conn->query($sql) === TRUE) {
        $success_message = "El registro se eliminó correctamente.";
    } else {
        $error_message = "Error al eliminar el registro: " . $conn->error;
    }
}

// Consulta para obtener todos los registros de asignados y preparar la visualización en la tabla.
$sql = "SELECT ID_Asignado, Nombre, PuestoDepartamento, Observaciones FROM Asignado";
$result = $conn->query($sql);

// Cierra la conexión a la base de datos después de realizar las operaciones.
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <!-- Configuraciones básicas de la página HTML -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bajas Asignado</title>
    <link rel="stylesheet" href="../css/login.css">
    <link rel="stylesheet" href="../css/tablas.css">
</head>

<body>
    <?php
    // Configura y muestra el encabezado de la página, incluyendo el título.
    $pageTitle = "Bajas Asignado";
    include 'header.php';
    echo '<br>';
    echo '<h1 class="text-center">Bajas de Asignado</h1>';
    ?>

    <!-- Muestra mensajes de éxito o error tras realizar operaciones de eliminación. -->
    <?php if (!empty($success_message)) : ?>
        <div id="successAlert" class="alert alert-success text-center"><?php echo $success_message; ?></div>
    <?php endif; ?>
    <?php if (!empty($error_message)) : ?>
        <div id="errorAlert" class="alert alert-danger text-center"><?php echo $error_message; ?></div>
    <?php endif; ?>

    <!-- Botón de regreso y tabla para visualizar y gestionar asignados. -->
    <div class="container-back">
        <button onclick="goBack()" class="btn btn-secondary mt-3">Regresar</button>
    </div>
    <div class="container mt-5">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Puesto / Departamento</th>
                    <th>Observaciones</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <!-- Loop para mostrar registros existentes y opciones para manejarlos. -->
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row["ID_Asignado"] . "</td>";
                        echo "<td>" . $row["Nombre"] . "</td>";
                        echo "<td>" . $row["PuestoDepartamento"] . "</td>";
                        echo "<td>" . $row["Observaciones"] . "</td>";
                        echo "<td><a href='baja-t2.php?id=" . $row["ID_Asignado"] . "' class='btn btn-danger'>Eliminar</a></td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>No hay registros</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <script>
        // Función para regresar a la página anterior.
        function goBack() {
            window.history.back();
        }

        // Temporizador para ocultar alertas automáticamente.
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