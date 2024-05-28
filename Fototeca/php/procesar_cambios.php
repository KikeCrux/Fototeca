<?php
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['username'])) {
    // Si no está autenticado, redirigirlo a la página de inicio de sesión
    header("Location: login.php");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "Sandia2016.!";
$dbname = "fototeca_uaa";

// Crear conexión a la base de datos
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Verificar si se recibió el ID del registro a cambiar
if (!isset($_GET['id'])) {
    header("Location: cambios.php");
    exit();
}

$ID_Tecnica = $_GET['id'];

// Consulta unificada para obtener los datos del registro específico
$sql = "SELECT ST.ID_Tecnica, ST.NumeroInventario, ST.ClaveTecnica, 
               ST.ProcesoFotografico, ST.FondoColeccion, ST.Formato, ST.NumeroNegativoCopia,
               ST.Tipo,
               C.ID_Cultural,
               D.ID_Datacion, D.FechaAsunto, D.FechaToma,
               UG.ID_Ubicacion, UG.LugarAsunto, UG.LugarToma,
               E.ID_Epoca, E.Epoca,
               A.ID_Autoria, A.Autor, A.AutorPrimigenio, A.AgenciaEstudio, A.EditorColeccionista, A.Lema,
               I.ID_Indicativo, I.Sello, I.Cuño, I.Firma, I.Etiqueta, I.Imprenta, I.Otro,
               DN.ID_Denominacion, DN.TituloOrigen, DN.TituloCatalografico, DN.TituloSerie,
               DS.ID_Descriptores, DS.TemaPrincipal, DS.Descriptores,
               P.ID_Protagonistas, P.Personajes,
               O.ID_Observaciones, O.InscripcionOriginal, O.Conjunto, O.Anotaciones, O.NumerosInterseccion, O.DocumentacionAsociada
        FROM SeccionTecnica ST
        LEFT JOIN Clave C ON ST.ID_Tecnica = C.ID_Tecnica
        LEFT JOIN Datacion D ON ST.ID_Tecnica = D.ID_Tecnica
        LEFT JOIN UbicacionGeografica UG ON ST.ID_Tecnica = UG.ID_Tecnica
        LEFT JOIN Epocario E ON ST.ID_Tecnica = E.ID_Tecnica
        LEFT JOIN Autoria A ON ST.ID_Tecnica = A.ID_Tecnica
        LEFT JOIN Indicativo I ON A.ID_Autoria = I.ID_Autoria
        LEFT JOIN Denominacion DN ON ST.ID_Tecnica = DN.ID_Tecnica
        LEFT JOIN Descriptores DS ON ST.ID_Tecnica = DS.ID_Tecnica
        LEFT JOIN Protagonistas P ON ST.ID_Tecnica = P.ID_Tecnica
        LEFT JOIN Observaciones O ON ST.ID_Tecnica = O.ID_Tecnica
        WHERE ST.ID_Tecnica = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $ID_Tecnica);
$stmt->execute();
$result = $stmt->get_result();

// Verificar si se encontró el registro
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
} else {
    header("Location: cambios.php");
    exit();
}

