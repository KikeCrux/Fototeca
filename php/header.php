<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?></title>
    <link rel="stylesheet" href="../resources/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/header.css">
</head>

<body>
    <!-- Barra de navegación -->
    <nav class="navbar nav_teca navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">Fototeca Obras UAA</a>
            <?php
            // Verificar si el usuario ya está autenticado
            if (isset($_SESSION['username'])) {
                echo '<div class="navbar-text">';
                echo '<p class="p-usr mr-3 mb-0">Bienvenido ' . $_SESSION['username'] . '</p>';
                echo '<form class="form-inline" action="logout.php" method="post">';
                echo '<button class="btn btn-out" type="submit">Cerrar sesión</button>';
                echo '</form>';
                echo '</div>';
            }
            ?>
        </div>
    </nav>

    <script src="../resources/bootstrap/dist/js/bootstrap.min.js"></script>

</body>

</html>