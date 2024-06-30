<?php
// Iniciar sesión y verificar autenticación. Redirige si el usuario no está autenticado.
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

// Incluye el script de conexión a la base de datos.
require_once 'conexion_BD.php';

// Inicializar variables para almacenar los valores seleccionados.
$selectedTipoObra = isset($_POST['tipoObra']) ? $_POST['tipoObra'] : '';
$selectedResguardante = isset($_POST['resguardante']) ? $_POST['resguardante'] : '';
$selectedAsignado = isset($_POST['asignado']) ? $_POST['asignado'] : '';

// Consulta para obtener las opciones de Tipo de Obra.
$tipoObraQuery = "SELECT DISTINCT TipoObra FROM DatosGenerales";
$tipoObraResult = $conn->query($tipoObraQuery);
$tipoObraOptions = [];
while ($row = $tipoObraResult->fetch_assoc()) {
    $tipoObraOptions[] = $row['TipoObra'];
}

// Consulta para obtener las opciones de personal.
$personalQuery = "SELECT ID_Personal, Clave, Nombre FROM Personal WHERE Estatus = 'Vigente'";
$personalResult = $conn->query($personalQuery);
$personalOptions = [];
while ($row = $personalResult->fetch_assoc()) {
    $personalOptions[] = $row;
}

// Inicializar la variable para almacenar los resultados.
$data = [];

// Construir la consulta SQL para obtener los datos con o sin filtros.
$sql = "SELECT ID_DatosGenerales, Autores, ObjetoObra, Ubicacion, NoInventario, NoVale, FechaPrestamo, Caracteristicas, Observaciones, TipoObra, ID_Resguardante, ID_Asignado FROM DatosGenerales WHERE 1=1";
if (!empty($selectedTipoObra)) {
    $sql .= " AND TipoObra = '$selectedTipoObra'";
}
if (!empty($selectedResguardante)) {
    $sql .= " AND ID_Resguardante = '$selectedResguardante'";
}
if (!empty($selectedAsignado)) {
    $sql .= " AND ID_Asignado = '$selectedAsignado'";
}
$result = $conn->query($sql);

// Obtener los datos para mostrar en la tabla.
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
} else {
    $error_message = "No se encontraron registros.";
}

// Generar el archivo de texto si se ha solicitado.
if (isset($_POST['generate_txt'])) {
    if (!empty($data)) {
        $filename = "estadisticas_" . date('Ymd') . ".csv";
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        $output = fopen('php://output', 'w');

        // Agregar el encabezado del archivo CSV.
        fputcsv($output, array('ID', 'Autores', 'ObjetoObra', 'Ubicacion', 'NoInventario', 'NoVale', 'FechaPrestamo', 'Caracteristicas', 'Observaciones', 'TipoObra', 'ID_Resguardante', 'ID_Asignado'));

        // Agregar los datos al archivo CSV.
        foreach ($data as $row) {
            // Convertir todos los campos a strings para evitar problemas con caracteres especiales.
            $row = array_map('strval', $row);
            fputcsv($output, $row);
        }

        fclose($output);
        exit();
    } else {
        $error_message = "No hay datos para generar el archivo.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estadísticas</title>
    <link rel="stylesheet" href="../css/login.css">
    <link rel="stylesheet" href="../css/tablas.css">
</head>

<body>
    <?php
    // Incluye el archivo de cabecera y muestra el título de la página dinámicamente.
    $pageTitle = "Estadísticas";
    include 'header.php';
    echo '<br><h1 class="text-center">Estadísticas</h1>';
    ?>

    <!-- Formulario de filtros -->
    <div class="container mt-3">
        <div class="card mb-4">
            <div class="card-header">
                <strong>Filtros</strong>
            </div>
            <div class="card-body">
                <form method="POST" action="">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="tipoObra">Tipo de Obra</label>
                            <select id="tipoObra" name="tipoObra" class="form-control">
                                <option value="">Seleccione...</option>
                                <?php foreach ($tipoObraOptions as $option) : ?>
                                    <option value="<?php echo $option; ?>" <?php echo ($option === $selectedTipoObra) ? 'selected' : ''; ?>><?php echo $option; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="resguardante">Resguardante</label>
                            <select id="resguardante" name="resguardante" class="form-control">
                                <option value="">Seleccione...</option>
                                <?php foreach ($personalOptions as $option) : ?>
                                    <option value="<?php echo $option['ID_Personal']; ?>" <?php echo ($option['ID_Personal'] === $selectedResguardante) ? 'selected' : ''; ?>><?php echo $option['Clave'] . " - " . $option['Nombre']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="asignado">Asignado</label>
                            <select id="asignado" name="asignado" class="form-control">
                                <option value="">Seleccione...</option>
                                <?php foreach ($personalOptions as $option) : ?>
                                    <option value="<?php echo $option['ID_Personal']; ?>" <?php echo ($option['ID_Personal'] === $selectedAsignado) ? 'selected' : ''; ?>><?php echo $option['Clave'] . " - " . $option['Nombre']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <button type="submit" name="filter" class="btn btn-primary">Filtrar</button>
                    <button type="submit" name="generate_txt" class="btn btn-success ml-2">Generar CSV</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Mostrar la tabla de resultados filtrados -->
    <div class="container mt-3">
        <?php if (!empty($data)) : ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Autores</th>
                        <th>Objeto/Obra</th>
                        <th>Ubicacion</th>
                        <th>NoInventario</th>
                        <th>NoVale</th>
                        <th>FechaPrestamo</th>
                        <th>Caracteristicas</th>
                        <th>TipoObra</th>
                        <th>ID_Resguardante</th>
                        <th>ID_Asignado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data as $row) : ?>
                        <tr>
                            <td><?php echo $row['ID_DatosGenerales']; ?></td>
                            <td><?php echo $row['Autores']; ?></td>
                            <td><?php echo $row['ObjetoObra']; ?></td>
                            <td><?php echo $row['Ubicacion']; ?></td>
                            <td><?php echo $row['NoInventario']; ?></td>
                            <td><?php echo $row['NoVale']; ?></td>
                            <td><?php echo $row['FechaPrestamo']; ?></td>
                            <td><?php echo $row['Caracteristicas']; ?></td>
                            <td><?php echo $row['TipoObra']; ?></td>
                            <td><?php echo $row['ID_Resguardante']; ?></td>
                            <td><?php echo $row['ID_Asignado']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php elseif (isset($error_message)) : ?>
            <p><?php echo $error_message; ?></p>
        <?php endif; ?>
    </div>
</body>

</html>