// Si se envió el formulario de cambios
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recolectar datos del formulario
    $nuevo_NumeroInventario = $_POST["NumeroInventario"];
    $nuevo_ClaveTecnica = $_POST["ClaveTecnica"];
    $nuevo_ProcesoFotografico = $_POST["ProcesoFotografico"];
    $nuevo_FondoColeccion = $_POST["FondoColeccion"];
    $nuevo_Formato = $_POST["Formato"];
    $nuevo_NumeroNegativoCopia = $_POST["NumeroNegativoCopia"];
    $nuevo_Tipo = $_POST["Tipo"];

    $nuevo_ID_Cultural = $_POST["ID_Cultural"];
    $nuevo_ID_Datacion = $_POST["ID_Datacion"];
    $nuevo_FechaAsunto = $_POST["FechaAsunto"];
    $nuevo_FechaToma = $_POST["FechaToma"];
    $nuevo_ID_Ubicacion = $_POST["ID_Ubicacion"];
    $nuevo_LugarAsunto = $_POST["LugarAsunto"];
    $nuevo_LugarToma = $_POST["LugarToma"];
    $nuevo_ID_Epoca = $_POST["ID_Epoca"];
    $nuevo_Epoca = $_POST["Epoca"];
    $nuevo_ID_Autoria = $_POST["ID_Autoria"];
    $nuevo_Autor = $_POST["Autor"];
    $nuevo_AutorPrimigenio = $_POST["AutorPrimigenio"];
    $nuevo_AgenciaEstudio = $_POST["AgenciaEstudio"];
    $nuevo_EditorColeccionista = $_POST["EditorColeccionista"];
    $nuevo_Lema = $_POST["Lema"];
    $nuevo_ID_Indicativo = $_POST["ID_Indicativo"];
    $nuevo_Sello = $_POST["Sello"];
    $nuevo_Cuño = $_POST["Cuño"];
    $nuevo_Firma = $_POST["Firma"];
    $nuevo_Etiqueta = $_POST["Etiqueta"];
    $nuevo_Imprenta = $_POST["Imprenta"];
    $nuevo_Otro = $_POST["Otro"];
    $nuevo_ID_Denominacion = $_POST["ID_Denominacion"];
    $nuevo_TituloOrigen = $_POST["TituloOrigen"];
    $nuevo_TituloCatalografico = $_POST["TituloCatalografico"];
    $nuevo_TituloSerie = $_POST["TituloSerie"];
    $nuevo_ID_Descriptores = $_POST["ID_Descriptores"];
    $nuevo_TemaPrincipal = $_POST["TemaPrincipal"];
    $nuevo_Descriptores = $_POST["Descriptores"];
    $nuevo_ID_Protagonistas = $_POST["ID_Protagonistas"];
    $nuevo_Personajes = $_POST["Personajes"];
    $nuevo_ID_Observaciones = $_POST["ID_Observaciones"];
    $nuevo_InscripcionOriginal = $_POST["InscripcionOriginal"];
    $nuevo_Conjunto = $_POST["Conjunto"];
    $nuevo_Anotaciones = $_POST["Anotaciones"];
    $nuevo_NumerosInterseccion = $_POST["NumerosInterseccion"];
    $nuevo_DocumentacionAsociada = $_POST["DocumentacionAsociada"];

    // Actualizar los datos en la base de datos
    $sql = "UPDATE SeccionTecnica SET NumeroInventario=?, ClaveTecnica=?, ProcesoFotografico=?, FondoColeccion=?, Formato=?, NumeroNegativoCopia=?, Tipo=? WHERE ID_Tecnica=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssi", $nuevo_NumeroInventario, $nuevo_ClaveTecnica, $nuevo_ProcesoFotografico, $nuevo_FondoColeccion, $nuevo_Formato, $nuevo_NumeroNegativoCopia, $nuevo_Tipo, $ID_Tecnica);
    $stmt->execute();

    $sql = "UPDATE Clave SET ID_Cultural=? WHERE ID_Tecnica=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $nuevo_ID_Cultural, $ID_Tecnica);
    $stmt->execute();

    $sql = "UPDATE Datacion SET FechaAsunto=?, FechaToma=? WHERE ID_Tecnica=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $nuevo_FechaAsunto, $nuevo_FechaToma, $ID_Tecnica);
    $stmt->execute();

    $sql = "UPDATE UbicacionGeografica SET LugarAsunto=?, LugarToma=? WHERE ID_Tecnica=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $nuevo_LugarAsunto, $nuevo_LugarToma, $ID_Tecnica);
    $stmt->execute();

    $sql = "UPDATE Epocario SET Epoca=? WHERE ID_Tecnica=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $nuevo_Epoca, $ID_Tecnica);
    $stmt->execute();

    $sql = "UPDATE Autoria SET Autor=?, AutorPrimigenio=?, AgenciaEstudio=?, EditorColeccionista=?, Lema=? WHERE ID_Tecnica=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssi", $nuevo_Autor, $nuevo_AutorPrimigenio, $nuevo_AgenciaEstudio, $nuevo_EditorColeccionista, $nuevo_Lema, $ID_Tecnica);
    $stmt->execute();

    $sql = "UPDATE Indicativo SET Sello=?, Cuño=?, Firma=?, Etiqueta=?, Imprenta=?, Otro=? WHERE ID_Autoria=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssi", $nuevo_Sello, $nuevo_Cuño, $nuevo_Firma, $nuevo_Etiqueta, $nuevo_Imprenta, $nuevo_Otro, $row['ID_Autoria']);
    $stmt->execute();

    $sql = "UPDATE Denominacion SET TituloOrigen=?, TituloCatalografico=?, TituloSerie=? WHERE ID_Tecnica=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $nuevo_TituloOrigen, $nuevo_TituloCatalografico, $nuevo_TituloSerie, $ID_Tecnica);
    $stmt->execute();

    $sql = "UPDATE Descriptores SET TemaPrincipal=?, Descriptores=? WHERE ID_Tecnica=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $nuevo_TemaPrincipal, $nuevo_Descriptores, $ID_Tecnica);
    $stmt->execute();

    $sql = "UPDATE Protagonistas SET Personajes=? WHERE ID_Tecnica=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $nuevo_Personajes, $ID_Tecnica);
    $stmt->execute();

    $sql = "UPDATE Observaciones SET InscripcionOriginal=?, Conjunto=?, Anotaciones=?, NumerosInterseccion=?, DocumentacionAsociada=? WHERE ID_Tecnica=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssi", $nuevo_InscripcionOriginal, $nuevo_Conjunto, $nuevo_Anotaciones, $nuevo_NumerosInterseccion, $nuevo_DocumentacionAsociada, $ID_Tecnica);
    $stmt->execute();

    header("Location: cambio-t3.php?success_message=Cambios realizados exitosamente.");
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Procesar Cambios</title>
    <link rel="stylesheet" href="../css/login.css">
