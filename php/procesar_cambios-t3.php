<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

require_once 'conexion_BD.php';

if (!isset($_GET['id'])) {
    header("Location: cambio-t3.php");
    exit();
}

$id_DatosGenerales = $_GET['id'];

$sql = "SELECT * FROM DatosGenerales WHERE ID_DatosGenerales = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_DatosGenerales);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
} else {
    header("Location: cambio-t3.php");
    exit();
}

// Consulta para obtener los datos de personal para los selectores
$personalQuery = "SELECT ID_Personal, Clave, Nombre FROM Personal WHERE Estatus = 'Vigente'";
$personalResult = $conn->query($personalQuery);

// Si se envió el formulario de cambios
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nuevos_autores = $_POST['autores'];
    $nuevo_objeto = $_POST['objeto'];
    $nueva_ubicacion = $_POST['ubicacion'];
    $nuevo_inventario = $_POST['inventario'];
    $nuevo_vale = $_POST['vale'];
    $nueva_fechprestamo = $_POST['fechprestamo'];
    $nuevas_caracteristicas = $_POST['caracteristicas'];
    $nuevas_observaciones = $_POST['observaciones'];
    $nuevo_tipo_obra = $_POST['tipoObra'];
    $nuevo_resguardante = $_POST['idResguardante'];
    $nuevo_asignado = $_POST['idAsignado'];

    $sql = "UPDATE DatosGenerales SET Autores=?, ObjetoObra=?, Ubicacion=?, NoInventario=?, NoVale=?, FechaPrestamo=?, 
            Caracteristicas=?, Observaciones=?, TipoObra=?, ID_Resguardante=?, ID_Asignado=? WHERE ID_DatosGenerales=?";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        $error_message = "Error al preparar la consulta: " . $conn->error;
    } else {
        $stmt->bind_param(
            "ssssssssiiii",
            $nuevos_autores,
            $nuevo_objeto,
            $nueva_ubicacion,
            $nuevo_inventario,
            $nuevo_vale,
            $nueva_fechprestamo,
            $nuevas_caracteristicas,
            $nuevas_observaciones,
            $nuevo_tipo_obra,
            $nuevo_resguardante,
            $nuevo_asignado,
            $id_DatosGenerales
        );

        if ($stmt->execute()) {
            header("Location: cambio-t3.php?success_message=Cambios realizados exitosamente.");
            exit();
        } else {
            $error_message = "Error al realizar cambios: " . $stmt->error;
        }
        $stmt->close();
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
        }
    </style>
</head>

<body>
    <?php
    $pageTitle = "Procesar Cambios";
    include 'header.php';
    echo '<br>';
    ?>

    <div class="container mt-5">
        <h1 class="text-center">Procesar Cambios de Datos Generales</h1>
        <br>
        <?php if (!empty($error_message)) : ?>
            <div class="alert alert-danger text-center"><?php echo $error_message; ?></div>
        <?php endif; ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?id=' . $id_DatosGenerales; ?>" method="post">
            <div class="form-group">
                <label for="autores">Nuevos Autor(ES):</label>
                <input type="text" class="form-control" id="autores" name="autores" value="<?php echo htmlspecialchars($row['Autores']); ?>" required>
            </div>
            <div class="form-group">
                <label for="objeto">Nuevo objeto / obra:</label>
                <input type="text" class="form-control" id="objeto" name="objeto" value="<?php echo htmlspecialchars($row['ObjetoObra']); ?>">
            </div>
            <div class="form-group">
                <label for="ubicacion">Nueva ubicacion:</label>
                <input type="text" class="form-control" id="ubicacion" name="ubicacion" value="<?php echo htmlspecialchars($row['Ubicacion']); ?>" required>
            </div>
            <div class="form-group">
                <label for="inventario">Nuevo inventario:</label>
                <input type="text" class="form-control" id="inventario" name="inventario" value="<?php echo htmlspecialchars($row['NoInventario']); ?>" required>
            </div>
            <div class="form-group">
                <label for="vale">Nuevo No. Vale:</label>
                <input type="text" class="form-control" id="vale" name="vale" value="<?php echo htmlspecialchars($row['NoVale']); ?>" required>
            </div>
            <div class="form-group">
                <label for="fechprestamo">Nueva Fecha (Prestamo):</label>
                <input type="date" class="form-control" id="fechprestamo" name="fechprestamo" value="<?php echo htmlspecialchars($row['FechaPrestamo']); ?>" required>
            </div>
            <div class="form-group">
                <label for="caracteristicas">Nuevas caracteristicas:</label>
                <textarea class="form-control" id="caracteristicas" name="caracteristicas" rows="3"><?php echo htmlspecialchars($row['Caracteristicas']); ?></textarea>
            </div>
            <div class="form-group">
                <label for="observaciones">Nuevas observaciones:</label>
                <textarea class="form-control" id="observaciones" name="observaciones" rows="3"><?php echo htmlspecialchars($row['Observaciones']); ?></textarea>
            </div>
            <div class="form-group">
                <label for="tipoObra">Nuevo Tipo Obra:</label>
                <input type="text" class="form-control" id="tipoObra" name="tipoObra" value="<?php echo htmlspecialchars($row['TipoObra']); ?>" required>
            </div>
            <div class="form-group">
                <label for="idResguardante">Nuevo ID Resguardante:</label>
                <select class="form-control" id="idResguardante" name="idResguardante">
                    <?php while ($personal = $personalResult->fetch_assoc()) : ?>
                        <option value="<?php echo $personal['ID_Personal']; ?>" <?php if ($row['ID_Resguardante'] == $personal['ID_Personal']) echo 'selected'; ?>>
                            <?php echo $personal['Clave'] . " - " . $personal['Nombre']; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="idAsignado">Nuevo ID Asignado:</label>
                <select class="form-control" id="idAsignado" name="idAsignado">
                    <?php
                    // Re-query the same personal data for asignado options
                    $personalResult->data_seek(0);
                    while ($personal = $personalResult->fetch_assoc()) : ?>
                        <option value="<?php echo $personal['ID_Personal']; ?>" <?php if ($row['ID_Asignado'] == $personal['ID_Personal']) echo 'selected'; ?>>
                            <?php echo $personal['Clave'] . " - " . $personal['Nombre']; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <br>
            <button type="submit" class="btn btn-primary">Guardar cambios</button>
        </form>
    </div>
</body>

</html>