<?php
// Inicia la sesión y verifica si el usuario está autenticado, redirigiendo al login si no lo está.
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Incluye el script de conexión a la base de datos.
require_once 'conexion_BD.php';

// Procesa la solicitud de eliminación si se ha proporcionado un ID válido.
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id_DatosGenerales = $_GET['id'];
    $sql = "DELETE FROM DatosGenerales WHERE ID_DatosGenerales = $id_DatosGenerales";
    if ($conn->query($sql) === TRUE) {
        $success_message = "El registro se eliminó correctamente.";
    } else {
        $error_message = "Error al eliminar el registro: " . $conn->error;
    }
}

$search = isset($_POST['search']) ? $_POST['search'] : '';

// Realiza una consulta para obtener los registros de la tabla DatosGenerales según el término de búsqueda
if (!empty($search)) {
    $sql = "SELECT dg.ID_DatosGenerales, dg.Autores, dg.ObjetoObra, dg.Ubicacion, dg.NoInventario, dg.NoVale, 
            dg.FechaPrestamo, dg.Caracteristicas, dg.Observaciones, 
            p1.Clave as ClaveResguardante, p2.Clave as ClaveAsignado, p1.Nombre as NombreResguardante, p2.Nombre as NombreAsignado
            FROM DatosGenerales dg
            LEFT JOIN Personal p1 ON dg.ID_Resguardante = p1.ID_Personal
            LEFT JOIN Personal p2 ON dg.ID_Asignado = p2.ID_Personal
            WHERE dg.Autores LIKE ? OR dg.ObjetoObra LIKE ? OR p1.Clave LIKE ? OR p2.Clave LIKE ?";
    $stmt = $conn->prepare($sql);
    $searchTerm = "%" . $search . "%";
    $stmt->bind_param("ssss", $searchTerm, $searchTerm, $searchTerm, $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $sql = "SELECT dg.ID_DatosGenerales, dg.Autores, dg.ObjetoObra, dg.Ubicacion, dg.NoInventario, dg.NoVale, 
            dg.FechaPrestamo, dg.Caracteristicas, dg.Observaciones, 
            p1.Clave as ClaveResguardante, p2.Clave as ClaveAsignado, p1.Nombre as NombreResguardante, p2.Nombre as NombreAsignado
            FROM DatosGenerales dg
            LEFT JOIN Personal p1 ON dg.ID_Resguardante = p1.ID_Personal
            LEFT JOIN Personal p2 ON dg.ID_Asignado = p2.ID_Personal";
    $result = $conn->query($sql);
}

// Cierra la conexión a la base de datos.
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bajas Datos Generales</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/login.css">
    <link rel="stylesheet" href="../css/tablas.css">
</head>

<body>
    <?php
    // Incluye el encabezado de la página web, mostrando el título de la página.
    $pageTitle = "Bajas Datos Generales";
    include 'header.php';
    ?>

    <!-- Sección para mostrar mensajes de éxito o error tras las operaciones de eliminación. -->
    <?php if (!empty($success_message)) : ?>
        <div id="successAlert" class="alert alert-success text-center"><?php echo $success_message; ?></div>
    <?php endif; ?>
    <?php if (!empty($error_message)) : ?>
        <div id="errorAlert" class="alert alert-danger text-center"><?php echo $error_message; ?></div>
    <?php endif; ?>

    <br>
    <h1 class="text-center">Bajas de Datos Generales</h1>

    <!-- Formulario de búsqueda -->
    <div class="container mt-3">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="input-group mb-3">
                <input type="text" name="search" class="form-control" placeholder="Buscar por autor, objeto/obra, clave resguardante o clave asignado" value="<?php echo htmlspecialchars($search); ?>">
            </div>
            <button class="btn btn-primary" type="submit">Buscar</button>
        </form>
    </div>

    <!--Tabla para visualizar los registros de Datos Generales. -->
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
                    <th>Fecha Prestamo</th>
                    <th>Clave Resguardante</th>
                    <th>Clave Asignado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <!-- Muestra los registros existentes permitiendo su gestión mediante enlaces de acción. -->
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row["ID_DatosGenerales"] . "</td>";
                        echo "<td>" . htmlspecialchars($row["Autores"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["ObjetoObra"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["Ubicacion"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["NoInventario"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["NoVale"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["FechaPrestamo"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["ClaveResguardante"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["ClaveAsignado"]) . "</td>";
                        echo "<td><a href='baja-t3.php?id=" . $row["ID_DatosGenerales"] . "' class='btn btn-danger' onclick='return confirm(\"¿Está seguro de que desea eliminar este registro?\")'>Eliminar</a></td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='12'>No hay registros</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <script>
        // Temporizador para ocultar automáticamente las alertas de éxito o error.
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