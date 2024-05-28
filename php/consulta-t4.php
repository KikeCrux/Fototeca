<?php
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];
require_once 'conexion_BD.php';

// Inicializar la variable de búsqueda
$search = isset($_POST['search']) ? $_POST['search'] : '';

// Preparar y ejecutar la consulta de búsqueda si se ha proporcionado un término de búsqueda
if (!empty($search)) {
    $sql = "SELECT ID_Usuario, Usuario, TipoUsuario FROM Usuarios WHERE Usuario LIKE ? OR CAST(ID_Usuario AS CHAR) LIKE ?";
    $stmt = $conn->prepare($sql);
    $searchTerm = "%" . $search . "%";
    $stmt->bind_param("ss", $searchTerm, $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    // Consultar todos los registros si no hay término de búsqueda
    $sql = "SELECT ID_Usuario, Usuario, TipoUsuario FROM Usuarios";
    $result = $conn->query($sql);
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consultas Usuarios</title>
    <link rel="stylesheet" href="../css/login.css">
    <link rel="stylesheet" href="../css/tablas.css">
</head>

<body>
    <?php include 'header.php'; ?>
    <br>
    <h1 class="text-center">Consultas de Usuarios</h1>

    <!-- Formulario de búsqueda -->
    <br>
    <div class="container">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <input type="text" name="search" class="form-control" placeholder="Buscar por ID o usuario" value="<?php echo $search; ?>">
                <br>
                <button type="submit" class="btn btn-primary">Buscar</button>
            </div>
        </form>
    </div>

    <div class="container mt-5">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Usuario</th>
                    <th>Tipo de Usuario</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row["ID_Usuario"] . "</td>";
                        echo "<td>" . $row["Usuario"] . "</td>";
                        echo "<td>" . $row["TipoUsuario"] . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='3'>No hay registros</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

</body>

</html>