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
$servername = "localhost";
$db_username = "root";
$db_password = "Sandia2016.!";
$dbname = "fototeca_uaa";

$conn = new mysqli($servername, $db_username, $db_password, $dbname);
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Consultas para obtener los datos de cada tabla relacionada
$sqlTecnica = "SELECT * FROM SeccionTecnica";
$resultTecnica = $conn->query($sqlTecnica);

$sqlClave = "SELECT * FROM Clave";
$resultClave = $conn->query($sqlClave);

$sqlDatacion = "SELECT * FROM Datacion";
$resultDatacion = $conn->query($sqlDatacion);

$sqlUbicacionGeografica = "SELECT * FROM UbicacionGeografica";
$resultUbicacionGeografica = $conn->query($sqlUbicacionGeografica);

$sqlEpocario = "SELECT * FROM Epocario";
$resultEpocario = $conn->query($sqlEpocario);

$sqlAutoria = "SELECT * FROM Autoria";
$resultAutoria = $conn->query($sqlAutoria);

$sqlIndicativo = "SELECT * FROM Indicativo";
$resultIndicativo = $conn->query($sqlIndicativo);

$sqlDenominacion = "SELECT * FROM Denominacion";
$resultDenominacion = $conn->query($sqlDenominacion);

$sqlDescriptores = "SELECT * FROM Descriptores";
$resultDescriptores = $conn->query($sqlDescriptores);

$sqlProtagonistas = "SELECT * FROM Protagonistas";
$resultProtagonistas = $conn->query($sqlProtagonistas);

$sqlObservaciones = "SELECT * FROM Observaciones";
$resultObservaciones = $conn->query($sqlObservaciones);



// Cerrar la conexión a la base de datos
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulta de Datos Técnicos</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/login.css">
    <link rel="stylesheet" href="../css/tablas.css">
</head>

<body>
    <?php include 'header.php'; ?>
    <div class="container mt-4">
        <h1 class="mb-4 text-center">Consulta de Datos Técnicos</h1>

        <div class="card">
            <div class="card-header">
                <strong>Sección Técnica</strong>
            </div>
            <div class="card-body">
                <table class="table table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th>ID Técnica</th>
                            <th>Número Inventario</th>
                            <th>Clave Técnica</th>
                            <th>Proceso Fotografico</th>
                            <th>Fondo Coleccion</th>
                            <th>Formato</th>
                            <th># Negativo Copia</th>
                            <th>Tipo</th>
                            <th>Detalles</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . $row["ID_Tecnica"] . "</td>";
                                echo "<td>" . $row["NumeroInventario"] . "</td>";
                                echo "<td>" . $row["ClaveTecnica"] . "</td>";
                                echo "<td>" . $row["ProcesoFotografico"] . "</td>";
                                echo "<td>" . $row["FondoColeccion"] . "</td>";
                                echo "<td>" . $row["Formato"] . "</td>";
                                echo "<td>" . $row["NumeroNegativoCopia"] . "</td>";
                                echo "<td>" . $row["Tipo"] . "</td>";
                                echo '<td><button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#detailsModal' . $row["ID_Tecnica"] . '">Ver más</button></td>';
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='9'>No hay datos disponibles.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>