<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

$username = $_SESSION['username'];
require_once 'conexion_BD.php';

$search = isset($_POST['search']) ? $_POST['search'] : '';
$searchID = isset($_POST['searchID']) ? $_POST['searchID'] : '';

// Preparar y ejecutar la consulta de búsqueda si se ha proporcionado un término de búsqueda o ID de obra
if (!empty($search) || !empty($searchID)) {
    $sql = "SELECT dg.ID_DatosGenerales, dg.Autores, dg.ObjetoObra, dg.Ubicacion, dg.NoInventario, dg.NoVale, 
            dg.FechaPrestamo, dg.Caracteristicas, dg.Observaciones, dg.ImagenOficioVale, dg.ImagenObra, dg.TipoObra,
            p1.Clave as ClaveResguardante, p2.Clave as ClaveAsignado, p1.Nombre as NombreResguardante, p2.Nombre as NombreAsignado
            FROM DatosGenerales dg
            LEFT JOIN Personal p1 ON dg.ID_Resguardante = p1.ID_Personal
            LEFT JOIN Personal p2 ON dg.ID_Asignado = p2.ID_Personal
            WHERE (dg.Autores LIKE ? OR dg.ObjetoObra LIKE ? OR p1.Clave LIKE ? OR p2.Clave LIKE ?)
            AND (dg.ID_DatosGenerales = ? OR ? = '')";
    $stmt = $conn->prepare($sql);
    $searchTerm = "%" . $search . "%";
    $stmt->bind_param("ssssss", $searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchID, $searchID);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $sql = "SELECT dg.ID_DatosGenerales, dg.Autores, dg.ObjetoObra, dg.Ubicacion, dg.NoInventario, dg.NoVale, 
            dg.FechaPrestamo, dg.Caracteristicas, dg.Observaciones, dg.ImagenOficioVale, dg.ImagenObra, dg.TipoObra,
            p1.Clave as ClaveResguardante, p2.Clave as ClaveAsignado, p1.Nombre as NombreResguardante, p2.Nombre as NombreAsignado
            FROM DatosGenerales dg
            LEFT JOIN Personal p1 ON dg.ID_Resguardante = p1.ID_Personal
            LEFT JOIN Personal p2 ON dg.ID_Asignado = p2.ID_Personal";
    $result = $conn->query($sql);
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consultas Datos Generales</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/login.css">
    <link rel="stylesheet" href="../css/tablas.css">
</head>

<body>
    <?php include 'header.php'; ?>
    <br>
    <h1 class="text-center">Consultas de Datos Generales</h1>

    <!-- Formulario de búsqueda -->
    <div class="container mt-3">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="input-group mb-3">
                <input type="text" name="search" class="form-control" placeholder="Buscar por autor, objeto/obra, clave resguardante o clave asignado" value="<?php echo htmlspecialchars($search); ?>">
                <input type="text" name="searchID" class="form-control" placeholder="Buscar por ID de obra" value="<?php echo htmlspecialchars($searchID); ?>">
            </div>
            <button class="btn btn-primary" type="submit">Buscar</button>
        </form>
    </div>

    <div class="container mt-5">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Autor(es)</th>
                    <th>Objeto / Obra</th>
                    <th>Clave Resguardante</th>
                    <th>Clave Asignado</th>
                    <th>Detalles</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row["ID_DatosGenerales"] . "</td>";
                        echo "<td>" . htmlspecialchars($row["Autores"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["ObjetoObra"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["ClaveResguardante"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["ClaveAsignado"]) . "</td>";
                        echo '<td><button class="btn btn-action" data-bs-toggle="modal" data-bs-target="#detailsModal' . $row["ID_DatosGenerales"] . '">Ver</button></td>';
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>No hay registros</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Modals por cada renglon -->
    <?php
    if ($result->num_rows > 0) {
        $result->data_seek(0); // Resetea el puntero del resultado
        while ($row = $result->fetch_assoc()) {
            include 'obrasModalDatosGen.php'; // Inclucion del modal
        }
    }
    ?>

    <script src="../resources/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>