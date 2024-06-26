<?php
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];
require_once 'conexion_BD.php';

$success_message = '';
$error_message = '';

// Maneja la eliminación de registros
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $id_personal = $_GET['id'];

    // Verificar si el personal tiene obras como resguardante o asignado
    $checkSql = "SELECT COUNT(*) AS count FROM DatosGenerales WHERE ID_Resguardante = ? OR ID_Asignado = ?";
    $checkStmt = $conn->prepare($checkSql);
    $checkStmt->bind_param("ii", $id_personal, $id_personal);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();
    $checkRow = $checkResult->fetch_assoc();

    if ($checkRow['count'] > 0) {
        $error_message = "No se puede eliminar el registro porque está asociado a obras como resguardante o asignado.";
    } else {
        $sql = "DELETE FROM Personal WHERE ID_Personal = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_personal);
        if ($stmt->execute()) {
            $success_message = "El registro se eliminó correctamente.";
        } else {
            $error_message = "Error al eliminar el registro: " . $stmt->error;
        }
    }
}

// Captura el término de búsqueda si es que se envió uno
$search = isset($_POST['search']) ? $_POST['search'] : '';

// Preparar consulta SQL basada en la búsqueda
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
    <title>Bajas Personal</title>
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

    <?php if (!empty($success_message)) : ?>
        <div id="successAlert" class="alert alert-success text-center"><?php echo $success_message; ?></div>
    <?php endif; ?>
    <?php if (!empty($error_message)) : ?>
        <div id="errorAlert" class="alert alert-danger text-center"><?php echo $error_message; ?></div>
    <?php endif; ?>

    <br>
    <h1 class="text-center">Bajas de Personal</h1>

    <!-- Formulario de búsqueda -->
    <br>
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
        <table class="table table-striped table-hover">
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
                        echo "<td><a href='?action=delete&id=" . $row["ID_Personal"] . "' class='btn btn-danger' onclick='return confirm(\"¿Está seguro de querer eliminar este registro?\")'>Eliminar</a></td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>No hay registros para mostrar</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <script>
        setTimeout(function() {
            var successAlert = document.getElementById("successAlert");
            var errorAlert = document.getElementById("errorAlert");
            if (errorAlert) {
                errorAlert.style.display = "none";
            } else if (successAlert) {
                successAlert.style.display = "none";
            }
        }, 5000);
    </script>

</body>

</html>