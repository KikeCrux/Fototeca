<?php
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

require_once 'conexion_BD.php';

// Verificar si se recibió el ID del personal a cambiar
if (!isset($_GET['id'])) {
    header("Location: cambio-t1.php");
    exit();
}

// Obtener el ID del personal
$id_personal = $_GET['id'];

// Consultar los datos del personal a cambiar
$sql = "SELECT * FROM Personal WHERE ID_Personal = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_personal);
$stmt->execute();
$result = $stmt->get_result();

// Verificar si se encontró el personal
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $nombre = $row["Nombre"];
    $puesto = $row["PuestoDepartamento"];
    $observaciones = $row["Observaciones"];
    $clave = $row["Clave"];
    $estatus = $row["Estatus"];
} else {
    header("Location: cambio-t1.php");
    exit();
}

// Si se envió el formulario de cambios
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nuevo_nombre = $_POST['nombre'];
    $nuevo_puesto = $_POST['puesto'];
    $nuevas_observaciones = $_POST['observaciones'];
    $nueva_clave = $_POST['clave'];
    $nuevo_estatus = $_POST['estatus'];

    // Verificar si se está cambiando el estatus a "No Vigente" o "Jubilado"
    if (($nuevo_estatus == 'No Vigente' || $nuevo_estatus == 'Jubilado') && $estatus == 'Vigente') {
        // Verificar si el personal tiene obras asignadas como resguardante o asignado
        $checkSql = "SELECT COUNT(*) AS count FROM DatosGenerales WHERE ID_Resguardante = ? OR ID_Asignado = ?";
        $checkStmt = $conn->prepare($checkSql);
        $checkStmt->bind_param("ii", $id_personal, $id_personal);
        $checkStmt->execute();
        $checkResult = $checkStmt->get_result();
        $checkRow = $checkResult->fetch_assoc();

        if ($checkRow['count'] > 0) {
            $error_message = "No se puede cambiar el estatus porque el personal está asociado a obras como resguardante o asignado.";
        }
    }

    if (empty($error_message)) {
        // Actualizar el registro en la base de datos
        $sql_update = "UPDATE Personal SET Nombre=?, PuestoDepartamento=?, Observaciones=?, Clave=?, Estatus=? WHERE ID_Personal=?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("sssssi", $nuevo_nombre, $nuevo_puesto, $nuevas_observaciones, $nueva_clave, $nuevo_estatus, $id_personal);

        if ($stmt_update->execute()) {
            header("Location: cambio-t1.php?success_message=Cambios realizados exitosamente.");
            exit();
        } else {
            $error_message = "Error al realizar cambios: " . $stmt_update->error;
        }
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Procesar Cambios</title>
    <link rel="stylesheet" href="../css/login.css">
    <style>
        .form-group {
            margin-bottom: 20px;
            /* Espacio entre campos */
        }
    </style>
</head>

<body>
    <?php include 'header.php'; ?>
    <div class="container mt-5">
        <h1 class="text-center">Procesar Cambios de Personal</h1>
        <br>
        <?php if (!empty($error_message)) : ?>
            <div class="alert alert-danger text-center"><?php echo $error_message; ?></div>
        <?php endif; ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?id=' . $id_personal; ?>" method="post">
            <div class="form-group">
                <label for="nombre">Nuevo nombre:</label>
                <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo htmlspecialchars($nombre); ?>">
            </div>
            <div class="form-group">
                <label for="puesto">Nuevo puesto / departamento:</label>
                <input type="text" class="form-control" id="puesto" name="puesto" value="<?php echo htmlspecialchars($puesto); ?>">
            </div>
            <div class="form-group">
                <label for="observaciones">Nuevas observaciones:</label>
                <textarea class="form-control" id="observaciones" name="observaciones" rows="3"><?php echo htmlspecialchars($observaciones); ?></textarea>
            </div>
            <div class="form-group">
                <label for="clave">Clave:</label>
                <input type="text" class="form-control" id="clave" name="clave" value="<?php echo htmlspecialchars($clave); ?>">
            </div>
            <div class="form-group">
                <label for="estatus">Estatus:</label>
                <select class="form-control" id="estatus" name="estatus">
                    <option value="Vigente" <?php echo ($estatus == 'Vigente') ? 'selected' : ''; ?>>Vigente</option>
                    <option value="No Vigente" <?php echo ($estatus == 'No Vigente') ? 'selected' : ''; ?>>No Vigente</option>
                    <option value="Jubilado" <?php echo ($estatus == 'Jubilado') ? 'selected' : ''; ?>>Jubilado</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Guardar cambios</button>
        </form>
    </div>
</body>

</html>