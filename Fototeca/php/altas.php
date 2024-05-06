<?php
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['username'])) {
    // Si no está autenticado, redirigirlo a la página de inicio de sesión
    header("Location: login.php");
    exit();
}

// Si el usuario está autenticado, mostrar el nombre de usuario
#$username = $_SESSION['username'];

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
    #-------SeccionTecnica------------------------------
    $ID_Tecnica = $_POST["ID_Tecnica"];
    $NInventario = $_POST["NInventario"];
    $ClTecnica = $_POST["ClTecnica"];
    $PFoto = $_POST["PFoto"];
    $Fondo = $_POST["Fondo"];
    $Formato = $_POST["Formato"];
    $NCopia = $_POST["NCopia"];
    $Tipo = $_POST["Tipo"];

    #-----------Clave-----------------------------------
    $ID_Cultural = $_POST["ID_Cultural"];

    #----------Datacion---------------------------------
    $ID_Datacion = $_POST["ID_Datacion"];
    $FechAsunto = $_POST["FechAsunto"];
    $FechToma = $_POST["FechToma"];

    #------Ubicacion Geografica-------------------------
    $ID_Ubicacion = $_POST["ID_Ubicacion"];
    $LugarAsunto = $_POST["LugarAsunto"];
    $LugarToma = $_POST["LugarToma"];

    #----------Epocario---------------------------------
    $ID_Epoca = $_POST["ID_Epoca"];
    $Epoca = $_POST["Epoca"];

    #-----------Autoria---------------------------------
    $ID_Autoria = $_POST["ID_Autoria"];
    $Autor = $_POST["Autor"];
    $Autor_Primi = $_POST["Autor_Primi"];
    $Agencia = $_POST["Agencia"];
    $Editor = $_POST["Editor"];
    $Lema = $_POST["Lema"];

    #----------Indicativo--------------------------------
    $ID_Indicativo = $_POST["ID_Indicativo"];
    $Sello = $_POST["Sello"];
    $Cuno = $_POST["Cuno"];
    $Firma = $_POST["Firma"];
    $Etiqueta = $_POST["Etiqueta"];
    $Imprenta = $_POST["Imprenta"];
    $Otro = $_POST["Otro"];

    #-----------Denominacion------------------------------
    $ID_Denominacion = $_POST["ID_Denominacion"];
    $TitOrigen = $_POST["TitOrigen"];
    $TitCatalo = $_POST["TitCatalo"];
    $TitSerie = $_POST["TitSerie"];

    #-----------Descriptores------------------------------
    $ID_Descriptores = $_POST["ID_Descriptores"];
    $TemaPrin = $_POST["TemaPrin"];
    $Descriptores = $_POST["Descriptores"];

    #------------Protagonistas----------------------------
    $ID_Protagonistas = $_POST["ID_Protagonistas"];
    $Personajes = $_POST["Personajes"];

    #------------Observaciones----------------------------
    $ID_Observaciones = $_POST["ID_Observaciones"];
    $InscripOriginal = $_POST["InscripOriginal"];
    $Conjunto = $_POST["Conjunto"];
    $Anotaciones = $_POST["ClTecnica"];
    $NInterseccion = $_POST["NInterseccion"];
    $DocAsociada = $_POST["DocAsociada"];

    // Preparar la consulta SQL
    #-------SeccionTecnica------------------------------
    $sql = "INSERT INTO SeccionTecnica (ID_Tecnica, NumeroInventario, ClaveTecnica, 
                        ProcesoFotografico, FondoColeccion, Formato, NumeroNegativoCopia,
                        Tipo) 
            VALUES ('$ID_Tecnica', '$NInventario', '$ClTecnica', '$PFoto', '$Fondo'
            '$Formato', '$NCopia', '$Tipo')";

    #-----------Clave-----------------------------------
    $sql = "INSERT INTO Clave (ID_Tecnica, ID_Cultural ) 
            VALUES ('$ID_Tecnica', '$ID_Cultural')";

    #----------Datacion---------------------------------
    $sql = "INSERT INTO Datacion (ID_Tecnica, ID_Datacion, FechaAsunto,  FechaToma) 
            VALUES ('$ID_Tecnica', '$ID_Datacion','$FechAsunto', '$FechToma')";

    #------Ubicacion Geografica-------------------------
    $sql = "INSERT INTO UbicacionGeografica (ID_Tecnica, ID_Ubicacion, LugarAsunto,  LugarToma) 
            VALUES ('$ID_Tecnica', '$ID_Ubicacion','$LugarAsunto', '$LugarToma')";

    #----------Epocario--------------------------------
    $sql = "INSERT INTO Epocario (ID_Tecnica, ID_Epoca, Epoca) 
            VALUES ('$ID_Tecnica', '$ID_Epoca', '$Epoca')";

    #-----------Autoria---------------------------------
    $sql = "INSERT INTO Autoria (ID_Tecnica, ID_Autoria, Autor,  AutorPrimigenio, AgenciaEstudio
                        EditorColeccionista, Lema) 
            VALUES ('$ID_Tecnica', '$ID_Autoria','$Autor', '$Autor_Primi', '$Agencia'
                    '$Editor', '$Lema')";

    #----------Indicativo--------------------------------
    $sql = "INSERT INTO Indicativo (ID_Indicativo, ID_Autoria, Sello, Cuño, Firma, Etiqueta, Imprenta, Otro) 
            VALUES ('$ID_Indicativo', '$ID_Autoria', '$Sello', '$Cuno', '$Firma', '$Etiqueta', '$Imprenta', '$Otro')";

    #-----------Denominacion------------------------------
    $sql = "INSERT INTO Denominacion (ID_Tecnica, ID_Denominacion, TituloOrigen, TituloCatalografico, TituloSerie) 
            VALUES ('$ID_Tecnica', '$ID_Denominacion', '$TitOrigen', '$TitCatalo', '$TitSerie')";

    #-----------Descriptores------------------------------
    $sql = "INSERT INTO Descriptores (ID_Tecnica, ID_Descriptores, TemaPrincipal, Descriptores) 
                VALUES ('$ID_Tecnica', '$ID_Descriptores', '$TemaPrin', '$Descriptores')";

    #------------Protagonistas----------------------------
    $sql = "INSERT INTO Protagonistas (ID_Tecnica, ID_Protagonistas, Personajes) 
            VALUES ('$ID_Tecnica', '$ID_Protagonistas', '$Personajes')";

    #------------Observaciones----------------------------
    $sql = "INSERT INTO Observaciones (ID_Tecnica, ID_Observaciones, InscripcionOriginal, Conjunto, Anotaciones, NumerosInterseccion, DocumentacionAsociada) 
            VALUES ('$ID_Tecnica', '$ID_Observaciones', '$InscripOriginal', '$Conjunto', '$Anotaciones', '$NInterseccion', '$DocAsociada')";


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
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alta de Resguardante</title>
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
                <label for="id_tecnica_clave">Numero de Inventario: </label>
                <input type="text" class="form-control" name="NInventario" id="NInventario"><br><br>
                <label for="id_tecnica_clave">Clave Tecnica: </label>
                <input type="text" class="form-control" name="ClTecnica" id="ClTecnica"><br><br>
                <label for="id_tecnica_clave">Proceso Fotografico: </label>
                <input type="text" class="form-control" name="PFoto" id="PFoto"><br><br>
                <label for="id_tecnica_clave">Fondo / Coleccion: </label>
                <input type="text" class="form-control" name="Fondo" id="Fondo"><br><br>
                <label for="id_tecnica_clave">Formato: </label>
                <input type="text"class="form-control"  name="Formato" id="Formato"><br><br>
                <label for="id_tecnica_clave">Numero Negativo Copia: </label>
                <input type="text" class="form-control" name="NCopia" id="NCopia"><br><br>
                <label for="id_tecnica_clave">Tipo: </label>
                <input type="text" class="form-control" name="Tipo" id="Tipo"><br><br>
            </div>

            <!-- Campos para la tabla Datacion -->
            <div class="form-group">
                <h3>Ubicación Geográfica</h3>
                <label for="id_tecnica_datacion">ID Datación: </label>
                <input type="text" class="form-control" name="ID_Datacion" id="ID_Datacion"><br><br>
                <label for="fecha_asunto">Fecha Asunto: </label>
                <input type="date" class="form-control" name="FechAsunto" id="FechAsunto"><br><br>
                <label for="fecha_toma">Fecha Toma: </label>
                <input type="date" class="form-control" name="FechToma" id="FechToma"><br><br>
            </div>

            <!-- Campos para la tabla Ubicacion geografica -->
            <div class="form-group">
            <h3>Ubicación Geográfica</h3>
                <label for="id_tecnica_ubicacion">ID Ubicación: </label>
                <input type="text" class="form-control" name="ID_Ubicacion" id="ID_Ubicacion"><br><br>
                <label for="lugar_asunto">Lugar de Asunto: </label>
                <input type="text" class="form-control" name="LugarAsunto" id="LugarAsunto"><br><br>
                <label for="lugar_toma">Lugar de Toma: </label>
                <input type="text" class="form-control" name="LugarToma" id="LugarToma"><br><br>
            </div>

            <!-- Campos para la tabla Epocario -->
            <div class="form-group">
                <h3>Epocario</h3>
                <label for="id_tecnica_epocario">ID Epoca: </label>
                <input type="text" class="form-control" name="ID_Epoca" id="ID_Epoca"><br><br>
                <label for="epoca">Época: </label>
                <input type="text" class="form-control" name="Epoca" id="Epoca"><br><br>
            </div>

            <!-- Campos para la tabla Autoria -->
            <div class="form-group">
                <h3>Autoría</h3>
                <label for="id_tecnica_autoria">ID Autoria: </label>
                <input type="text" class="form-control" name="ID_Autoria" id="ID_Autoria"><br><br>
                <label for="autor">Autor: </label>
                <input type="text" class="form-control" name="Autor" id="Autor"><br><br>
                <label for="autor_primigenio">Autor Primigenio: </label>
                <input type="text" class="form-control" name="Autor_Primi" id="Autor_Primi"><br><br>
                <label for="agencia_estudio">Agencia/Estudio: </label>
                <input type="text" class="form-control" name="Agencia" id="Agencia"><br><br>
                <label for="editor_coleccionista">Editor/Coleccionista: </label>
                <input type="text" class="form-control" name="Editor" id="Editor"><br><br>
                <label for="lema">Lema: </label>
                <input type="text" class="form-control" name="Lema" id="Lema"><br><br>
            </div>

            <!-- Campos para la tabla indicativo -->
            <div class="form-group">
                <h3>Indicativo</h3>
                <label for="id_autoria_indicativo">ID Indicativo: </label>
                <input type="text" class="form-control" name="ID_Indicativo" id="ID_Indicativo"><br><br>
                <label for="sello">Sello: </label>
                <input type="text" class="form-control" name="Sello" id="Sello"><br><br>
                <label for="cuño">Cuño: </label>
                <input type="text" class="form-control" name="Cuno" id="Cuno"><br><br>
                <label for="firma">Firma: </label>
                <input type="text" class="form-control" name="Firma" id="Firma"><br><br>
                <label for="etiqueta">Etiqueta: </label>
                <input type="text" class="form-control" name="Etiqueta" id="Etiqueta"><br><br>
                <label for="imprenta">Imprenta: </label>
                <input type="text" class="form-control" name="Imprenta" id="Imprenta"><br><br>
                <label for="otro">Otro: </label>
                <input type="text" class="form-control" name="Otro" id="Otro"><br><br>
            </div>

            <!-- Campos para la tabla Denominación -->
            <div class="form-group">
                <h3>Denominación</h3>
                <label for="id_tecnica_denominacion">ID Denominacion: </label>
                <input type="text" class="form-control" name="ID_Denominacion" id="ID_Denominacion"><br><br>
                <label for="titulo_origen">Título Origen: </label>
                <input type="text" class="form-control" name="TitOrigen" id="TitOrigen"><br><br>
                <label for="titulo_catalografico">Título Catalográfico: </label>
                <input type="text" class="form-control" name="TitCatalo" id="TitCatalo"><br><br>
                <label for="titulo_serie">Título Serie: </label>
                <input type="text" class="form-control" name="TitSerie" id="TitSerie"><br><br>
            </div>

            <!-- Campos para la tabla Ubicacion Descriptores -->
            <div class="form-group">
                <h3>Descriptores</h3>
                <label for="id_tecnica_descriptores">ID Descriptores: </label>
                <input type="text" class="form-control" name="ID_Descriptores" id="ID_Descriptores"><br><br>
                <label for="tema_principal">Tema Principal: </label>
                <input type="text" class="form-control" name="TemaPrin" id="TemaPrin"><br><br>
                <label for="descriptores">Descriptores: </label>
                <textarea name="Descriptores" class="form-control" id="Descriptores" cols="30" rows="5"></textarea><br><br>
            </div>

            <!-- Campos para la tabla Ubicacion Protagonistas -->
            <div class="form-group">
                <h3>Protagonistas</h3>
                <label for="id_tecnica_protagonistas">ID Protagonistas: </label>
                <input type="text" class="form-control" name="ID_Protagonistas" id="ID_Protagonistas"><br><br>
                <label for="personajes">Personajes: </label>
                <textarea class="form-control" name="Personajes" id="Personajes" cols="30" rows="5"></textarea><br><br>
            </div>

            <!-- Campos para la tabla Ubicacion observaciones -->
            <div class="form-group">
                <h3>Observaciones</h3>
                <label for="id_tecnica_observaciones">ID Observaciones: </label>
                <input type="text" class="form-control" name="ID_Observaciones" id="ID_Observaciones"><br><br>
                <label for="inscripcion_original">Inscripción Original: </label>
                <textarea class="form-control" name="InscripOriginal" id="InscripOriginal" cols="30" rows="5"></textarea><br><br>
                <label for="conjunto">Conjunto: </label>
                <input type="text" class="form-control" name="Conjunto" id="Conjunto"><br><br>
                <label for="anotaciones">Anotaciones: </label>
                <textarea class="form-control" name="Anotaciones" id="Anotaciones" cols="30" rows="5"></textarea><br><br>
                <label for="numeros_interseccion">Números Intersección: </label>
                <textarea class="form-control" name="NInterseccion" id="NInterseccion" cols="30" rows="5"></textarea><br><br>
                <label for="documentacion_asociada">Documentación Asociada: </label>
                <textarea class="form-control" name="DocAsociada" id="DocAsociada" cols="30" rows="5"></textarea><br><br>
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