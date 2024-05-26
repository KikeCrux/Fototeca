<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
$username = $_SESSION['username'];

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

    <?php include 'menu-t1.php'; ?>

</body>

</html>