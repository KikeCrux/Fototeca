<?php
// Inicia sesión y verifica si el usuario está autenticado
session_start();
if (!isset($_SESSION['username'])) {
    // Si no hay una sesión activa, redirige al usuario al formulario de inicio de sesión
    header("Location: index.php");
    exit();
}
// Almacena el nombre de usuario autenticado para su uso en el script
$username = $_SESSION['username'];

?>

<!DOCTYPE html>
<html>

<head>
    <title>Dashboard</title>
</head>

<body>
    <?php
    // Incluye el archivo de cabecera que puede contener la navegación o elementos visuales comunes
    $pageTitle = "Dashboard-Admin";
    include 'header.php';
    ?>

    <br>
    <?php
    // Incluye el menú específico para administradores, controlando el acceso a funcionalidades específicas
    include 'menuAdmin.php';
    ?>

</body>

</html>