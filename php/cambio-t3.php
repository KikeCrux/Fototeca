<?php
// Iniciar sesión y verificar autenticación. Redirige si el usuario no está autenticado.
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Accede al nombre de usuario autenticado y lo almacena en una variable.
$username = $_SESSION['username'];

// Incluye el script de conexión a la base de datos.
require_once 'conexion_BD.php';

// Realiza una consulta para obtener los registros de Datos Generales.
$sql = "SELECT ID_DatosGenerales, Autores, ObjetoObra, Ubicacion, NoInventario, NoVale,
                FechaPrestamo, Caracteristicas, Observaciones, ImagenOficioVale FROM DatosGenerales";
$result = $conn->query($sql);

// Cierra la conexión a la base de datos después de las operaciones.
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <!-- Define los metadatos de la página y enlaza las hojas de estilo para el diseño. -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cambios Datos Generales</title>
    <link rel="stylesheet" href="../css/login.css">
    <link rel="stylesheet" href="../css/tablas.css">
</head>

<body>
    <?php
    // Incluye el archivo de cabecera y muestra el título de la página dinámicamente.
    $pageTitle = "Cambios Datos Generales";
    include 'header.php';
    echo '<h1 class="text-center">Cambios de Datos Generales</h1>';
    ?>

    <!-- Botón para regresar a la página anterior. -->
    <div class="container-back">
        <button onclick="goBack()" class="btn btn-secondary mt-3">Regresar</button>
    </div>

    <!-- Tabla para mostrar los datos de Datos Generales y proporcionar una acción de cambio. -->
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
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <!-- Itera sobre cada registro de Datos Generales y muestra sus detalles con una opción para editar. -->
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
                        echo "<td><a href='procesar_cambios-t3.php?id=" . $row["ID_DatosGenerales"] . "' class='btn btn-primary'>Cambiar</a></td>";
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
        // Función JavaScript para permitir al usuario regresar a la página anterior.
        function goBack() {
            window.history.back();
        }
    </script>
</body>

</html>