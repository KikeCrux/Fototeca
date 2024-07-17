<?php
// Establecer la zona horaria a Ciudad de México (CDMX)
date_default_timezone_set('America/Mexico_City');

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
    // Conectar a la base de datos
    $servername = "localhost";
    $username = "root";
    $password = "Trompudo117";
    $dbname = "fototeca_uaa";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Verificar si se ha enviado un ID de Seccion Tecnica para eliminar
if (isset($_GET['id']) && !empty($_GET['id'])) {
    // ID de Resguardante a eliminar
    $id_secciontecnica = $_GET['id'];

    // Consulta SQL para eliminar el Resguardante
    $sql = "DELETE FROM Fototeca WHERE ID_Tecnica = $id_secciontecnica";

    if ($conn->query($sql) === TRUE) {
        // Mensaje de éxito
        $success_message = "El registro se eliminó correctamente.";
    } else {
        // Mensaje de error
        $error_message = "Error al eliminar el registro: " . $conn->error;
    }
}


// Consultas para obtener los datos de cada tabla relacionada
$sqlTecnica = "SELECT * FROM Fototeca";
$resultTecnica = $conn->query($sqlTecnica);

// Cerrar la conexión a la base de datos
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">

<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cambios</title>
    <link rel="stylesheet" href="../css/login.css">
    <link rel="stylesheet" href="../../css/tablas.css">
</head>

<body>

    <body>
        <!-- Mostrar mensaje de éxito -->
        <?php if (!empty($success_message)) : ?>
            <div id="successAlert" class="alert alert-success text-center"><?php echo $success_message; ?></div>
        <?php endif; ?>

        <!-- Mostrar mensaje de error -->
        <?php if (!empty($error_message)) : ?>
            <div id="errorAlert" class="alert alert-danger text-center"><?php echo $error_message; ?></div>
        <?php endif; ?>
        <?php include 'header.php'; ?>
        <div class="container mt-4">

            <div class="card">
                <div class="card-header">
                    <strong>Sección Técnica</strong>
                </div>
                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID Técnica</th>
                                <th>Número Inventario</th>
                                <th>Clave Técnica</th>
                                <th>Proceso Fotografico</th>
                                <th>Fondo Coleccion</th>
                                <th>Formato</th>
                                <th># Negativo Copia</th>
                                <th>Tipo</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($resultTecnica->num_rows > 0) {
                                while ($row = $resultTecnica->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td>" . $row["ID_Tecnica"] . "</td>";
                                    echo "<td>" . $row["NumeroInventario"] . "</td>";
                                    echo "<td>" . $row["ClaveTecnica"] . "</td>";
                                    echo "<td>" . $row["ProcesoFotografico"] . "</td>";
                                    echo "<td>" . $row["FondoColeccion"] . "</td>";
                                    echo "<td>" . $row["Formato"] . "</td>";
                                    echo "<td>" . $row["NumeroNegativoCopia"] . "</td>";
                                    echo "<td>" . $row["Tipo"] . "</td>";

                                    echo "<td><a href='bajas.php?id=" . $row["ID_Tecnica"] . "' class='btn btn-danger'>Eliminar</a></td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='3'>No hay datos disponibles.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                <div class="card">
                    <strong>Verificar en consultar si se elimino con exito</strong>
                </div>
            </div>

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