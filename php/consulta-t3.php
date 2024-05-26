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
    <title>Consultas Datos Generales</title>
    <link rel="stylesheet" href="../css/login.css">
    <link rel="stylesheet" href="../css/tablas.css">
</head>

<body>
    <?php
    $pageTitle = "Consultas Datos Generales";
    include 'header.php';
    echo '<br>';
    echo '<h1 class="text-center">Consultas de Datos Generales</h1>';
    ?>

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
                    <!-- <th>Imagen de oficio/vale2</th> -->
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
                        echo "<td>";
                        // Verificar si hay un PDF almacenado y mostrar un enlace para abrirlo
                        if (!empty($row["ImagenOficioVale"])) {
                            // Crear un enlace que apunte a un script de PHP que maneje la visualización del PDF
                            echo '<a href="verPDF.php?id=' . $row["ID_DatosGenerales"] . '">Ver PDF</a>';
                        }
                        echo "</td>";
                        // Imprimir tamaño del BLOB para depuración
                        echo "<td>" . strlen($row["ImagenOficioVale"]) . " bytes</td>";
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
        function goBack() {
            window.history.back();
        }
    </script>

</body>

</html>