<?php
session_start();

// Verificar autenticación del usuario y redirigir si no está autenticado.
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Cargar conexión a la base de datos.
require_once 'conexion_BD.php';

// Eliminación de usuario si se proporciona un ID válido.
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id_usuario = $_GET['id'];

    // Intenta eliminar primero registros dependientes en 'userlogs' para evitar errores de clave foránea
    $conn->autocommit(FALSE); // Desactiva el autocommit para manejar la transacción manualmente
    try {
        $sql = "DELETE FROM userlogs WHERE ID_Usuario = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();

        $sql = "DELETE FROM Usuarios WHERE ID_Usuario = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();

        $conn->commit(); // Confirma todas las operaciones de la transacción
        $success_message = "El usuario se eliminó correctamente.";
    } catch (mysqli_sql_exception $e) {
        $conn->rollback(); // Revierte la transacción en caso de error
        $error_message = "Error al eliminar el usuario: " . $e->getMessage();
    }
    $conn->autocommit(TRUE); // Reactiva el autocommit
}

// Consulta para obtener la lista de usuarios.
$sql = "SELECT ID_Usuario, Usuario, TipoUsuario FROM Usuarios";
$result = $conn->query($sql);

// Cierra la conexión después de las operaciones.
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bajas Usuarios</title>
    <link rel="stylesheet" href="../css/login.css">
    <link rel="stylesheet" href="../css/tablas.css">
</head>

<body>
    <?php
    // Incluir encabezado con el título de la página.
    $pageTitle = "Bajas Usuarios";
    include 'header.php';
    echo '<h1 class="text-center">Bajas de Usuarios</h1>';
    ?>

    <!-- Sección para mostrar mensajes de éxito o error. -->
    <?php if (!empty($success_message)) : ?>
        <div id="successAlert" class="alert alert-success text-center"><?php echo $success_message; ?></div>
    <?php endif; ?>
    <?php if (!empty($error_message)) : ?>
        <div id="errorAlert" class="alert alert-danger text-center"><?php echo $error_message; ?></div>
    <?php endif; ?>

    <!-- Botón para regresar a la página anterior y tabla para gestionar usuarios. -->
    <div class="container-back">
        <button onclick="goBack()" class="btn btn-secondary mt-3">Regresar</button>
    </div>
    <div class="container mt-5">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Usuario</th>
                    <th>Tipo de Usuario</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <!-- Muestra los usuarios existentes con opciones para eliminar. -->
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row["ID_Usuario"] . "</td>";
                        echo "<td>" . $row["Usuario"] . "</td>";
                        echo "<td>" . $row["TipoUsuario"] . "</td>";
                        echo "<td><a href='baja-t4.php?id=" . $row["ID_Usuario"] . "' class='btn btn-danger'>Eliminar</a></td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>No hay registros</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <script>
        // Función para manejar el regreso a la página anterior y gestionar la visibilidad de alertas.
        function goBack() {
            window.history.back();
        }
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