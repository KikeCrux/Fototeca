<?php
session_start();

// Realizar la conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "Trompudo117";
$dbname = "fototeca_uaa";

$conn = new mysqli($servername,$username, $password, $dbname);
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

$id_DatosGenerales = $_GET['id'];

// Obtener la información actual del registro a modificar
$sql = "SELECT * FROM Fototeca WHERE ID_Tecnica = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_DatosGenerales);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "No se encontró ningún registro con el ID proporcionado.";
    exit();
}

$row = $result->fetch_assoc();

// Procesar el formulario de cambios
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Limpiar y validar los datos recibidos del formulario (puedes implementar una función limpiar_entrada si es necesario)
    $nuevo_numero_inventario = $_POST['NumeroInventario'];
    $nueva_clave_tecnica = $_POST['ClaveTecnica'];
    $nuevo_proceso_fotografico = $_POST['ProcesoFotografico'];
    $nuevo_fondo_coleccion = $_POST['FondoColeccion'];
    $nuevo_formato = $_POST['Formato'];
    $nuevo_numero_negativo = $_POST['NumeroNegativoCopia'];
    $nuevo_tipo = $_POST['Tipo'];
    $nuevo_fecha_asunto = $_POST['FechaAsunto'];
    $nuevo_fecha_toma = $_POST['FechaToma'];
    $nuevo_lugar_asunto = $_POST['LugarAsunto'];
    $nuevo_lugar_toma = $_POST['LugarToma'];
    $nuevo_epoca = $_POST['Epoca'];
    $nuevo_autor = $_POST['Autor'];
    $nuevo_autor_primigenio = $_POST['AutorPrimigenio'];
    $nuevo_agencia_estudio = $_POST['AgenciaEstudio'];
    $nuevo_editor_coleccionista = $_POST['EditorColeccionista'];
    $nuevo_lema = $_POST['Lema'];
    $nuevo_sello = $_POST['Sello'];
    $nuevo_cuño = $_POST['Cuño'];
    $nuevo_firma = $_POST['Firma'];
    $nuevo_etiqueta = $_POST['Etiqueta'];
    $nuevo_imprenta = $_POST['Imprenta'];
    $nuevo_otro = $_POST['Otro'];
    $nuevo_titulo_origen = $_POST['TituloOrigen'];
    $nuevo_titulo_catalografico = $_POST['TituloCatalografico'];
    $nuevo_titulo_serie = $_POST['TituloSerie'];
    $nuevo_tema_principal = $_POST['TemaPrincipal'];
    $nuevo_descriptores = $_POST['Descriptores'];
    $nuevo_personajes = $_POST['Personajes'];
    $nuevo_inscripcion_original = $_POST['InscripcionOriginal'];
    $nuevo_conjunto = $_POST['Conjunto'];
    $nuevo_anotaciones = $_POST['Anotaciones'];
    $nuevo_numeros_interseccion = $_POST['NumerosInterseccion'];
    $nuevo_documentacion_asociada = $_POST['DocumentacionAsociada'];

    // Actualizar el registro en la tabla Fototeca
    $sql_update = "UPDATE Fototeca SET 
                    NumeroInventario = ?, 
                    ClaveTecnica = ?, 
                    ProcesoFotografico = ?, 
                    FondoColeccion = ?, 
                    Formato = ?, 
                    NumeroNegativoCopia = ?, 
                    Tipo = ?, 
                    FechaAsunto = ?, 
                    FechaToma = ?, 
                    LugarAsunto = ?, 
                    LugarToma = ?, 
                    Epoca = ?, 
                    Autor = ?, 
                    AutorPrimigenio = ?, 
                    AgenciaEstudio = ?, 
                    EditorColeccionista = ?, 
                    Lema = ?, 
                    Sello = ?, 
                    Cuño = ?, 
                    Firma = ?, 
                    Etiqueta = ?, 
                    Imprenta = ?, 
                    Otro = ?, 
                    TituloOrigen = ?, 
                    TituloCatalografico = ?, 
                    TituloSerie = ?, 
                    TemaPrincipal = ?, 
                    Descriptores = ?, 
                    Personajes = ?, 
                    InscripcionOriginal = ?, 
                    Conjunto = ?, 
                    Anotaciones = ?, 
                    NumerosInterseccion = ?, 
                    DocumentacionAsociada = ?
                    WHERE ID_Tecnica = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param(
        "ssssssssssssssssssssssssssssssssssi",
        $nuevo_numero_inventario,
        $nueva_clave_tecnica,
        $nuevo_proceso_fotografico,
        $nuevo_fondo_coleccion,
        $nuevo_formato,
        $nuevo_numero_negativo,
        $nuevo_tipo,
        $nuevo_fecha_asunto,
        $nuevo_fecha_toma,
        $nuevo_lugar_asunto,
        $nuevo_lugar_toma,
        $nuevo_epoca,
        $nuevo_autor,
        $nuevo_autor_primigenio,
        $nuevo_agencia_estudio,
        $nuevo_editor_coleccionista,
        $nuevo_lema,
        $nuevo_sello,
        $nuevo_cuño,
        $nuevo_firma,
        $nuevo_etiqueta,
        $nuevo_imprenta,
        $nuevo_otro,
        $nuevo_titulo_origen,
        $nuevo_titulo_catalografico,
        $nuevo_titulo_serie,
        $nuevo_tema_principal,
        $nuevo_descriptores,
        $nuevo_personajes,
        $nuevo_inscripcion_original,
        $nuevo_conjunto,
        $nuevo_anotaciones,
        $nuevo_numeros_interseccion,
        $nuevo_documentacion_asociada,
        $id_DatosGenerales
    );

    if ($stmt_update->execute()) {
        header("Location: cambios.php?success=true");
        exit();
    } else {
        echo "Error al actualizar el registro: " . $stmt_update->error;
    }
}

// Cerrar conexión
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
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?id=' . $id_DatosGenerales; ?>" method="post" enctype="multipart/form-data">
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