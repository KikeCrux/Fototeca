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

// Realizar la conexión a la base de datos
$servername = "localhost";
$db_username = "root";
$db_password = "Sandia2016.!";
$dbname = "fototeca_ob_uaa";

$conn = new mysqli($servername, $db_username, $db_password, $dbname);
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Verificar si se ha enviado un ID de Usuario para eliminar
if (isset($_GET['id']) && !empty($_GET['id'])) {
    // ID de Usuario a eliminar
    $id_usuario = $_GET['id'];

    // Consulta SQL para eliminar el Usuario
    $sql = "DELETE FROM Usuarios WHERE ID_Usuario = $id_usuario";

    if ($conn->query($sql) === TRUE) {
        // Mensaje de éxito
        $success_message = "El usuario se eliminó correctamente.";
    } else {
        // Mensaje de error
        $error_message = "Error al eliminar el usuario: " . $conn->error;
    }
}

// Consultar los registros de la tabla Usuarios
$sql = "SELECT ID_Usuario, Usuario, TipoUsuario FROM Usuarios";
$result = $conn->query($sql);

// Cerrar la conexión a la base de datos
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bajas Usuarios</title>
    <link rel="stylesheet" href="../css/login.css">
    <link rel="stylesheet" href="../css/tablas.css"> <!-- Asegúrate de tener un archivo CSS llamado tablas.css para aplicar estilos a la tabla -->
</head>

<body>
    <?php
    $pageTitle = "Bajas Usuarios";
    include 'header.php'; // Incluir el archivo header.php si contiene el encabezado de la página
    echo '<br>';
    echo '<h1 class="text-center">Bajas de Usuarios</h1>';
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
                    <th>Usuario</th>
                    <th>Tipo de Usuario</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
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