<?php
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Definir las variables para los mensajes de éxito y error
$success_message = '';
$error_message = '';

$imagenObra = '';

// Procesar el formulario si se envió
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Conectar a la base de datos
    $servername = "localhost";
    $username = "root";
    $password = "Sandia2016.!";
    $dbname = "Fototeca_UAA";

    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }

    // Obtener los datos del formulario
    $NInventario = $_POST["NInventario"];
    $ClTecnica = $_POST["ClTecnica"];
    $PFoto = $_POST["PFoto"];
    $Fondo = $_POST["Fondo"];
    $Formato = $_POST["Formato"];
    $NCopia = $_POST["NCopia"];
    $Tipo = $_POST["Tipo"];
    $FechAsunto = $_POST["FechAsunto"];
    $FechToma = $_POST["FechToma"];
    $LugarAsunto = $_POST["LugarAsunto"];
    $LugarToma = $_POST["LugarToma"];
    $Epoca = $_POST["Epoca"];
    $Autor = $_POST["Autor"];
    $Autor_Primi = $_POST["Autor_Primi"];
    $Agencia = $_POST["Agencia"];
    $Editor = $_POST["Editor"];
    $Lema = $_POST["Lema"];
    $Sello = $_POST["Sello"];
    $Cuno = $_POST["Cuno"];
    $Firma = $_POST["Firma"];
    $Etiqueta = $_POST["Etiqueta"];
    $Imprenta = $_POST["Imprenta"];
    $Otro = $_POST["Otro"];
    $TitOrigen = $_POST["TitOrigen"];
    $TitCatalo = $_POST["TitCatalo"];
    $TitSerie = $_POST["TitSerie"];
    $TemaPrin = $_POST["TemaPrin"];
    $Descriptores = $_POST["Descriptores"];
    $Personajes = $_POST["Personajes"];
    $InscripOriginal = $_POST["InscripOriginal"];
    $Conjunto = $_POST["Conjunto"];
    $Anotaciones = $_POST["Anotaciones"];
    $NInterseccion = $_POST["NInterseccion"];


    // Manejo de la imagen de oficio/vale
    if (isset($_FILES['DocAsociada']) && $_FILES['DocAsociada']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['DocAsociada']['tmp_name'];
        $fileType = $_FILES['DocAsociada']['type'];
        $fileSize = $_FILES['DocAsociada']['size'];

        if ($fileType == 'application/pdf' && $fileSize <= 10000000) { // 10 MB limit
            $imagenContenido = file_get_contents($fileTmpPath);
            $imagenContenido = $conn->real_escape_string($imagenContenido);
        } else {
            $error_message = "Archivo de oficio/vale no válido. Asegúrese de que es un PDF y no supera los 10 MB.";
        }
    }

    // Preparar la consulta SQL
    $sql = "INSERT INTO Fototeca (NumeroInventario, ClaveTecnica, ProcesoFotografico, FondoColeccion, Formato, NumeroNegativoCopia, Tipo,
                                  FechaAsunto, FechaToma, LugarAsunto, LugarToma, Epoca, 
                                  Autor, AutorPrimigenio, AgenciaEstudio, EditorColeccionista, Lema, 
                                  Sello, Cuño, Firma, Etiqueta, Imprenta, Otro, 
                                  TituloOrigen, TituloCatalografico, TituloSerie, TemaPrincipal, Descriptores, 
                                  Personajes, InscripcionOriginal, Conjunto, Anotaciones, NumerosInterseccion, DocumentacionAsociada)
            VALUES ('$NInventario', '$ClTecnica', '$PFoto', '$Fondo', '$Formato', '$NCopia', '$Tipo',
                    '$FechAsunto', '$FechToma', '$LugarAsunto', '$LugarToma', '$Epoca', 
                    '$Autor', '$Autor_Primi', '$Agencia', '$Editor', '$Lema', 
                    '$Sello', '$Cuno', '$Firma', '$Etiqueta', '$Imprenta', '$Otro', 
                    '$TitOrigen', '$TitCatalo', '$TitSerie', '$TemaPrin', '$Descriptores', 
                    '$Personajes', '$InscripOriginal', '$Conjunto', '$Anotaciones', '$NInterseccion', '$imagenContenido')";

    // Ejecutar la consulta SQL
    if ($conn->query($sql) === TRUE) {
        // Mensaje de éxito
        $success_message = "Registro agregado exitosamente.";
    } else {
        // Capturar el mensaje de error
        $error_message = "Error: " . $sql . "<br>" . $conn->error;
    }

    // Cerrar la conexión a la base de datos
    $conn->close();
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alta de Registro</title>
    <link href="../../resources/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/login.css">
