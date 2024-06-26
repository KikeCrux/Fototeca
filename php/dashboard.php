<?php
// Inicia sesión para acceder y verificar la autenticidad del usuario
session_start();

// Verifica si el usuario está autenticado; de lo contrario, redirige a la página de inicio de sesión
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}
// Almacena el nombre de usuario de la sesión para su uso en el dashboard
$username = $_SESSION['username'];

?>

<!DOCTYPE html>
<html>

<head>
    <title>Dashboard</title> <!-- Título de la página -->
</head>

<body>
    <?php
    // Incluye el encabezado del sitio, que podría tener la navegación o contenido común
    $pageTitle = "Dashboard";
    include 'header.php';
    ?>

    <br>

    <?php
    // Incluye el menú específico del tipo de usuario Arte, que puede tener enlaces específicos a funciones o módulos
    include 'menuArte.php';
    ?>

</body>

</html>