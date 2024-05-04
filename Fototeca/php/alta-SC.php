<?php
session_start();

#if (!isset($_SESSION['username'])) {
// Si no está autenticado, redirigirlo a la página de inicio de sesión
#header("Location: login.php");
#exit();
#}

#$username = $_SESSION['username'];

$success_message = '';
$error_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Conectar a la base de datos
    $servername = "localhost";
    $username = "root";
    $password = "ROOT$01JMGA270102&";
    $dbname = "fototecaBD";

    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }

    // Verificar conexión
    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }

    // Función para limpiar los datos enviados por el formulario
    function limpiar_datos($dato)
    {
        $dato = trim($dato);
        $dato = stripslashes($dato);
        $dato = htmlspecialchars($dato);
        return $dato;
    }

    // Procesar datos del formulario y realizar inserción en la tabla Clave
    $id_tecnica_clave = limpiar_datos($_POST['id_tecnica_clave']);

    $sql_clave = "INSERT INTO Clave (ID_Tecnica) VALUES ('$id_tecnica_clave')";

    if ($conn->query($sql_clave) === TRUE) {
        echo "Datos insertados correctamente en la tabla Clave.";
    } else {
        echo "Error al insertar datos en la tabla Clave: " . $conn->error;
    }

    // Procesar datos del formulario y realizar inserción en la tabla Datacion
    $id_tecnica_datacion = limpiar_datos($_POST['id_tecnica_datacion']);
    $fecha_asunto = limpiar_datos($_POST['fecha_asunto']);
    $fecha_toma = limpiar_datos($_POST['fecha_toma']);

    $sql_datacion = "INSERT INTO Datacion (ID_Tecnica, FechaAsunto, FechaToma) VALUES ('$id_tecnica_datacion', '$fecha_asunto', '$fecha_toma')";

    if ($conn->query($sql_datacion) === TRUE) {
        echo "Datos insertados correctamente en la tabla Datacion.";
    } else {
        echo "Error al insertar datos en la tabla Datacion: " . $conn->error;
    }

    // Procesar datos del formulario y realizar inserción en la tabla UbicacionGeografica
    $id_tecnica_ubicacion = limpiar_datos($_POST['id_tecnica_ubicacion']);
    $lugar_asunto = limpiar_datos($_POST['lugar_asunto']);
    $lugar_toma = limpiar_datos($_POST['lugar_toma']);

    $sql_ubicacion = "INSERT INTO UbicacionGeografica (ID_Tecnica, LugarAsunto, LugarToma) VALUES ('$id_tecnica_ubicacion', '$lugar_asunto', '$lugar_toma')";

    if ($conn->query($sql_ubicacion) === TRUE) {
        echo "Datos insertados correctamente en la tabla UbicacionGeografica.<br>";
    } else {
        echo "Error al insertar datos en la tabla UbicacionGeografica: " . $conn->error . "<br>";
    }

    // Procesar datos del formulario y realizar inserción en la tabla Epocario
    $id_tecnica_epocario = limpiar_datos($_POST['id_tecnica_epocario']);
    $epoca = limpiar_datos($_POST['epoca']);

    $sql_epocario = "INSERT INTO Epocario (ID_Tecnica, Epoca) VALUES ('$id_tecnica_epocario', '$epoca')";

    if ($conn->query($sql_epocario) === TRUE) {
        echo "Datos insertados correctamente en la tabla Epocario.<br>";
    } else {
        echo "Error al insertar datos en la tabla Epocario: " . $conn->error . "<br>";
    }

    // Procesar datos del formulario y realizar inserción en la tabla Autoria
    $id_tecnica_autoria = limpiar_datos($_POST['id_tecnica_autoria']);
    $autor = limpiar_datos($_POST['autor']);
    $autor_primigenio = limpiar_datos($_POST['autor_primigenio']);
    $agencia_estudio = limpiar_datos($_POST['agencia_estudio']);
    $editor_coleccionista = limpiar_datos($_POST['editor_coleccionista']);
    $lema = limpiar_datos($_POST['lema']);

    $sql_autoria = "INSERT INTO Autoria (ID_Tecnica, Autor, AutorPrimigenio, AgenciaEstudio, EditorColeccionista, Lema) 
                VALUES ('$id_tecnica_autoria', '$autor', '$autor_primigenio', '$agencia_estudio', '$editor_coleccionista', '$lema')";

    if ($conn->query($sql_autoria) === TRUE) {
        echo "Datos insertados correctamente en la tabla Autoria.<br>";
    } else {
        echo "Error al insertar datos en la tabla Autoria: " . $conn->error . "<br>";
    }

    // Procesar datos del formulario y realizar inserción en la tabla Indicativo
    $id_autoria_indicativo = limpiar_datos($_POST['id_autoria_indicativo']);
    $sello = limpiar_datos($_POST['sello']);
    $cuño = limpiar_datos($_POST['cuño']);
    $firma = limpiar_datos($_POST['firma']);
    $etiqueta = limpiar_datos($_POST['etiqueta']);
    $imprenta = limpiar_datos($_POST['imprenta']);
    $otro = limpiar_datos($_POST['otro']);

    $sql_indicativo = "INSERT INTO Indicativo (ID_Autoria, Sello, Cuño, Firma, Etiqueta, Imprenta, Otro) 
                   VALUES ('$id_autoria_indicativo', '$sello', '$cuño', '$firma', '$etiqueta', '$imprenta', '$otro')";

    if ($conn->query($sql_indicativo) === TRUE) {
        echo "Datos insertados correctamente en la tabla Indicativo.<br>";
    } else {
        echo "Error al insertar datos en la tabla Indicativo: " . $conn->error . "<br>";
    }

    // Procesar datos del formulario y realizar inserción en la tabla Denominacion
    $id_tecnica_denominacion = limpiar_datos($_POST['id_tecnica_denominacion']);
    $titulo_origen = limpiar_datos($_POST['titulo_origen']);
    $titulo_catalografico = limpiar_datos($_POST['titulo_catalografico']);
    $titulo_serie = limpiar_datos($_POST['titulo_serie']);

    $sql_denominacion = "INSERT INTO Denominacion (ID_Tecnica, TituloOrigen, TituloCatalografico, TituloSerie) 
                     VALUES ('$id_tecnica_denominacion', '$titulo_origen', '$titulo_catalografico', '$titulo_serie')";

    if ($conn->query($sql_denominacion) === TRUE) {
        echo "Datos insertados correctamente en la tabla Denominacion.<br>";
    } else {
        echo "Error al insertar datos en la tabla Denominacion: " . $conn->error . "<br>";
    }

    // Procesar datos del formulario y realizar inserción en la tabla Descriptores
    $id_tecnica_descriptores = limpiar_datos($_POST['id_tecnica_descriptores']);
    $tema_principal = limpiar_datos($_POST['tema_principal']);
    $descriptores = limpiar_datos($_POST['descriptores']);

    $sql_descriptores = "INSERT INTO Descriptores (ID_Tecnica, TemaPrincipal, Descriptores) 
                     VALUES ('$id_tecnica_descriptores', '$tema_principal', '$descriptores')";

    if ($conn->query($sql_descriptores) === TRUE) {
        echo "Datos insertados correctamente en la tabla Descriptores.<br>";
    } else {
        echo "Error al insertar datos en la tabla Descriptores: " . $conn->error . "<br>";
    }

    // Procesar datos del formulario y realizar inserción en la tabla Protagonistas
    $id_tecnica_protagonistas = limpiar_datos($_POST['id_tecnica_protagonistas']);
    $personajes = limpiar_datos($_POST['personajes']);

    $sql_protagonistas = "INSERT INTO Protagonistas (ID_Tecnica, Personajes) 
                      VALUES ('$id_tecnica_protagonistas', '$personajes')";

    if ($conn->query($sql_protagonistas) === TRUE) {
        echo "Datos insertados correctamente en la tabla Protagonistas.<br>";
    } else {
        echo "Error al insertar datos en la tabla Protagonistas: " . $conn->error . "<br>";
    }

    // Procesar datos del formulario y realizar inserción en la tabla Observaciones
    $id_tecnica_observaciones = limpiar_datos($_POST['id_tecnica_observaciones']);
    $inscripcion_original = limpiar_datos($_POST['inscripcion_original']);
    $conjunto = limpiar_datos($_POST['conjunto']);
    $anotaciones = limpiar_datos($_POST['anotaciones']);
    $numeros_interseccion = limpiar_datos($_POST['numeros_interseccion']);
    $documentacion_asociada = limpiar_datos($_POST['documentacion_asociada']);

    $sql_observaciones = "INSERT INTO Observaciones (ID_Tecnica, InscripcionOriginal, Conjunto, Anotaciones, NumerosInterseccion, DocumentacionAsociada) 
                      VALUES ('$id_tecnica_observaciones', '$inscripcion_original', '$conjunto', '$anotaciones', '$numeros_interseccion', '$documentacion_asociada')";

    if ($conn->query($sql_observaciones) === TRUE) {
        echo "Datos insertados correctamente en la tabla Observaciones.<br>";
    } else {
        echo "Error al insertar datos en la tabla Observaciones: " . $conn->error . "<br>";
    }

    // Cierra la conexión
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <link href="../../resources/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Datos</title>
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/login.css">
</head>

