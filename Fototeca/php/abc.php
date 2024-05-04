<?php
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['username'])) {
    // Si no está autenticado, redirigirlo a la página de inicio de sesión
    header("Location: login.php");
    exit();
}

// Si el usuario está autenticado, mostrar el nombre de usuario
$username = $_SESSION['username'];

// Definir las variables para los mensajes de éxito y error
$success_message = '';
$error_message = '';

// Procesar el formulario si se envió
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Conectar a la base de datos
    $servername = "localhost";
    $username = "root";
    $password = "Sandia2016.!";
    $dbname = "fototeca_ob_uaa";

    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }

    // Obtener los datos del formulario

    $ID_Tecnica = $_POST["ID_Tecnica"];
    $NInventario = $_POST["NInventario"];
    $ClTecnica = $_POST["ClTecnica"];
    $PFoto = $_POST["PFoto"];
    $Fondo = $_POST["Fondo"];
    $Formato = $_POST["Formato"];
    $NCopia = $_POST["NCopia"];
    $Tipo = $_POST["Tipo"];
    #------------------------------------------------
    $ID_Cultural = $_POST["ID_Cultural"];
    #------------------------------------------------
    $ID_Datacion = $_POST["ID_Datacion"];
    $FechAsunto = $_POST["FechAsunto"];
    $FechToma = $_POST["FechToma"];
    #------------------------------------------------
    $ID_Ubicacion = $_POST["ID_Ubicacion"];
    $LugarAsunto = $_POST["LugarAsunto"];
    $LugarToma = $_POST["LugarToma"];
    #------------------------------------------------
    $ID_Epoca = $_POST["ID_Epoca"];
    $Epoca = $_POST["Epoca"];
    #------------------------------------------------
    $ID_Autoria = $_POST["ID_Autoria"];
    $Autor = $_POST["Autor"];
    $Autor_Primi = $_POST["Autor_Primi"];
    $Agencia = $_POST["Agencia"];
    $Editor = $_POST["Editor"];
    $Lema = $_POST["Lema"];
    #------------------------------------------------
    $ID_Indicativo = $_POST["ID_Indicativo"];
    $Sello = $_POST["Sello"];
    $Cuno = $_POST["Cuno"];
    $Firma = $_POST["Firma"];
    $Etiqueta = $_POST["Etiqueta"];
    $Imprenta = $_POST["Imprenta"];
    $Otro = $_POST["Otro"];
    #------------------------------------------------
    $ID_Denominacion = $_POST["ID_Denominacion"];
    $TitOrigen = $_POST["TitOrigen"];
    $TitCatalo = $_POST["TitCatalo"];
    $TitSerie = $_POST["TitSerie"];
    #------------------------------------------------
    $ID_Descriptores = $_POST["ID_Descriptores"];
    $TemaPrin = $_POST["TemaPrin"];
    $Descriptores = $_POST["Descriptores"];
    #------------------------------------------------
    $ID_Protagonistas = $_POST["ID_Protagonistas"];
    $Personajes = $_POST["Personajes"];
    #------------------------------------------------

    $InscripOriginal = $_POST["InscripOriginal"];
    $Conjunto = $_POST["Conjunto"];
    $Anotaciones = $_POST["ClTecnica"];
    $NInterseccion = $_POST["NInterseccion"];
    $DocAsociada = $_POST["DocAsociada"];

    // Preparar la consulta SQL
    $sql = "INSERT INTO SeccionTecnica (ID_Tecnica, NumeroInventario, ClaveTecnica, 
                        ProcesoFotografico, FondoColeccion, Formato, NumeroNegativoCopia,
                        Tipo) 
            VALUES ('$ID_Tecnica', '$NInventario', '$ClTecnica', '$PFoto', '$Fondo'
            '$Formato', '$NCopia', '$Tipo')";

    $sql = "INSERT INTO Clave (ID_Tecnica, NumeroInventario, ClaveTecnica, 
                        ProcesoFotografico, FondoColeccion, Formato, NumeroNegativoCopia,
                        Tipo) 
            VALUES ('$ID_Tecnica', '$NInventario', '$ClTecnica', '$PFoto', '$Fondo'
            '$Formato', '$NCopia', '$Tipo')";

    // Ejecutar la consulta SQL
    if ($conn->query($sql) === TRUE) {
        // Mensaje de éxito
        $success_message = "Resguardante registrado exitosamente.";
    } else {
        // Capturar el mensaje de error
        $error_message = "Error: " . $sql . "<br>" . $conn->error;
    }

    // Cerrar la conexión a la base de datos
    $conn->close();
}
