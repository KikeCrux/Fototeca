<?php
session_start();

// Verificar autenticidad del usuario
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

$username = $_SESSION['username'];

require_once 'conexion_BD.php';

// Captura la búsqueda si existe
$search = isset($_POST['search']) ? $_POST['search'] : '';

// Prepara la consulta SQL basada en la búsqueda
$sql = "SELECT ID_Personal, Nombre, PuestoDepartamento, Observaciones, Clave, Estatus FROM Personal
        WHERE Nombre LIKE ? OR Clave LIKE ?";
$stmt = $conn->prepare($sql);
$searchTerm = '%' . $search . '%';
$stmt->bind_param("ss", $searchTerm, $searchTerm);
$stmt->execute();
$result = $stmt->get_result();

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cambios Personal</title>
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
    <h1 class="text-center">Cambios de Personal</h1>

    <!-- Formulario de búsqueda -->
    <div class="container">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <input type="text" name="search" class="form-control" placeholder="Buscar por nombre o clave" value="<?php echo $search; ?>">
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
                    <th>Acciones</th>
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
                        echo "<td><a href='procesarCambiosPersonal.php?id=" . $row["ID_Personal"] . "' class='btn btn-primary'>Editar</a></td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>No se encontraron registros</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>

</html>