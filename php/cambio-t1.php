<?php
// Inicia la sesión y verifica la autenticación del usuario. Redirige a la página de inicio de sesión si no está autenticado.
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Almacena el nombre de usuario en una variable para uso posterior.
$username = $_SESSION['username'];

// Incluye el archivo de conexión a la base de datos y realiza una consulta para obtener los resguardantes.
require_once 'conexion_BD.php';
$sql = "SELECT ID_Resguardante, Nombre, PuestoDepartamento, Observaciones FROM Resguardante";
$result = $conn->query($sql);

// Cierra la conexión a la base de datos después de obtener los datos necesarios.
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cambios Resguardante</title>
    <link rel="stylesheet" href="../css/login.css">
    <link rel="stylesheet" href="../css/tablas.css">
</head>

<body>
    <?php
    // Incluye el encabezado de la página, usando el título dinámico para la página.
    $pageTitle = "Cambios Resguardante";
    include 'header.php';
    echo '<h1 class="text-center">Cambios de Resguardantes</h1>';
    ?>

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
                <?php
                // Muestra los registros de resguardantes en la tabla y proporciona un enlace para realizar cambios.
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row["ID_Resguardante"] . "</td>";
                        echo "<td>" . $row["Nombre"] . "</td>";
                        echo "<td>" . $row["PuestoDepartamento"] . "</td>";
                        echo "<td>" . $row["Observaciones"] . "</td>";
                        echo "<td><a href='procesar_cambios-t1.php?id=" . $row["ID_Resguardante"] . "' class='btn btn-primary'>Cambiar</a></td>";
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
        // Función para permitir al usuario regresar a la página anterior.
        function goBack() {
            window.history.back();
        }
    </script>
</body>

</html>