</head>

<body>
    <?php
    $pageTitle = "Procesar Cambios";
    include 'header.php';
    echo '<br>';
    ?>

    <div class="container mt-5">
        <h1 class="text-center">Procesar Cambios de Datos Generales</h1>
        <br>
        <?php if (!empty($error_message)) : ?>
            <div class="alert alert-danger text-center"><?php echo $error_message; ?></div>
        <?php endif; ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?id=' . $ID_Tecnica; ?>" method="post" enctype="multipart/form-data">
            <!-- Formulario actualizado con los nuevos campos -->
            <div class="form-group">
                <label for="NumeroInventario">Número Inventario:</label>
                <input type="text" class="form-control" id="NumeroInventario" name="NumeroInventario" value="<?php echo htmlspecialchars($row['NumeroInventario']); ?>" required>
            </div>
            <div class="form-group">
                <label for="ClaveTecnica">Clave Técnica:</label>
                <input type="text" class="form-control" id="ClaveTecnica" name="ClaveTecnica" value="<?php echo htmlspecialchars($row['ClaveTecnica']); ?>" required>
            </div>
            <div class="form-group">
                <label for="ProcesoFotografico">Proceso Fotográfico:</label>
                <input type="text" class="form-control" id="ProcesoFotografico" name="ProcesoFotografico" value="<?php echo htmlspecialchars($row['ProcesoFotografico']); ?>">
            </div>
            <div class="form-group">
                <label for="FondoColeccion">Fondo Colección:</label>
                <input type="text" class="form-control" id="FondoColeccion" name="FondoColeccion" value="<?php echo htmlspecialchars($row['FondoColeccion']); ?>">
            </div>
            <div class="form-group">
                <label for="Formato">Formato:</label>
                <input type="text" class="form-control" id="Formato" name="Formato" value="<?php echo htmlspecialchars($row['Formato']); ?>">
            </div>
            <div class="form-group">
                <label for="NumeroNegativoCopia">Número Negativo/Copia:</label>
                <input type="text" class="form-control" id="NumeroNegativoCopia" name="NumeroNegativoCopia" value="<?php echo htmlspecialchars($row['NumeroNegativoCopia']); ?>">
            </div>
            <div class="form-group">
                <label for="Tipo">Tipo:</label>
                <input type="text" class="form-control" id="Tipo" name="Tipo" value="<?php echo htmlspecialchars($row['Tipo']); ?>">
            </div>
            <!-- Campos adicionales para las tablas relacionadas -->
            <div class="form-group">
                <label for="ID_Cultural">ID Cultural:</label>
                <input type="text" class="form-control" id="ID_Cultural" name="ID_Cultural" value="<?php echo htmlspecialchars($row['ID_Cultural']); ?>">
            </div>
            <div class="form-group">
                <label for="FechaAsunto">Fecha Asunto:</label>
                <input type="date" class="form-control" id="FechaAsunto" name="FechaAsunto" value="<?php echo htmlspecialchars($row['FechaAsunto']); ?>">
            </div>
            <div class="form-group">
                <label for="FechaToma">Fecha Toma:</label>
                <input type="date" class="form-control" id="FechaToma" name="FechaToma" value="<?php echo htmlspecialchars($row['FechaToma']); ?>">
            </div>
            <div class="form-group">
                <label for="LugarAsunto">Lugar Asunto:</label>
                <input type="text" class="form-control" id="LugarAsunto" name="LugarAsunto" value="<?php echo htmlspecialchars($row['LugarAsunto']); ?>">
            </div>
            <div class="form-group">
                <label for="LugarToma">Lugar Toma:</label>
                <input type="text" class="form-control" id="LugarToma" name="LugarToma" value="<?php echo htmlspecialchars($row['LugarToma']); ?>">
            </div>
            <div class="form-group">
                <label for="Epoca">Epoca:</label>
                <input type="text" class="form-control" id="Epoca" name="Epoca" value="<?php echo htmlspecialchars($row['Epoca']); ?>">
            </div>
            <div class="form-group">
                <label for="Autor">Autor:</label>
                <input type="text" class="form-control" id="Autor" name="Autor" value="<?php echo htmlspecialchars($row['Autor']); ?>">
            </div>
            <div class="form-group">
                <label for="AutorPrimigenio">Autor Primigenio:</label>
                <input type="text" class="form-control" id="AutorPrimigenio" name="AutorPrimigenio" value="<?php echo htmlspecialchars($row['AutorPrimigenio']); ?>">
            </div>
            <div class="form-group">
                <label for="AgenciaEstudio">Agencia/Estudio:</label>
                <input type="text" class="form-control" id="AgenciaEstudio" name="AgenciaEstudio" value="<?php echo htmlspecialchars($row['AgenciaEstudio']); ?>">
            </div>
            <div class="form-group">
                <label for="EditorColeccionista">Editor/Coleccionista:</label>
                <input type="text" class="form-control" id="EditorColeccionista" name="EditorColeccionista" value="<?php echo htmlspecialchars($row['EditorColeccionista']); ?>">
            </div>
            <div class="form-group">
                <label for="Lema">Lema:</label>
                <input type="text" class="form-control" id="Lema" name="Lema" value="<?php echo htmlspecialchars($row['Lema']); ?>">
            </div>
            <div class="form-group">
                <label for="Sello">Sello:</label>
                <input type="text" class="form-control" id="Sello" name="Sello" value="<?php echo htmlspecialchars($row['Sello']); ?>">
            </div>
            <div class="form-group">
                <label for="Cuño">Cuño:</label>
                <input type="text" class="form-control" id="Cuño" name="Cuño" value="<?php echo htmlspecialchars($row['Cuño']); ?>">
            </div>
            <div class="form-group">
                <label for="Firma">Firma:</label>
                <input type="text" class="form-control" id="Firma" name="Firma" value="<?php echo htmlspecialchars($row['Firma']); ?>">
            </div>
            <div class="form-group">
                <label for="Etiqueta">Etiqueta:</label>
                <input type="text" class="form-control" id="Etiqueta" name="Etiqueta" value="<?php echo htmlspecialchars($row['Etiqueta']); ?>">
            </div>
            <div class="form-group">
                <label for="Imprenta">Imprenta:</label>
                <input type="text" class="form-control" id="Imprenta" name="Imprenta" value="<?php echo htmlspecialchars($row['Imprenta']); ?>">
            </div>
            <div class="form-group">
                <label for="Otro">Otro:</label>
                <input type="text" class="form-control" id="Otro" name="Otro" value="<?php echo htmlspecialchars($row['Otro']); ?>">
            </div>
            <div class="form-group">
                <label for="TituloOrigen">Título Origen:</label>
                <input type="text" class="form-control" id="TituloOrigen" name="TituloOrigen" value="<?php echo htmlspecialchars($row['TituloOrigen']); ?>">
            </div>
            <div class="form-group">
                <label for="TituloCatalografico">Título Catalográfico:</label>
                <input type="text" class="form-control" id="TituloCatalografico" name="TituloCatalografico" value="<?php echo htmlspecialchars($row['TituloCatalografico']); ?>">
            </div>
            <div class="form-group">
                <label for="TituloSerie">Título Serie:</label>
                <input type="text" class="form-control" id="TituloSerie" name="TituloSerie" value="<?php echo htmlspecialchars($row['TituloSerie']); ?>">
            </div>
            <div class="form-group">
                <label for="TemaPrincipal">Tema Principal:</label>
                <input type="text" class="form-control" id="TemaPrincipal" name="TemaPrincipal" value="<?php echo htmlspecialchars($row['TemaPrincipal']); ?>">
            </div>
            <div class="form-group">
                <label for="Descriptores">Descriptores:</label>
                <input type="text" class="form-control" id="Descriptores" name="Descriptores" value="<?php echo htmlspecialchars($row['Descriptores']); ?>">
            </div>
            <div class="form-group">
                <label for="Personajes">Personajes:</label>
                <input type="text" class="form-control" id="Personajes" name="Personajes" value="<?php echo htmlspecialchars($row['Personajes']); ?>">
            </div>
            <div class="form-group">
                <label for="InscripcionOriginal">Inscripción Original:</label>
                <input type="text" class="form-control" id="InscripcionOriginal" name="InscripcionOriginal" value="<?php echo htmlspecialchars($row['InscripcionOriginal']); ?>">
            </div>
            <div class="form-group">
                <label for="Conjunto">Conjunto:</label>
                <input type="text" class="form-control" id="Conjunto" name="Conjunto" value="<?php echo htmlspecialchars($row['Conjunto']); ?>">
            </div>
            <div class="form-group">
                <label for="Anotaciones">Anotaciones:</label>
                <input type="text" class="form-control" id="Anotaciones" name="Anotaciones" value="<?php echo htmlspecialchars($row['Anotaciones']); ?>">
            </div>
            <div class="form-group">
                <label for="NumerosInterseccion">Números Intersección:</label>
                <input type="text" class="form-control" id="NumerosInterseccion" name="NumerosInterseccion" value="<?php echo htmlspecialchars($row['NumerosInterseccion']); ?>">
            </div>
            <div class="form-group">
                <label for="DocumentacionAsociada">Documentación Asociada:</label>
                <input type="text" class="form-control" id="DocumentacionAsociada" name="DocumentacionAsociada" value="<?php echo htmlspecialchars($row['DocumentacionAsociada']); ?>">
            </div>
            <br>
            <button type="submit" class="btn btn-primary">Guardar cambios</button>
        </form>
    </div>
</body>

</html>