<body>
    <?php
    $pageTitle = "Altas Seccion Cultural";
    include 'header.php';
    echo '<br>';
    echo '<h1 class="text-center">Registro de Seccion Cultural</h1>';
    ?>

    <?php if (!empty($error_message)) : ?>
        <div id="errorAlert" class="alert alert-danger text-center"><?php echo $error_message; ?></div>
    <?php endif; ?>
    <div class="container form mt-5">
        <h2>Ingresa los datos:</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <!-- Campos para la tabla Clave -->
            <div class="form-group">
                <h3>Clave</h3>
                <label for="id_tecnica_clave">ID Técnica:</label>
                <input type="text" class="form-control" name="id_tecnica_clave" id="id_tecnica_clave">
            </div>

            <!-- Campos para la tabla Datacion -->
            <div class="form-group">
                <h3>Datación</h3>
                <label for="id_tecnica_datacion">ID Técnica:</label>
                <input type="text" class="form-control" name="id_tecnica_datacion" id="id_tecnica_datacion">
                <label for="fecha_asunto">Fecha Asunto:</label>
                <input type="date" class="form-control" name="fecha_asunto" id="fecha_asunto">
                <label for="fecha_toma">Fecha Toma:</label>
                <input type="date" class="form-control" name="fecha_toma" id="fecha_toma">
            </div>

            <!-- Campos para la tabla Ubicacion geografica -->
            <div class="form-group">
                <h3>Ubicación Geográfica</h3>
                <label for="id_tecnica_ubicacion">ID Técnica:</label>
                <input type="text" class="form-control" name="id_tecnica_ubicacion" id="id_tecnica_ubicacion">
                <label for="lugar_asunto">Lugar Asunto:</label>
                <input type="text" class="form-control" name="lugar_asunto" id="lugar_asunto">
                <label for="lugar_toma">Lugar Toma:</label>
                <input type="text" class="form-control" name="lugar_toma" id="lugar_toma">
            </div>

            <!-- Campos para la tabla Ubicacion Epocario -->
            <div class="form-group">
                <h3>Epocario</h3>
                <label for="id_tecnica_epocario">ID Técnica:</label>
                <input type="text" class="form-control" name="id_tecnica_epocario" id="id_tecnica_epocario">
                <label for="epoca">Época:</label>
                <input type="text" class="form-control" name="epoca" id="epoca">
            </div>

            <!-- Campos para la tabla Ubicacion Autoria -->
            <div class="form-group">
                <h3>Autoría</h3>
                <label for="id_tecnica_autoria">ID Técnica:</label>
                <input type="text" class="form-control" name="id_tecnica_autoria" id="id_tecnica_autoria">
                <label for="autor">Autor:</label>
                <input type="text" class="form-control" name="autor" id="autor">
                <label for="autor_primigenio">Autor Primigenio:</label>
                <input type="text" class="form-control" name="autor_primigenio" id="autor_primigenio">
                <label for="agencia_estudio">Agencia/Estudio:</label>
                <input type="text" class="form-control" name="agencia_estudio" id="agencia_estudio">
                <label for="editor_coleccionista">Editor/Coleccionista:</label>
                <input type="text" class="form-control" name="editor_coleccionista" id="editor_coleccionista">
                <label for="lema">Lema:</label>
                <input type="text" class="form-control" name="lema" id="lema">
            </div>

            <!-- Campos para la tabla Ubicacion Denominación -->
            <div class="form-group">
                <h3>Denominación</h3>
                <label for="id_tecnica_denominacion">ID Técnica:</label>
                <input type="text" class="form-control" name="id_tecnica_denominacion" id="id_tecnica_denominacion">
                <label for="titulo_origen">Título Origen:</label>
                <input type="text" class="form-control" name="titulo_origen" id="titulo_origen">
                <label for="titulo_catalografico">Título Catalográfico:</label>
                <input type="text" class="form-control" name="titulo_catalografico" id="titulo_catalografico">
                <label for="titulo_serie">Título Serie:</label>
                <input type="text" class="form-control" name="titulo_serie" id="titulo_serie">
            </div>

            <!-- Campos para la tabla Ubicacion Descriptores -->
            <div class="form-group">
                <h3>Descriptores</h3>
                <label for="id_tecnica_descriptores">ID Técnica:</label>
                <input type="text" class="form-control" name="id_tecnica_descriptores" id="id_tecnica_descriptores">
                <label for="tema_principal">Tema Principal:</label>
                <input type="text" class="form-control" name="tema_principal" id="tema_principal">
                <label for="descriptores">Descriptores:</label>
                <textarea class="form-control" name="descriptores" id="descriptores" cols="30" rows="5"></textarea>
            </div>

            <!-- Campos para la tabla Ubicacion Protagonistas -->
            <div class="form-group">
                <h3>Protagonistas</h3>
                <label for="id_tecnica_protagonistas">ID Técnica:</label>
                <input type="text" class="form-control" name="id_tecnica_protagonistas" id="id_tecnica_protagonistas">
                <label for="personajes">Personajes:</label>
                <textarea class="form-control" name="personajes" id="personajes" cols="30" rows="5"></textarea>
            </div>

            <!-- Campos para la tabla Ubicacion observaciones -->
            <div class="form-group">
                <h3>Observaciones</h3>
                <label for="id_tecnica_observaciones">ID Técnica:</label>
                <input type="text" class="form-control" name="id_tecnica_observaciones" id="id_tecnica_observaciones">
                <label for="inscripcion_original">Inscripción Original:</label>
                <textarea class="form-control" name="inscripcion_original" id="inscripcion_original" cols="30" rows="5"></textarea>
                <label for="conjunto">Conjunto:</label>
                <input type="text" class="form-control" name="conjunto" id="conjunto">
                <label for="anotaciones">Anotaciones:</label>
                <textarea class="form-control" name="anotaciones" id="anotaciones" cols="30" rows="5"></textarea>
                <label for="numeros_interseccion">Números Intersección:</label>
                <textarea class="form-control" name="numeros_interseccion" id="numeros_interseccion" cols="30" rows="5"></textarea>
                <label for="documentacion_asociada">Documentación Asociada:</label>
                <textarea class="form-control" name="documentacion_asociada" id="documentacion_asociada" cols="30" rows="5"></textarea>
            </div>

            <!-- Campos para la tabla Ubicacion indicativo -->
            <div class="form-group">
                <h3>Indicativo</h3>
                <label for="id_autoria_indicativo">ID Autoría:</label>
                <input type="text" class="form-control" name="id_autoria_indicativo" id="id_autoria_indicativo">
                <label for="sello">Sello:</label>
                <input type="text" class="form-control" name="sello" id="sello">
                <label for="cuño">Cuño:</label>
                <input type="text" class="form-control" name="cuño" id="cuño">
                <label for="firma">Firma:</label>
                <input type="text" class="form-control" name="firma" id="firma">
                <label for="etiqueta">Etiqueta:</label>
                <input type="text" class="form-control" name="etiqueta" id="etiqueta">
                <label for="imprenta">Imprenta:</label>
                <input type="text" class="form-control" name="imprenta" id="imprenta">
                <label for="otro">Otro:</label>
                <input type="text" class="form-control" name="otro" id="otro">
            </div>

            <div class="container-btn">
                <button type="submit" class="btnEnviar">Guardar</button>
            </div>
        </form>
    </div>
</body>

</html>