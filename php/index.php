<?php
// Establecer la zona horaria a Ciudad de México (CDMX)
date_default_timezone_set('America/Mexico_City');

session_start();

// Verificar si el usuario ya está autenticado
if (isset($_SESSION['username'])) {
    header("Location: dashboard.php");
    exit();
}

$error_message = ""; // Inicializar una variable para almacenar el mensaje de error

// Verificar si se han enviado los datos del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Realizar la conexión a la base de datos
    require_once 'conexion_BD.php';

    // Recuperar datos del formulario
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Consulta SQL para buscar el usuario en la base de datos
    $sql = "SELECT * FROM Usuarios WHERE Usuario='$username' AND Contraseña='$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $_SESSION['username'] = $username;

        // Obtener el tipo de usuario del primer resultado
        $row = $result->fetch_assoc();
        $tipoUsuario = $row['TipoUsuario'];

        // Almacenar el tipo de usuario en una variable de sesión
        $_SESSION['tipoUsuario'] = $tipoUsuario;

        // Registrar la actividad de inicio de sesión en la tabla UserLogs
        $userId = $row['ID_Usuario'];
        $dateTime = date("Y-m-d H:i:s");
        $ipAddress = $_SERVER['REMOTE_ADDR'];

        $insertLogSql = "INSERT INTO UserLogs (ID_Usuario, FechaHora, IP) VALUES ('$userId', '$dateTime', '$ipAddress')";
        $conn->query($insertLogSql);

        if ($_SESSION['tipoUsuario'] == "Admin") {
            header("Location: dashboard-admin.php");
        } elseif ($_SESSION['tipoUsuario'] == "Arte") {
            header("Location: dashboard.php");
            exit();
        }
    } else {
        // Inicio de sesión fallido
        $error_message = "Usuario o contraseña incorrectos.";
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión</title>
    <link rel="stylesheet" href="../css/login.css">
</head>

<body>
    <?php
    $pageTitle = "Login";
    include 'header.php';
    ?>
    <?php if (!empty($error_message)) : ?>
        <div id="errorAlert" class="alert alert-danger text-center"><?php echo $error_message; ?></div>
    <?php endif; ?>
    <br>
    <h2 class="text-center">Iniciar sesión</h2>

    <div class="container form mt-5">
        <br>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <label class="form-label" for="username">Usuario:</label><br>
            <input type="text" class="form-control" id="username" name="username" required><br>
            <label class="form-label" for="password">Contraseña:</label><br>
            <input type="password" class="form-control" id="password" name="password" required><br><br>
            <div class="container-btn"> <input type="submit" value="Iniciar" class="btnEnviar"> </div>
        </form>
    </div>


    <script>
        setTimeout(function() {
            var errorAlert = document.getElementById("errorAlert");
            if (errorAlert) {
                errorAlert.style.display = "none";
            }
        }, 5000);
    </script>

</body>

</html>