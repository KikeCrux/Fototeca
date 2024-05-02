<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
$username = $_SESSION['username'];

// Imprimir el tipo de usuario en el registro de errores
error_log('Tipo de usuario: ' . $_SESSION['tipoUsuario']);

?>

<!DOCTYPE html>
<html>

<head>
    <title>Dashboard</title>
</head>

<body>
    <?php
    $pageTitle = "Dashborard";
    include 'header.php';
    ?>

    <br>

    <?php include 'menu.php'; ?>

    <!-- Otro contenido del dashboard -->



</body>

</html>