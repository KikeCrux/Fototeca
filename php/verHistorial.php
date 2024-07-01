<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

require_once 'conexion_BD.php';

if (!isset($_GET['id'])) {
    header("Location: cambioDatosGen.php");
    exit();
}

$id_DatosGenerales = $_GET['id'];

// Obtener detalles de los datos generales (incluyendo resguardante actual, asignado actual y ubicación actual)
$sql_datos_generales = "SELECT dg.*, p1.Clave AS ClaveResguardanteActual, p1.Nombre AS NombreResguardanteActual, p2.Clave AS ClaveAsignadoActual, p2.Nombre AS NombreAsignadoActual
                        FROM datosgenerales dg
                        LEFT JOIN personal p1 ON dg.ID_Resguardante = p1.ID_Personal
                        LEFT JOIN personal p2 ON dg.ID_Asignado = p2.ID_Personal
                        WHERE dg.ID_DatosGenerales = $id_DatosGenerales";

$result_datos_generales = $conn->query($sql_datos_generales);

if ($result_datos_generales->num_rows > 0) {
    $row_datos_generales = $result_datos_generales->fetch_assoc();
} else {
    header("Location: cambioDatosGen.php");
    exit();
}

// Obtener historial de cambios
$sql_historial = "SELECT h.ID_Historial, dg.NoVale AS NoValeAnterior, p1.Clave AS ClaveResguardanteAnterior, p1.Nombre AS NombreResguardanteAnterior, p2.Clave AS ClaveAsignadoAnterior, p2.Nombre AS NombreAsignadoAnterior, 
                        h.UbicacionAnterior, DATE_FORMAT(h.FechaCambio, '%d-%m-%Y') AS FechaCambioFormato
                 FROM HistorialCambiosDatosGenerales h
                 LEFT JOIN Personal p1 ON h.ID_ResguardanteAnterior = p1.ID_Personal
                 LEFT JOIN Personal p2 ON h.ID_AsignadoAnterior = p2.ID_Personal
                 LEFT JOIN datosgenerales dg ON h.ID_DatosGenerales = dg.ID_DatosGenerales
                 WHERE h.ID_DatosGenerales = $id_DatosGenerales
                 ORDER BY h.FechaCambio DESC";

$result_historial = $conn->query($sql_historial);

// Cerrar conexión
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historial de Cambios</title>
    <link rel="stylesheet" href="../css/login.css">
    <!-- Agregar aquí cualquier otro CSS necesario -->
</head>

<body>
    <?php
    $pageTitle = "Detalles de la Obra con Historial de Cambios";
    include 'header.php';
    ?>

    <div class="container mt-5">
        <h1 class="text-center">Historial de Cambios</h1>
        <br>
        <div class="mb-3">
            <h4>Detalles de la Obra Original</h4>
            <br>
            <p><strong>Autores:</strong> <?php echo htmlspecialchars($row_datos_generales['Autores']); ?></p>
            <p><strong>Obra / Objeto:</strong> <?php echo htmlspecialchars($row_datos_generales['ObjetoObra']); ?></p>
            <p><strong>Resguardante Actual:</strong> <?php echo $row_datos_generales['ClaveResguardanteActual'] . " - " . $row_datos_generales['NombreResguardanteActual']; ?></p>
            <p><strong>Asignado Actual:</strong> <?php echo $row_datos_generales['ClaveAsignadoActual'] . " - " . $row_datos_generales['NombreAsignadoActual']; ?></p>
            <p><strong>Ubicación Actual:</strong> <?php echo htmlspecialchars($row_datos_generales['Ubicacion']); ?></p>
            <p><strong>No. Vale:</strong> <?php echo htmlspecialchars($row_datos_generales['NoVale']); ?></p>
            <p><strong>Fecha de Préstamo:</strong> <?php echo $row_datos_generales['FechaPrestamo'] ? date("d-m-Y", strtotime($row_datos_generales['FechaPrestamo'])) : 'N/A'; ?></p>
        </div>

        <?php if ($result_historial->num_rows > 0) : ?>
            <div class="mb-3">
                <br>
                <h4>Historial de Cambios</h4>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Fecha de Cambio</th>
                                <th>Resguardante Anterior</th>
                                <th>Asignado Anterior</th>
                                <th>Ubicación Anterior</th>
                                <th>No. Vale Anterior</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row_historial = $result_historial->fetch_assoc()) : ?>
                                <tr>
                                    <td><?php echo $row_historial['FechaCambioFormato']; ?></td>
                                    <td><?php echo $row_historial['ClaveResguardanteAnterior'] . " - " . $row_historial['NombreResguardanteAnterior']; ?></td>
                                    <td><?php echo $row_historial['ClaveAsignadoAnterior'] . " - " . $row_historial['NombreAsignadoAnterior']; ?></td>
                                    <td><?php echo htmlspecialchars($row_historial['UbicacionAnterior']); ?></td>
                                    <td><?php echo htmlspecialchars($row_historial['NoValeAnterior']); ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php else : ?>
            <p class="text-center">No se han registrado cambios para esta obra.</p>
        <?php endif; ?>

        <div class="text-center">
            <a href="consultaDatosGen.php" class="btn btn-secondary">Volver</a>
        </div>
    </div>
    <br>
</body>

</html>