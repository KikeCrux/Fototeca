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
    <link rel="stylesheet" href="../css/login.css">
    <link rel="stylesheet" href="../css/tablas.css">
</head>

<body>

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
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='3'>No hay datos disponibles.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <br>
            <!-- Tabla Clave -->
            <div class="card mb-4">
                <div class="card-header">
                    <strong>Claves</strong>
                </div>
                <div class="card-body">
                    <table class="table table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th>ID Cultural</th>
                                <th>ID Técnica</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($resultClave->num_rows > 0) {
                                while ($row = $resultClave->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td>" . $row["ID_Cultural"] . "</td>";
                                    echo "<td>" . $row["ID_Tecnica"] . "</td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='2'>No hay datos disponibles.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Tabla Datacion -->
            <div class="card mb-4">
                <div class="card-header">
                    <strong>Datación</strong>
                </div>
                <div class="card-body">
                    <table class="table table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th>ID Datación</th>
                                <th>ID Técnica</th>
                                <th>Fecha Asunto</th>
                                <th>Fecha Toma</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($resultDatacion->num_rows > 0) {
                                while ($row = $resultDatacion->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td>" . $row["ID_Datacion"] . "</td>";
                                    echo "<td>" . $row["ID_Tecnica"] . "</td>";
                                    echo "<td>" . $row["FechaAsunto"] . "</td>";
                                    echo "<td>" . $row["FechaToma"] . "</td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='4'>No hay datos disponibles.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Tabla Ubicacion Geografica -->
            <div class="card mb-4">
                <div class="card-header">
                    <strong>Ubicacion Geografica</strong>
                </div>
                <div class="card-body">
                    <table class="table table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th>ID Ubicacion</th>
                                <th>ID Técnica</th>
                                <th>Lugar Asunto</th>
                                <th>Lugar Toma</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($resultUbicacionGeografica->num_rows > 0) {
                                while ($row = $resultUbicacionGeografica->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td>" . $row["ID_Ubicacion"] . "</td>";
                                    echo "<td>" . $row["ID_Tecnica"] . "</td>";
                                    echo "<td>" . $row["LugarAsunto"] . "</td>";
                                    echo "<td>" . $row["LugarToma"] . "</td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='4'>No hay datos disponibles.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Tabla Epocario -->
            <div class="card mb-4">
                <div class="card-header">
                    <strong>Epocario</strong>
                </div>
                <div class="card-body">
                    <table class="table table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th>ID Epoca</th>
                                <th>ID Técnica</th>
                                <th>Epoca</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($resultEpocario->num_rows > 0) {
                                while ($row = $resultEpocario->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td>" . $row["ID_Epoca"] . "</td>";
                                    echo "<td>" . $row["ID_Tecnica"] . "</td>";
                                    echo "<td>" . $row["Epoca"] . "</td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='4'>No hay datos disponibles.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Tabla Autoria -->
            <div class="card mb-4">
                <div class="card-header">
                    <strong>Autoria</strong>
                </div>
                <div class="card-body">
                    <table class="table table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th>ID Autoria</th>
                                <th>ID Técnica</th>
                                <th>Autor</th>
                                <th>Autor Primigenio</th>
                                <th>Agencia Estudio</th>
                                <th>Editor Coleccionista</th>
                                <th>Lema</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($resultAutoria->num_rows > 0) {
                                while ($row = $resultAutoria->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td>" . $row["ID_Autoria"] . "</td>";
                                    echo "<td>" . $row["ID_Tecnica"] . "</td>";
                                    echo "<td>" . $row["Autor"] . "</td>";
                                    echo "<td>" . $row["AutorPrimigenio"] . "</td>";
                                    echo "<td>" . $row["AgenciaEstudio"] . "</td>";
                                    echo "<td>" . $row["EditorColeccionista"] . "</td>";
                                    echo "<td>" . $row["Lema"] . "</td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='4'>No hay datos disponibles.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Tabla Indicativo -->
            <div class="card mb-4">
                <div class="card-header">
                    <strong>Indicativo</strong>
                </div>
                <div class="card-body">
                    <table class="table table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th>ID Indicativo</th>
                                <th>ID Auditoria</th>
                                <th>Sello</th>
                                <th>Cuño</th>
                                <th>Firma</th>
                                <th>Etiqueta</th>
                                <th>Imprenta</th>
                                <th>Otro</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($resultIndicativo->num_rows > 0) {
                                while ($row = $resultIndicativo->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td>" . $row["ID_Indicativo"] . "</td>";
                                    echo "<td>" . $row["ID_Autoria"] . "</td>";
                                    echo "<td>" . $row["Sello"] . "</td>";
                                    echo "<td>" . $row["Cuño"] . "</td>";
                                    echo "<td>" . $row["Firma"] . "</td>";
                                    echo "<td>" . $row["Etiqueta"] . "</td>";
                                    echo "<td>" . $row["Imprenta"] . "</td>";
                                    echo "<td>" . $row["Otro"] . "</td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='4'>No hay datos disponibles.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Tabla Denominacion -->
            <div class="card mb-4">
                <div class="card-header">
                    <strong>Denominacion</strong>
                </div>
                <div class="card-body">
                    <table class="table table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th>ID Denominacion</th>
                                <th>ID Tecnica</th>
                                <th>Titulo Origen</th>
                                <th>Titulo Catalografico</th>
                                <th>Titulo Serie</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($resultDenominacion->num_rows > 0) {
                                while ($row = $resultDenominacion->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td>" . $row["ID_Denominacion"] . "</td>";
                                    echo "<td>" . $row["ID_Tecnica"] . "</td>";
                                    echo "<td>" . $row["TituloOrigen"] . "</td>";
                                    echo "<td>" . $row["TituloCatalografico"] . "</td>";
                                    echo "<td>" . $row["TituloSerie"] . "</td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='4'>No hay datos disponibles.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Tabla Descriptores -->
            <div class="card mb-4">
                <div class="card-header">
                    <strong>Descriptores</strong>
                </div>
                <div class="card-body">
                    <table class="table table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th>ID Descriptores</th>
                                <th>ID Tecnica</th>
                                <th>Tema Principal</th>
                                <th>Descriptores</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($resultDescriptores->num_rows > 0) {
                                while ($row = $resultDescriptores->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td>" . $row["ID_Descriptores"] . "</td>";
                                    echo "<td>" . $row["ID_Tecnica"] . "</td>";
                                    echo "<td>" . $row["TemaPrincipal"] . "</td>";
                                    echo "<td>" . $row["Descriptores"] . "</td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='4'>No hay datos disponibles.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Tabla Protagonistas -->
            <div class="card mb-4">
                <div class="card-header">
                    <strong>Protagonistas</strong>
                </div>
                <div class="card-body">
                    <table class="table table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th>ID Protagonistas</th>
                                <th>ID Tecnica</th>
                                <th>Personajes</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($resultProtagonistas->num_rows > 0) {
                                while ($row = $resultProtagonistas->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td>" . $row["ID_Protagonistas"] . "</td>";
                                    echo "<td>" . $row["ID_Tecnica"] . "</td>";
                                    echo "<td>" . $row["Personajes"] . "</td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='4'>No hay datos disponibles.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Tabla Observaciones -->
            <div class="card mb-4">
                <div class="card-header">
                    <strong>Observaciones</strong>
                </div>
                <div class="card-body">
                    <table class="table table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th>ID Observaciones</th>
                                <th>ID Tecnica</th>
                                <th>Inscripcion Original</th>
                                <th>Conjunto</th>
                                <th>Anotaciones</th>
                                <th>Numeros Interseccion</th>
                                <th>Documentacion Asociada</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($resultObservaciones->num_rows > 0) {
                                while ($row = $resultObservaciones->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td>" . $row["ID_Observaciones"] . "</td>";
                                    echo "<td>" . $row["ID_Tecnica"] . "</td>";
                                    echo "<td>" . $row["InscripcionOriginal"] . "</td>";
                                    echo "<td>" . $row["Conjunto"] . "</td>";
                                    echo "<td>" . $row["Anotaciones"] . "</td>";
                                    echo "<td>" . $row["NumerosInterseccion"] . "</td>";
                                    echo "<td>" . $row["DocumentacionAsociada"] . "</td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='4'>No hay datos disponibles.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

    </body>

</html>