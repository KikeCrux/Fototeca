
<?php
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
// Función para limpiar y preparar datos para CSV
function cleanForCSV($value) {
    return '"' . str_replace('"', '""', $value) . '"';
}

// Verificar si se ha enviado el formulario
if (isset($_POST['export'])) {
    // Datos de conexión a la base de datos
    $servername = "localhost";
    $username = "root";
    $password = "Trompudo117";
    $dbname = "fototeca_uaa";

    // Crear conexión
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verificar conexión
    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    // Consulta SQL para obtener los datos de la tabla Fototeca
    $sql = "SELECT * FROM Fototeca";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        // Nombre del archivo CSV
        $filename = "fototeca_datos_exportados.csv";

        // Crear archivo temporal para escribir
        $tempFile = tmpfile();
        $csvContent = fopen("php://temp", 'w');

        // Escribir encabezados de columna en el archivo CSV
        $header = array(
            'ID_Tecnica', 'NumeroInventario', 'ClaveTecnica', 'ProcesoFotografico', 'FondoColeccion', 
            'Formato', 'NumeroNegativoCopia', 'Tipo', 'FechaAsunto', 'FechaToma', 'LugarAsunto', 
            'LugarToma', 'Epoca', 'Autor', 'AutorPrimigenio', 'AgenciaEstudio', 'EditorColeccionista', 
            'Lema', 'Sello', 'Cuño', 'Firma', 'Etiqueta', 'Imprenta', 'Otro', 'TituloOrigen', 
            'TituloCatalografico', 'TituloSerie', 'TemaPrincipal', 'Descriptores', 'Personajes', 
            'InscripcionOriginal', 'Conjunto', 'Anotaciones', 'NumerosInterseccion', 'DocumentacionAsociada'
        );
        fputcsv($csvContent, $header);

        // Escribir datos de las filas en el archivo CSV
        while ($row = $result->fetch_assoc()) {
            // Limpiar y preparar los datos para CSV
            $cleanRow = array_map('cleanForCSV', $row);
            fputcsv($csvContent, $cleanRow);
        }

        // Posicionar el puntero al inicio del archivo temporal
        fseek($csvContent, 0);

        // Establecer encabezados para forzar la descarga del archivo CSV
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        // Salida del contenido del archivo CSV
        fpassthru($csvContent);

        // Cerrar y eliminar archivo temporal
        fclose($csvContent);
        exit();
    } else {
        die("No se encontraron datos para exportar.");
    }

    $conn->close();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Exportar Datos a CSV</title>
    <link href="../../resources/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    
</head>
<body>
<?php
    $pageTitle = "Altas ";
    include 'header.php';
    echo '<br>';
    ?>
    <div class="container">
        <div class="card">
            <div class="card-body">
                <h1 class="card-title text-center mb-4">Exportar Datos a CSV</h1>
                <form action="" method="post">
                    <div class="text-center">
                        <button type="submit" name="export" class="btn btn-primary btn-lg">Exportar a CSV</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