</head>

<body>
    <?php
    $pageTitle = "Altas ";
    include 'header.php';
    echo '<br>';
    echo '<h1 class="text-center">Registro</h1>';
    ?>

    <!-- Mostrar mensaje de éxito -->
    <?php if (!empty($success_message)) : ?>
        <div id="successAlert" class="alert alert-success text-center"><?php echo $success_message; ?></div>
    <?php endif; ?>

    <!-- Mostrar mensaje de error -->
    <?php if (!empty($error_message)) : ?>
        <div id="errorAlert" class="alert alert-danger text-center"><?php echo $error_message; ?></div>
    <?php endif; ?>

    <div class="container form mt-5">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <!-- Campos para la base de datos SeccionTecnica -->
            <div class="form-group">
                <h3>Sección Técnica</h3>
                <label for="NInventario">Número de Inventario:</label>
                <input type="text" class="form-control" name="NInventario" id="NInventario" required><br>
                <label for="ClTecnica">Clave Técnica:</label>
                <input type="text" class="form-control" name="ClTecnica" id="ClTecnica" required><br>
                <label for="PFoto">Proceso Fotográfico:</label>
                <input type="text" class="form-control" name="PFoto" id="PFoto"><br>
                <label for="Fondo">Fondo / Colección:</label>
                <input type="text" class="form-control" name="Fondo" id="Fondo"><br>
                <label for="Formato">Formato:</label>
                <input type="text" class="form-control" name="Formato" id="Formato"><br>
                <label for="NCopia">Número Negativo Copia:</label>
                <input type="text" class="form-control" name="NCopia" id="NCopia"><br>
                <label for="Tipo">Tipo:</label>
                <input type="text" class="form-control" name="Tipo" id="Tipo"><br>
            </div>

            <!-- Campos para la tabla Datacion -->
            <div class="form-group">
                <h3>Datación</h3>
                <label for="FechAsunto">Fecha Asunto:</label>
                <input type="date" class="form-control" name="FechAsunto" id="FechAsunto"><br>
                <label for="FechToma">Fecha Toma:</label>
                <input type="date" class="form-control" name="FechToma" id="FechToma"><br>
            </div>

            <!-- Campos para la tabla Ubicacion geografica -->
            <div class="form-group">
                <h3>Ubicación Geográfica</h3>
                <label for="LugarAsunto">Lugar de Asunto:</label>
                <input type="text" class="form-control" name="LugarAsunto" id="LugarAsunto"><br>
                <label for="LugarToma">Lugar de Toma:</label>
                <input type="text" class="form-control" name="LugarToma" id="LugarToma"><br>
            </div>

            <!-- Campos para la tabla Epocario -->
            <div class="form-group">
                <h3>Epocario</h3>
                <label for="Epoca">Época:</label>
                <input type="text" class="form-control" name="Epoca" id="Epoca"><br>
            </div>

            <!-- Campos para la tabla Autoria -->
            <div class="form-group">
                <h3>Autoría</h3>
                <label for="Autor">Autor:</label>
                <input type="text" class="form-control" name="Autor" id="Autor"><br>
                <label for="Autor_Primi">Autor Primigenio:</label>
                <input type="text" class="form-control" name="Autor_Primi" id="Autor_Primi"><br>
                <label for="Agencia">Agencia/Estudio:</label>
                <input type="text" class="form-control" name="Agencia" id="Agencia"><br>
                <label for="Editor">Editor/Coleccionista:</label>
                <input type="text" class="form-control" name="Editor" id="Editor"><br>
                <label for="Lema">Lema:</label>
                <input type="text" class="form-control" name="Lema" id="Lema"><br>
            </div>

            <!-- Campos para la tabla indicativo -->
            <div class="form-group">
                <h3>Indicativo</h3>
                <label for="Sello">Sello:</label>
                <input type="text" class="form-control" name="Sello" id="Sello"><br>
                <label for="Cuno">Cuño:</label>
                <input type="text" class="form-control" name="Cuno" id="Cuno"><br>
                <label for="Firma">Firma:</label>
                <input type="text" class="form-control" name="Firma" id="Firma"><br>
                <label for="Etiqueta">Etiqueta:</label>
                <input type="text" class="form-control" name="Etiqueta" id="Etiqueta"><br>
                <label for="Imprenta">Imprenta:</label>
                <input type="text" class="form-control" name="Imprenta" id="Imprenta"><br>
                <label for="Otro">Otro:</label>
                <input type="text" class="form-control" name="Otro" id="Otro"><br>
            </div>

            <!-- Campos para la tabla Denominación -->
            <div class="form-group">
                <h3>Denominación</h3>
                <label for="TitOrigen">Título Origen:</label>
                <input type="text" class="form-control" name="TitOrigen" id="TitOrigen"><br>
                <label for="TitCatalo">Título Catalográfico:</label>
                <input type="text" class="form-control" name="TitCatalo" id="TitCatalo"><br>
                <label for="TitSerie">Título Serie:</label>
                <input type="text" class="form-control" name="TitSerie" id="TitSerie"><br>
            </div>

            <!-- Campos para la tabla Descriptores -->
            <div class="form-group">
                <h3>Descriptores</h3>
                <label for="TemaPrin">Tema Principal:</label>
                <input type="text" class="form-control" name="TemaPrin" id="TemaPrin"><br>
                <label for="Descriptores">Descriptores:</label>
                <textarea name="Descriptores" class="form-control" id="Descriptores" cols="30" rows="5"></textarea><br>
            </div>

            <!-- Campos para la tabla Protagonistas -->
            <div class="form-group">
                <h3>Protagonistas</h3>
                <label for="Personajes">Personajes:</label>
                <textarea class="form-control" name="Personajes" id="Personajes" cols="30" rows="5"></textarea><br>
            </div>

            <!-- Campos para la tabla Observaciones -->
            <div class="form-group">
                <h3>Observaciones</h3>
                <label for="InscripOriginal">Inscripción Original:</label>
                <textarea class="form-control" name="InscripOriginal" id="InscripOriginal" cols="30" rows="5"></textarea><br>
                <label for="Conjunto">Conjunto:</label>
                <input type="text" class="form-control" name="Conjunto" id="Conjunto"><br>
                <label for="Anotaciones">Anotaciones:</label>
                <textarea class="form-control" name="Anotaciones" id="Anotaciones" cols="30" rows="5"></textarea><br>
                <label for="NInterseccion">Números Intersección:</label>
                <textarea class="form-control" name="NInterseccion" id="NInterseccion" cols="30" rows="5"></textarea><br>
                <label for="DocAsociada">Documentación Asociada:</label>
                <textarea class="form-control" name="DocAsociada" id="DocAsociada" cols="30" rows="5"></textarea><br>
                <div class="form-group">
                    <label for="DocAsociada">Imagen de la obra (PDF)</label>
                    <input type="file" class="form-control" id="DocAsociada" name="DocAsociada">
                </div>
            </div>

            <div class="container-btn"> <button type="submit" class="btnEnviar">Guardar</button> </div>
        </form>
    </div>

    <br>

    <div class="container-back">
        <button onclick="goBack()" class="btn btn-secondary mt-3">Regresar</button>
    </div>

    <br>

    <script>
        function goBack() {
            window.history.back();
        }

        // Función para ocultar la alerta después de un cierto período de tiempo
        setTimeout(function() {
            var successAlert = document.getElementById("successAlert");
            var errorAlert = document.getElementById("errorAlert");
            if (errorAlert) {
                errorAlert.style.display = "none";
            } else if (successAlert) {
                successAlert.style.display = "none";
            }
        }, 5000); // 5000 milisegundos = 5 segundos
    </script>
</body>

</html>