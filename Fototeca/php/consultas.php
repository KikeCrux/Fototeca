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
$username = "root";
$password = "Trompudo117";
$dbname = "fototeca_UAA";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Consulta para obtener los datos de la tabla Fototeca
$search = isset($_POST['search']) ? $_POST['search'] : '';

// Preparar y ejecutar la consulta de búsqueda si se ha proporcionado un término de búsqueda
if (!empty($search)) {
    $sql = "SELECT ID_Tecnica, NumeroInventario, ClaveTecnica, ProcesoFotografico, FondoColeccion, Formato, NumeroNegativoCopia, Tipo,
        FechaAsunto, FechaToma, LugarAsunto, LugarToma, Epoca, 
        Autor, AutorPrimigenio, AgenciaEstudio, EditorColeccionista, Lema, 
        Sello, Cuño, Firma, Etiqueta, Imprenta, Otro, 
        TituloOrigen, TituloCatalografico, TituloSerie, TemaPrincipal, Descriptores, 
        Personajes, InscripcionOriginal, Conjunto, Anotaciones, NumerosInterseccion, DocumentacionAsociada
        FROM Fototeca
        WHERE ID_Tecnica LIKE ? OR NumeroInventario LIKE ? OR ClaveTecnica LIKE ? OR ProcesoFotografico LIKE ? OR 
              FondoColeccion LIKE ? OR Formato LIKE ? OR NumeroNegativoCopia LIKE ? OR Tipo LIKE ? OR LugarToma LIKE ? OR Personajes LIKE ? OR
              Autor LIKE ? OR TituloOrigen LIKE ? OR Descriptores LIKE ? OR AutorPrimigenio LIKE ? OR  Imprenta LIKE ?";
    $stmt = $conn->prepare($sql);
    $searchTerm = "%" . $search . "%";
    $stmt->bind_param(
        "sssssssssssssss",
        $searchTerm,
        $searchTerm,
        $searchTerm,
        $searchTerm,
        $searchTerm,
        $searchTerm,
        $searchTerm,
        $searchTerm,
        $searchTerm,
        $searchTerm,
        $searchTerm,
        $searchTerm,
        $searchTerm,
        $searchTerm,
        $searchTerm
    );
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $sql = "SELECT ID_Tecnica, NumeroInventario, ClaveTecnica, ProcesoFotografico, FondoColeccion, Formato, NumeroNegativoCopia, Tipo,
        FechaAsunto, FechaToma, LugarAsunto, LugarToma, Epoca, 
        Autor, AutorPrimigenio, AgenciaEstudio, EditorColeccionista, Lema, 
        Sello, Cuño, Firma, Etiqueta, Imprenta, Otro, 
        TituloOrigen, TituloCatalografico, TituloSerie, TemaPrincipal, Descriptores, 
        Personajes, InscripcionOriginal, Conjunto, Anotaciones, NumerosInterseccion, DocumentacionAsociada
            FROM Fototeca";
    $result = $conn->query($sql);
}

// Cerrar la conexión a la base de datos
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consultas Datos</title>
    <link rel="stylesheet" href="../../bootstrap.min.css">
    <link rel="stylesheet" href="../css/login.css">
    <link rel="stylesheet" href="../css/tablas.css">
</head>

<body>
    <?php include 'header.php'; ?>
    <br>
    <h1 class="mb-4 text-center">Consulta de Datos Técnicos</h1>

    <div class="container mt-5">
        <div class="container mt-3">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="input-group mb-3">
                    <input type="text" name="search" class="form-control" placeholder="Buscar por ID Técnica, Número Inventario, Clave Técnica, etc." value="<?php echo htmlspecialchars($search); ?>">
                </div>
                <button class="btn btn-primary" type="submit">Buscar</button>
            </form>
        </div>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID Técnica</th>
                    <th>Número Inventario</th>
                    <th>Clave Técnica</th>
                    <th>Proceso Fotográfico</th>
                    <th>Fondo Colección</th>
                    <th>Formato</th>
                    <th># Negativo Copia</th>
                    <th>Tipo</th>
                    <th>Documentacion</th>
                    <th>Detalles</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row["ID_Tecnica"] . "</td>";
                        echo "<td>" . htmlspecialchars($row["NumeroInventario"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["ClaveTecnica"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["ProcesoFotografico"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["FondoColeccion"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["Formato"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["NumeroNegativoCopia"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["Tipo"]) . "</td>";
                        echo "<td>" . '<a href="pdf.php?id=' . $row['ID_Tecnica'] . ' ">Abrir PDF</a></td>';
                        echo '<td><button class="btn btn-action" data-bs-toggle="modal" data-bs-target="#detailsModal' . $row["ID_Tecnica"] . '">Ver más</button></td>';
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='9'>No hay datos disponibles.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Modals for each entry -->
    <?php
    if ($result->num_rows > 0) {
        $result->data_seek(0); // Resetea el puntero del resultado
        while ($row = $result->fetch_assoc()) {
            include 'detailsModalObras.php'; // Incluir el modal aquí
        }
    }
    ?>

    <script src="../../resources/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>