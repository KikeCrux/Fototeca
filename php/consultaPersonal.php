<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];
require_once 'conexion_BD.php';

$search = isset($_POST['search']) ? $_POST['search'] : '';

// Preparar y ejecutar la consulta de búsqueda si se ha proporcionado un término de búsqueda
if (!empty($search)) {
    $sql = "SELECT ID_Personal, Nombre, PuestoDepartamento, Observaciones, Clave, Estatus FROM Personal WHERE Nombre LIKE ? OR Clave LIKE ?";
    $stmt = $conn->prepare($sql);
    $searchTerm = "%" . $search . "%";
    $stmt->bind_param("ss", $searchTerm, $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    // Consultar todos los registros si no hay término de búsqueda
    $sql = "SELECT ID_Personal, Nombre, PuestoDepartamento, Observaciones, Clave, Estatus FROM Personal";
    $result = $conn->query($sql);
}

// No cerrar la conexión aquí
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consultas Personal</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/login.css">
    <link rel="stylesheet" href="../css/tablas.css">
    <style>
        .status-circle {
            height: 10px;
            width: 10px;
            border-radius: 50%;
            display: inline-block;
        }
    </style>
</head>

<body>
    <?php include 'header.php'; ?>
    <br>
    <h1 class="text-center">Consultas de Personal</h1>

    <!-- Formulario de búsqueda -->
    <br>
    <div class="container">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <input type="text" name="search" class="form-control" placeholder="Buscar por nombre o clave" value="<?php echo htmlspecialchars($search); ?>">
                <br>
                <button type="submit" class="btn btn-primary">Buscar</button>
            </div>
        </form>
    </div>

    <div class="container mt-5">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Clave</th>
                    <th>Nombre</th>
                    <th>Puesto / Departamento</th>
                    <th>Observaciones</th>
                    <th>Estatus</th>
                    <th>Detalles</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $statusColor = ($row["Estatus"] == "Vigente") ? "green" : "red";
                        echo "<tr>";
                        echo "<td>" . $row["Clave"] . "</td>";
                        echo "<td>" . $row["Nombre"] . "</td>";
                        echo "<td>" . $row["PuestoDepartamento"] . "</td>";
                        echo "<td>" . $row["Observaciones"] . "</td>";
                        echo "<td><span class='status-circle' style='background-color: $statusColor;'></span> " . $row["Estatus"] . "</td>";
                        echo '<td><button class="btn btn-action" data-bs-toggle="modal" data-bs-target="#detailsModal' . $row["ID_Personal"] . '">Ver</button></td>';
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
            include 'obrasModalPesonal.php'; // Include your modal file here
        }
    }
    ?>

    <script src="../resources/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

<?php
// Cerrar la conexión aquí después de incluir todos los modales
$conn->close();
?>