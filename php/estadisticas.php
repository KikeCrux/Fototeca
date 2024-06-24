<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];
require_once 'conexion_BD.php';

// Variables para almacenar los datos de las gráficas
$tipoObraData = [];
$resguardanteData = [];
$asignadoData = [];

// Consulta para obtener las opciones de TipoObra
$sqlTipoObraOptions = "SELECT DISTINCT TipoObra FROM DatosGenerales";
$resultTipoObraOptions = $conn->query($sqlTipoObraOptions);
$tipoObraOptions = [];
while ($row = $resultTipoObraOptions->fetch_assoc()) {
    $tipoObraOptions[] = $row['TipoObra'];
}

// Consulta para obtener las opciones de Personal
$sqlPersonalOptions = "SELECT ID_Personal, Nombre, Clave FROM Personal WHERE Estatus = 'Vigente'";
$resultPersonalOptions = $conn->query($sqlPersonalOptions);
$personalOptions = [];
while ($row = $resultPersonalOptions->fetch_assoc()) {
    $personalOptions[] = $row;
}

// Procesar la selección del usuario
$selectedTipoObra = isset($_POST['tipoObra']) ? $_POST['tipoObra'] : '';
$selectedResguardante = isset($_POST['resguardante']) ? $_POST['resguardante'] : '';
$selectedAsignado = isset($_POST['asignado']) ? $_POST['asignado'] : '';

$whereClauses = [];
$params = [];

if ($selectedTipoObra !== '') {
    $whereClauses[] = "TipoObra = ?";
    $params[] = $selectedTipoObra;
}

if ($selectedResguardante !== '') {
    $whereClauses[] = "ID_Resguardante = ?";
    $params[] = $selectedResguardante;
}

if ($selectedAsignado !== '') {
    $whereClauses[] = "ID_Asignado = ?";
    $params[] = $selectedAsignado;
}

$whereSql = '';
if (count($whereClauses) > 0) {
    $whereSql = 'WHERE ' . implode(' AND ', $whereClauses);
}

// Consulta para obtener estadísticas de TipoObra
$sqlTipoObra = "SELECT TipoObra, COUNT(*) as count FROM DatosGenerales $whereSql GROUP BY TipoObra";
$stmt = $conn->prepare($sqlTipoObra);
if (count($params) > 0) {
    $stmt->bind_param(str_repeat('s', count($params)), ...$params);
}
$stmt->execute();
$resultTipoObra = $stmt->get_result();
while ($row = $resultTipoObra->fetch_assoc()) {
    $tipoObraData[] = $row;
}

// Consulta para obtener estadísticas de resguardante y asignado
$sqlResguardante = "SELECT p.Nombre as nombre, COUNT(dg.ID_DatosGenerales) as count FROM DatosGenerales dg 
                    JOIN Personal p ON dg.ID_Resguardante = p.ID_Personal $whereSql GROUP BY p.Nombre";
$stmt = $conn->prepare($sqlResguardante);
if (count($params) > 0) {
    $stmt->bind_param(str_repeat('s', count($params)), ...$params);
}
$stmt->execute();
$resultResguardante = $stmt->get_result();
while ($row = $resultResguardante->fetch_assoc()) {
    $resguardanteData[] = $row;
}

$sqlAsignado = "SELECT p.Nombre as nombre, COUNT(dg.ID_DatosGenerales) as count FROM DatosGenerales dg 
                JOIN Personal p ON dg.ID_Asignado = p.ID_Personal $whereSql GROUP BY p.Nombre";
$stmt = $conn->prepare($sqlAsignado);
if (count($params) > 0) {
    $stmt->bind_param(str_repeat('s', count($params)), ...$params);
}
$stmt->execute();
$resultAsignado = $stmt->get_result();
while ($row = $resultAsignado->fetch_assoc()) {
    $asignadoData[] = $row;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estadísticas</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <script src="../resources/chart.min.js"></script> <!-- Asegúrate de ajustar esta ruta -->
</head>

<body>
    <?php include 'header.php'; ?>
    <div class="container mt-4">
        <h1 class="mb-4 text-center">Estadísticas</h1>

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
                    <button type="submit" class="btn btn-primary">Filtrar</button>
                </form>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-header">
                        <strong>Estadísticas por Tipo de Obra</strong>
                    </div>
                    <div class="card-body">
                        <canvas id="tipoObraChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-header">
                        <strong>Estadísticas por Resguardante</strong>
                    </div>
                    <div class="card-body">
                        <canvas id="resguardanteChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-header">
                        <strong>Estadísticas por Asignado</strong>
                    </div>
                    <div class="card-body">
                        <canvas id="asignadoChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Datos para la gráfica de Tipo de Obra
            const tipoObraData = <?php echo json_encode($tipoObraData); ?>;
            const tipoObraLabels = tipoObraData.map(item => item.TipoObra);
            const tipoObraCounts = tipoObraData.map(item => item.count);

            const ctxTipoObra = document.getElementById('tipoObraChart').getContext('2d');
            new Chart(ctxTipoObra, {
                type: 'bar',
                data: {
                    labels: tipoObraLabels,
                    datasets: [{
                        label: 'Cantidad',
                        data: tipoObraCounts,
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1 // Configurar el tamaño del paso a 1 para mostrar solo números enteros
                            }
                        }
                    }
                }
            });

            // Datos para la gráfica de Resguardante
            const resguardanteData = <?php echo json_encode($resguardanteData); ?>;
            const resguardanteLabels = resguardanteData.map(item => item.nombre);
            const resguardanteCounts = resguardanteData.map(item => item.count);

            const ctxResguardante = document.getElementById('resguardanteChart').getContext('2d');
            new Chart(ctxResguardante, {
                type: 'bar',
                data: {
                    labels: resguardanteLabels,
                    datasets: [{
                        label: 'Cantidad',
                        data: resguardanteCounts,
                        backgroundColor: 'rgba(153, 102, 255, 0.2)',
                        borderColor: 'rgba(153, 102, 255, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1 // Configurar el tamaño del paso a 1 para mostrar solo números enteros
                            }
                        }
                    }
                }
            });

            // Datos para la gráfica de Asignado
            const asignadoData = <?php echo json_encode($asignadoData); ?>;
            const asignadoLabels = asignadoData.map(item => item.nombre);
            const asignadoCounts = asignadoData.map(item => item.count);

            const ctxAsignado = document.getElementById('asignadoChart').getContext('2d');
            new Chart(ctxAsignado, {
                type: 'bar',
                data: {
                    labels: asignadoLabels,
                    datasets: [{
                        label: 'Cantidad',
                        data: asignadoCounts,
                        backgroundColor: 'rgba(255, 159, 64, 0.2)',
                        borderColor: 'rgba(255, 159, 64, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1 // Configurar el tamaño del paso a 1 para mostrar solo números enteros
                            }
                        }
                    }
                }
            });
        });
    </script>
</body>

</html>