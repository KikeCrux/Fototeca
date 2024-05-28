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

require_once 'conexion_BD.php';

// Definir el número de registros por página
$registrosPorPagina = 15;

// Obtener el número de página actual
if (isset($_GET['pagina'])) {
    $paginaActual = $_GET['pagina'];
} else {
    $paginaActual = 1;
}

// Calcular el inicio de los registros a mostrar en la página actual
$inicio = ($paginaActual - 1) * $registrosPorPagina;

// Consultar los registros de la tabla UserLogs para la página actual
$sql = "SELECT ID_Log, ID_Usuario, FechaHora, IP FROM UserLogs LIMIT $inicio, $registrosPorPagina";
$result = $conn->query($sql);

// Obtener el total de registros en la tabla
$totalRegistros = $conn->query("SELECT count(*) as total FROM UserLogs")->fetch_assoc()['total'];

// Calcular el total de páginas
$totalPaginas = ceil($totalRegistros / $registrosPorPagina);

// Cerrar la conexión a la base de datos
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consultas inicios</title>
    <link rel="stylesheet" href="../css/login.css">
    <link rel="stylesheet" href="../css/tablas.css">
</head>

<body>
    <?php
    $pageTitle = "Consultas inicios";
    include 'header.php';
    echo '<br>';
    echo '<h1 class="text-center">Consultas de inicios de sesión</h1>';
    ?>

    <div class="container mt-5">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID Log</th>
                    <th>ID Usuario</th>
                    <th>Fecha y Hora</th>
                    <th>IP</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row["ID_Log"] . "</td>";
                        echo "<td>" . $row["ID_Usuario"] . "</td>";
                        echo "<td>" . $row["FechaHora"] . "</td>";
                        echo "<td>" . $row["IP"] . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>No hay registros</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <!-- Mostrar paginación -->
        <div class="text-center paginacion">
            <?php if ($totalPaginas > 1) : ?>
                <ul class="pagination">
                    <?php if ($paginaActual > 1) : ?>
                        <li class="page-item"><a class="page-link" href="?pagina=<?php echo $paginaActual - 1; ?>">Anterior</a></li>
                    <?php endif; ?>
                    <?php for ($i = 1; $i <= $totalPaginas; $i++) : ?>
                        <li class="page-item <?php if ($i == $paginaActual) echo 'active'; ?>"><a class="page-link" href="?pagina=<?php echo $i; ?>"><?php echo $i; ?></a></li>
                    <?php endfor; ?>
                    <?php if ($paginaActual < $totalPaginas) : ?>
                        <li class="page-item"><a class="page-link" href="?pagina=<?php echo $paginaActual + 1; ?>">Siguiente</a></li>
                    <?php endif; ?>
                </ul>
            <?php endif; ?>
        </div>
    </div>

</body>

</html>