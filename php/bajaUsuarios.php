<?php
session_start();

// Verificar autenticación del usuario y redirigir si no está autenticado.
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

require_once 'conexion_BD.php';

$success_message = '';
$error_message = '';

// Captura el término de búsqueda si es que se envió uno
$search = isset($_POST['search']) ? $_POST['search'] : '';

// Eliminación de usuario si se proporciona un ID válido.
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id_usuario = $_GET['id'];

    $conn->autocommit(FALSE); // Desactiva el autocommit para manejar la transacción manualmente

    try {
        // Intenta eliminar primero registros dependientes en 'userlogs' para evitar errores de clave foránea
        $sql = "DELETE FROM UserLogs WHERE ID_Usuario = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();
        $stmt->close();

        // Eliminar el usuario
        $sql = "DELETE FROM Usuarios WHERE ID_Usuario = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();
        $stmt->close();

        $conn->commit(); // Confirma todas las operaciones de la transacción
        $success_message = "El usuario se eliminó correctamente.";
    } catch (Exception $e) {
        $conn->rollback(); // Revierte la transacción en caso de error
        $error_message = "Error al eliminar el usuario: " . $e->getMessage();
    }
    $conn->autocommit(TRUE); // Reactiva el autocommit
}

// Preparar consulta SQL basada en la búsqueda
if (!empty($search)) {
    $sql = "SELECT ID_Usuario, Usuario, TipoUsuario FROM Usuarios WHERE Usuario LIKE ? OR ID_Usuario LIKE ?";
    $stmt = $conn->prepare($sql);
    $searchTerm = "%" . $search . "%";
    $stmt->bind_param("ss", $searchTerm, $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
} else {
    // Consulta para obtener la lista de usuarios si no hay búsqueda.
    $sql = "SELECT ID_Usuario, Usuario, TipoUsuario FROM Usuarios";
    $result = $conn->query($sql);
}

// Cierra la conexión después de las operaciones.
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bajas Usuarios</title>
    <link rel="stylesheet" href="../css/login.css">
    <link rel="stylesheet" href="../css/tablas.css">
</head>

<body>
    <?php
    $pageTitle = "Bajas Usuarios";
    include 'header.php';

    // Muestra mensajes de éxito o error después de intentar eliminar un registro
    if (!empty($success_message)) : ?>
        <div id="successAlert" class="alert alert-success text-center"><?php echo $success_message; ?></div>
    <?php endif; ?>
    <?php if (!empty($error_message)) : ?>
        <div id="errorAlert" class="alert alert-danger text-center"><?php echo $error_message; ?></div>
    <?php endif; ?>

    <br>
    <h1 class="text-center">Bajas de Usuarios</h1>

    <!-- Formulario de búsqueda -->
    <div class="container mt-3">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <input type="text" class="form-control" name="search" placeholder="Buscar por nombre o ID..." value="<?php echo htmlspecialchars($search); ?>">
                <button type="submit" class="btn btn-primary mt-2">Buscar</button>
            </div>
        </form>
    </div>

    <div class="container mt-5">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Usuario</th>
                    <th>Tipo de Usuario</th>
                    <th>Acciones</th>
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
                        echo "<td><a href='bajaUsuarios.php?id=" . $row["ID_Usuario"] . "' class='btn btn-danger'>Eliminar</a></td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>No hay registros</td></tr>";
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
            } else {
                successAlert.style.display = "none";
            }
        }, 5000);
    </script>
</body>

</html>