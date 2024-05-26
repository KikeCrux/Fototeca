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

require_once 'conexion_BD.php';

// Verificar si se ha enviado un ID de Resguardante para eliminar
if (isset($_GET['id']) && !empty($_GET['id'])) {
    // ID de Resguardante a eliminar
    $id_asignado = $_GET['id'];

    // Consulta SQL para eliminar el Resguardante
    $sql = "DELETE FROM Asignado WHERE ID_Asignado = $id_asignado";

    if ($conn->query($sql) === TRUE) {
        // Mensaje de éxito
        $success_message = "El registro se eliminó correctamente.";
    } else {
        // Mensaje de error
        $error_message = "Error al eliminar el registro: " . $conn->error;
    }
}

// Consultar los registros de la tabla Resguardante
$sql = "SELECT ID_Asignado, Nombre, PuestoDepartamento, Observaciones FROM Asignado";
$result = $conn->query($sql);

// Cerrar la conexión a la base de datos
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bajas Asigando</title>
    <link rel="stylesheet" href="../css/login.css">
    <link rel="stylesheet" href="../css/tablas.css">
</head>

<body>
    <?php
    $pageTitle = "Bajas Asignado";
    include 'header.php';
    echo '<br>';
    echo '<h1 class="text-center">Bajas de Asigando</h1>';
    ?>


    <!-- Mostrar mensaje de éxito -->
    <?php if (!empty($success_message)) : ?>
        <div id="successAlert" class="alert alert-success text-center"><?php echo $success_message; ?></div>
    <?php endif; ?>

    <!-- Mostrar mensaje de error -->
    <?php if (!empty($error_message)) : ?>
        <div id="errorAlert" class="alert alert-danger text-center"><?php echo $error_message; ?></div>
    <?php endif; ?>

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
        function goBack() {
            window.history.back();
        }

        // Función para ocultar la alerta después de un cierto período de tiempo
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