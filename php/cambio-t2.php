<?php
// Inicia la sesión y verifica si el usuario está autenticado, redirigiendo al login si no lo está.
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Obtiene el nombre del usuario autenticado y lo almacena en una variable.
$username = $_SESSION['username'];

// Incluye el script de conexión a la base de datos y realiza una consulta para obtener los asignados.
require_once 'conexion_BD.php';
$sql = "SELECT ID_Asignado, Nombre, PuestoDepartamento, Observaciones FROM Asignado";
$result = $conn->query($sql);

// Cierra la conexión a la base de datos una vez que se han recuperado los datos necesarios.
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cambios Asignado</title>
    <link rel="stylesheet" href="../css/login.css">
    <link rel="stylesheet" href="../css/tablas.css">
</head>

<body>
    <?php
    // Incluye el archivo de cabecera y muestra el título de la página dinámicamente.
    $pageTitle = "Cambios Asignado";
    include 'header.php';
    echo '<h1 class="text-center">Cambios de Asignado</h1>';
    ?>

    <!-- Botón para regresar a la página anterior. -->
    <div class="container-back">
        <button onclick="goBack()" class="btn btn-secondary mt-3">Regresar</button>
    </div>

    <!-- Tabla para mostrar los datos de los asignados y proporcionar una acción de cambio. -->
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
                <?php
                // Itera sobre cada registro de asignados y muestra sus detalles con una opción para editar.
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row["ID_Asignado"] . "</td>";
                        echo "<td>" . $row["Nombre"] . "</td>";
                        echo "<td>" . $row["PuestoDepartamento"] . "</td>";
                        echo "<td>" . $row["Observaciones"] . "</td>";
                        echo "<td><a href='procesar_cambios-t2.php?id=" . $row["ID_Asignado"] . "' class='btn btn-primary'>Cambiar</a></td>";
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
        // Función JavaScript para permitir al usuario regresar a la página anterior.
        function goBack() {
            window.history.back();
        }
    </script>

</body>

</html>