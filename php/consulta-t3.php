<?php
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];
require_once 'conexion_BD.php';

$search = isset($_POST['search']) ? $_POST['search'] : '';

// Preparar y ejecutar la consulta de búsqueda si se ha proporcionado un término de búsqueda
if (!empty($search)) {
    $sql = "SELECT ID_DatosGenerales, Autores, ObjetoObra, Ubicacion, NoInventario, NoVale, FechaPrestamo, Caracteristicas, Observaciones, ImagenOficioVale, ImagenObra, ID_Resguardante, ID_Asignado FROM DatosGenerales
            WHERE Autores LIKE ? OR ObjetoObra LIKE ? OR ID_Resguardante LIKE ? OR ID_Asignado LIKE ?";
    $stmt = $conn->prepare($sql);
    $searchTerm = "%" . $search . "%";
    $stmt->bind_param("ssss", $searchTerm, $searchTerm, $searchTerm, $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    // Consultar todos los registros si no hay término de búsqueda
    $sql = "SELECT ID_DatosGenerales, Autores, ObjetoObra, Ubicacion, NoInventario, NoVale, FechaPrestamo, Caracteristicas, Observaciones, ImagenOficioVale, ImagenObra, ID_Resguardante, ID_Asignado FROM DatosGenerales";
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
                <input type="text" name="search" class="form-control" placeholder="Buscar por autor, objeto/obra, ID resguardante o ID asignado" value="<?php echo htmlspecialchars($search); ?>">
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
                    <th>ID Resguardante</th>
                    <th>ID Asignado</th>
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
                        echo "<td>" . htmlspecialchars($row["ID_Resguardante"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["ID_Asignado"]) . "</td>";
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

    <!-- Modals for each entry -->
    <?php
    if ($result->num_rows > 0) {
        $result->data_seek(0); // Reset result pointer
        while ($row = $result->fetch_assoc()) {
            include 'detailsModal.php'; // Include your modal file here
        }
    }
    ?>

    <script src="../resources/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function goBack() {
            window.history.back();
        }
    </script>
</body>

</html>