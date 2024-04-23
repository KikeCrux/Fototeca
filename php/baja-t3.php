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

// Verificar si se ha enviado un ID de Resguardante para eliminar
if (isset($_GET['id']) && !empty($_GET['id'])) {
    // ID de Resguardante a eliminar
    $id_DatosGenerales = $_GET['id'];

    // Consulta SQL para eliminar el Resguardante
    $sql = "DELETE FROM DatosGenerales WHERE ID_DatosGenerales = $id_DatosGenerales";

    if ($conn->query($sql) === TRUE) {
        // Mensaje de éxito
        $success_message = "El registro se eliminó correctamente.";
    } else {
        // Mensaje de error
        $error_message = "Error al eliminar el registro: " . $conn->error;
    }
}

// Consultar los registros de la tabla Resguardante
$sql = "SELECT ID_DatosGenerales, Autores, ObjetoObra, Ubicacion, NoInventario, NoVale,
                FechaPrestamo, Caracteristicas, Observaciones, ImagenOficioVale FROM DatosGenerales";
$result = $conn->query($sql);

// Cerrar la conexión a la base de datos
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bajas Datos Generales</title>
    <link rel="stylesheet" href="../css/login.css">
</head>

<body>
    <?php
    $pageTitle = "Bajas Datos Generales";
    include 'header.php';
    echo '<br>';
    echo '<h1 class="text-center">Bajas de Datos Generales</h1>';
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
                    <th>Autor(ES)</th>
                    <th>Objeto / Obra</th>
                    <th>Ubicacion</th>
                    <th>Inventario</th>
                    <th>No. Vale</th>
                    <th>Fecha: (Prestamo)</th>
                    <th>Caracteristicas</th>
                    <th>Observaciones</th>
                    <th>Imagen de oficio/vale</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
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
                        echo "<td>" . $row["ImagenOficioVale"] . "</td>";
                        echo "<td><a href='baja-t3.php?id=" . $row["ID_DatosGenerales"] . "' class='btn btn-danger'>Eliminar</a></td>";
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