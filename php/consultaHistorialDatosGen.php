<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

require_once 'conexion_BD.php';

// Inicializar variables de búsqueda
$searchID = '';
$searchClave = '';
$searchObra = '';
$searchAutor = '';

if (isset($_GET['searchID'])) {
    $searchID = $_GET['searchID'];
}

if (isset($_GET['searchClave'])) {
    $searchClave = $_GET['searchClave'];
}

if (isset($_GET['searchObra'])) {
    $searchObra = $_GET['searchObra'];
}

if (isset($_GET['searchAutor'])) {
    $searchAutor = $_GET['searchAutor'];
}

// Obtener todas las obras que tienen cambios en el historial, filtradas por ID de obra, clave de asignado o resguardante anterior, Objeto/Obra o autor
$sql_historial = "SELECT dg.ID_DatosGenerales AS ID_Obra, dg.ObjetoObra AS Obra_Objeto, dg.NoVale, dg.Autores, DATE(h.FechaCambio) AS FechaCambio, 
                         p1.ID_Personal AS ID_ResguardanteAnterior, p1.Nombre AS NombreResguardanteAnterior, 
                         p2.ID_Personal AS ID_AsignadoAnterior, p2.Nombre AS NombreAsignadoAnterior, 
                         h.UbicacionAnterior
                  FROM HistorialCambiosDatosGenerales h
                  JOIN datosgenerales dg ON h.ID_DatosGenerales = dg.ID_DatosGenerales
                  LEFT JOIN Personal p1 ON h.ID_ResguardanteAnterior = p1.ID_Personal
                  LEFT JOIN Personal p2 ON h.ID_AsignadoAnterior = p2.ID_Personal
                  WHERE ('$searchID' = '' OR dg.ID_DatosGenerales = '$searchID')
                  AND ('$searchClave' = '' OR p1.Clave = '$searchClave' OR p2.Clave = '$searchClave')
                  AND ('$searchObra' = '' OR dg.ObjetoObra LIKE '%$searchObra%')
                  AND ('$searchAutor' = '' OR dg.Autores LIKE '%$searchAutor%')
                  ORDER BY dg.ObjetoObra, h.FechaCambio DESC";

$result_historial = $conn->query($sql_historial);

// Generar CSV si se ha enviado la solicitud
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['generate_csv'])) {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="historial_cambios.csv"');
    $output = fopen('php://output', 'w');

    // Cabeceras CSV
    fputcsv($output, array(
        'ID_Obra',
        'Obra_Objeto',
        'NoVale',
        'Autores',
        'FechaCambio',
        'ID_ResguardanteAnterior',
        'NombreResguardanteAnterior',
        'ID_AsignadoAnterior',
        'NombreAsignadoAnterior',
        'UbicacionAnterior'
    ));

    // Obtener datos filtrados para CSV
    while ($row = $result_historial->fetch_assoc()) {
        fputcsv($output, array(
            $row['ID_Obra'],
            $row['Obra_Objeto'],
            $row['NoVale'],
            $row['Autores'],
            $row['FechaCambio'],
            $row['ID_ResguardanteAnterior'],
            $row['NombreResguardanteAnterior'],
            $row['ID_AsignadoAnterior'],
            $row['NombreAsignadoAnterior'],
            $row['UbicacionAnterior']
        ));
    }

    fclose($output);
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulta de Historial de Cambios</title>
    <link rel="stylesheet" href="../css/login.css">
</head>

<body>
    <?php
    $pageTitle = "Consulta de Historial de Cambios";
    include 'header.php';
    ?>

    <div class="container mt-5">
        <h1 class="text-center">Consulta de Historial de Cambios</h1>
        <br>

        <!-- Formulario de búsqueda -->
        <form class="mb-4" method="get" action="consultaHistorialDatosGen.php">
            <div class="input-group mb-3">
                <input type="text" class="form-control" name="searchID" placeholder="Buscar por ID de obra" value="<?php echo htmlspecialchars($searchID); ?>">
                <input type="text" class="form-control" name="searchClave" placeholder="Buscar por clave de asignado o resguardante anterior" value="<?php echo htmlspecialchars($searchClave); ?>">
                <input type="text" class="form-control" name="searchObra" placeholder="Buscar por Objeto/Obra" value="<?php echo htmlspecialchars($searchObra); ?>">
                <input type="text" class="form-control" name="searchAutor" placeholder="Buscar por Autor" value="<?php echo htmlspecialchars($searchAutor); ?>">
            </div>
            <button class="btn btn-primary" type="submit">Buscar</button>
        </form>

        <!-- Botón para generar CSV -->
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <input type="hidden" name="generate_csv" value="1">
            <input type="hidden" name="searchID" value="<?php echo htmlspecialchars($searchID); ?>">
            <input type="hidden" name="searchClave" value="<?php echo htmlspecialchars($searchClave); ?>">
            <input type="hidden" name="searchObra" value="<?php echo htmlspecialchars($searchObra); ?>">
            <input type="hidden" name="searchAutor" value="<?php echo htmlspecialchars($searchAutor); ?>">
            <button class="btn btn-success" type="submit">Generar CSV</button>
        </form>
        <br>

        <?php if ($result_historial->num_rows > 0) : ?>
            <?php
            $currentObra = "";
            while ($row_historial = $result_historial->fetch_assoc()) :
                if ($currentObra != $row_historial['Obra_Objeto']) {
                    if ($currentObra != "") {
                        echo "</tbody></table></div></div>";
                    }
                    $currentObra = $row_historial['Obra_Objeto'];
            ?>
                    <div class="mb-3">
                        <h4>ID: <?php echo htmlspecialchars($row_historial['ID_Obra']); ?> | <?php echo htmlspecialchars($row_historial['Obra_Objeto']); ?></h4>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Fecha de Cambio</th>
                                        <th>Resguardante Anterior</th>
                                        <th>Asignado Anterior</th>
                                        <th>Ubicación Anterior</th>
                                        <th>No Vale Anterior</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                            }
                                ?>
                                <tr>
                                    <td><?php echo $row_historial['FechaCambio']; ?></td>
                                    <td><?php echo $row_historial['ID_ResguardanteAnterior'] . ' - ' . $row_historial['NombreResguardanteAnterior']; ?></td>
                                    <td><?php echo $row_historial['ID_AsignadoAnterior'] . ' - ' . $row_historial['NombreAsignadoAnterior']; ?></td>
                                    <td><?php echo htmlspecialchars($row_historial['UbicacionAnterior']); ?></td>
                                    <td><?php echo htmlspecialchars($row_historial['NoVale']); ?></td>
                                </tr>
                            <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php else : ?>
                    <p class="text-center">No se han registrado cambios para esta búsqueda.</p>
                <?php endif; ?>

                <div class="text-center">
                    <a href="consultaHistorialDatosGen.php" class="btn btn-secondary">Volver</a>
                </div>
    </div>
    <br>
</body>

</html>