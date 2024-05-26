<?php
// Inicia la sesión y verifica la autenticación del usuario
session_start();
if (!isset($_SESSION['username'])) {
    // Redirige a la página de inicio de sesión si el usuario no está autenticado
    header("Location: login.php");
    exit();
}

// Usuario actualmente autenticado
$username = $_SESSION['username'];

// Incluye el archivo de conexión a la base de datos
require_once 'conexion_BD.php';

// Proceso para eliminar un Resguardante basado en el ID proporcionado
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id_resguardante = $_GET['id']; // ID del Resguardante a eliminar

    // Prepara y ejecuta la consulta SQL para eliminar el registro
    $sql = "DELETE FROM Resguardante WHERE ID_Resguardante = $id_resguardante";
    if ($conn->query($sql) === TRUE) {
        $success_message = "El registro se eliminó correctamente.";
    } else {
        $error_message = "Error al eliminar el registro: " . $conn->error;
    }
}

// Consulta para obtener todos los Resguardantes existentes
$sql = "SELECT ID_Resguardante, Nombre, PuestoDepartamento, Observaciones FROM Resguardante";
$result = $conn->query($sql);

// Cierra la conexión a la base de datos después de las operaciones
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bajas Resguardante</title>
    <link rel="stylesheet" href="../css/login.css">
    <link rel="stylesheet" href="../css/tablas.css">
</head>

<body>
    <?php
    // Incluye la cabecera de la página
    include 'header.php';
    echo '<h1 class="text-center">Bajas de Resguardantes</h1>';
    ?>

    <!-- Muestra mensajes de éxito o error después de intentar eliminar un registro -->
    <?php if (!empty($success_message)) : ?>
        <div id="successAlert" class="alert alert-success text-center"><?php echo $success_message; ?></div>
    <?php endif; ?>
    <?php if (!empty($error_message)) : ?>
        <div id="errorAlert" class="alert alert-danger text-center"><?php echo $error_message; ?></div>
    <?php endif; ?>

    <!-- Botón para regresar a la página anterior -->
    <div class="container-back">
        <button onclick="goBack()" class="btn btn-secondary mt-3">Regresar</button>
    </div>

    <!-- Tabla que muestra los Resguardantes y permite la eliminación de estos -->
    <div class="container mt-5">
        <table class="table table-striped table-hover">
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
                <?php

                // Itera sobre cada Resguardante recuperado de la base de datos
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row["ID_Resguardante"] . "</td>";
                        echo "<td>" . $row["Nombre"] . "</td>";
                        echo "<td>" . $row["PuestoDepartamento"] . "</td>";
                        echo "<td>" . $row["Observaciones"] . "</td>";

                        // Proporciona un enlace para eliminar el Resguardante
                        echo "<td><a href='baja-t1.php?id=" . $row["ID_Resguardante"] . "' class='btn btn-danger'>Eliminar</a></td>";
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
        // Funciones de JavaScript para mejorar la interacción del usuario
        function goBack() {
            window.history.back();
        }

        // Oculta los mensajes de alerta automáticamente después de un tiempo
        setTimeout(function() {
            var successAlert = document.getElementById("successAlert");
            var errorAlert = document.getElementById("errorAlert");
            if (errorAlert) {
                errorAlert.style.display = "none";
            } else {
                successAlert.style.display = "none";
            }
        }, 5000);
    </script>
</body>

</html>