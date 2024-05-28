<?php
session_start();

// Verificar si el usuario está autenticado
//if (!isset($_SESSION['username'])) {
    // Si no está autenticado, redirigirlo a la página de inicio de sesión
    //header("Location: login.php");
    //exit();
//}

// Si el usuario está autenticado, mostrar el nombre de usuario
//$username = $_SESSION['username'];

// Realizar la conexión a la base de datos
$servername = "localhost";
$db_username = "root";
$db_password = "Sandia2016.!";
$dbname = "Fototeca_uaa";

$conn = new mysqli($servername, $db_username, $db_password, $dbname);
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Consulta unificada para obtener los datos de todas las tablas relacionadas
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
        LEFT JOIN Observaciones O ON ST.ID_Tecnica = O.ID_Tecnica";

$result = $conn->query($sql);

// Cerrar la conexión a la base de datos
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cambios</title>
    <link rel="stylesheet" href="../css/login.css">
    <link rel="stylesheet" href="../css/tablas.css">
</head>

<body>
    <?php
    $pageTitle = "Cambios";
    include 'header.php';
    echo '<br>';
    echo '<h1 class="text-center">Cambios</h1>';
    ?>

    <div class="container-back">
        <button onclick="goBack()" class="btn btn-secondary mt-3">Regresar</button>
    </div>

    <div class="container mt-5">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID Técnica</th>
                    <th>Número Inventario</th>
                    <th>Clave Técnica</th>
                    <th>Proceso Fotográfico</th>
                    <th>Fondo Colección</th>
                    <th>Formato</th>
                    <th>Número Negativo/Copia</th>
                    <th>Tipo</th>
                    <th>ID Cultural</th>
                    <th>ID Datación</th>
                    <th>Fecha Asunto</th>
                    <th>Fecha Toma</th>
                    <th>ID Ubicación</th>
                    <th>Lugar de Asunto</th>
                    <th>Lugar de Toma</th>
                    <th>ID Epoca</th>
                    <th>Epoca</th>
                    <th>ID Autoria</th>
                    <th>Autor</th>
                    <th>Autor Primigenio</th>
                    <th>Agencia/Estudio</th>
                    <th>Editor/Coleccionista</th>
                    <th>Lema</th>
                    <th>ID Indicativo</th>
                    <th>Sello</th>
                    <th>Cuño Primigenio</th>
                    <th>Firma</th>
                    <th>Etiqueta</th>
                    <th>Imprenta</th>
                    <th>Otro</th>
                    <th>ID Denominacion</th>
                    <th>Título Origen</th>
                    <th>Título Catalográfico</th>
                    <th>Título Serie</th>
                    <th>ID Descriptores</th>
                    <th>Tema Principal</th>
                    <th>Descriptores</th>
                    <th>ID Protagonistas</th>
                    <th>Personajes</th>
                    <th>ID Observaciones</th>
                    <th>Inscripcion Original</th>
                    <th>Conjunto</th>
                    <th>Anotaciones</th>
                    <th>Números Intersección</th>
                    <th>Documentacion Asociada</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row["ID_Tecnica"] . "</td>";
                        echo "<td>" . $row["NumeroInventario"] . "</td>";
                        echo "<td>" . $row["ClaveTecnica"] . "</td>";
                        echo "<td>" . $row["ProcesoFotografico"] . "</td>";
                        echo "<td>" . $row["FondoColeccion"] . "</td>";
                        echo "<td>" . $row["Formato"] . "</td>";
                        echo "<td>" . $row["NumeroNegativoCopia"] . "</td>";
                        echo "<td>" . $row["Tipo"] . "</td>";
                        echo "<td>" . $row["ID_Cultural"] . "</td>";
                        echo "<td>" . $row["ID_Datacion"] . "</td>";
                        echo "<td>" . $row["FechaAsunto"] . "</td>";
                        echo "<td>" . $row["FechaToma"] . "</td>";
                        echo "<td>" . $row["ID_Ubicacion"] . "</td>";
                        echo "<td>" . $row["LugarAsunto"] . "</td>";
                        echo "<td>" . $row["LugarToma"] . "</td>";
                        echo "<td>" . $row["ID_Epoca"] . "</td>";
                        echo "<td>" . $row["Epoca"] . "</td>";
                        echo "<td>" . $row["ID_Autoria"] . "</td>";
                        echo "<td>" . $row["Autor"] . "</td>";
                        echo "<td>" . $row["AutorPrimigenio"] . "</td>";
                        echo "<td>" . $row["AgenciaEstudio"] . "</td>";
                        echo "<td>" . $row["EditorColeccionista"] . "</td>";
                        echo "<td>" . $row["Lema"] . "</td>";
                        echo "<td>" . $row["ID_Indicativo"] . "</td>";
                        echo "<td>" . $row["Sello"] . "</td>";
                        echo "<td>" . $row["Cuño"] . "</td>";
                        echo "<td>" . $row["Firma"] . "</td>";
                        echo "<td>" . $row["Etiqueta"] . "</td>";
                        echo "<td>" . $row["Imprenta"] . "</td>";
                        echo "<td>" . $row["Otro"] . "</td>";
                        echo "<td>" . $row["ID_Denominacion"] . "</td>";
                        echo "<td>" . $row["TituloOrigen"] . "</td>";
                        echo "<td>" . $row["TituloCatalografico"] . "</td>";
                        echo "<td>" . $row["TituloSerie"] . "</td>";
                        echo "<td>" . $row["ID_Descriptores"] . "</td>";
                        echo "<td>" . $row["TemaPrincipal"] . "</td>";
                        echo "<td>" . $row["Descriptores"] . "</td>";
                        echo "<td>" . $row["ID_Protagonistas"] . "</td>";
                        echo "<td>" . $row["Personajes"] . "</td>";
                        echo "<td>" . $row["ID_Observaciones"] . "</td>";
                        echo "<td>" . $row["InscripcionOriginal"] . "</td>";
                        echo "<td>" . $row["Conjunto"] . "</td>";
                        echo "<td>" . $row["Anotaciones"] . "</td>";
                        echo "<td>" . $row["NumerosInterseccion"] . "</td>";
                        echo "<td>" . $row["DocumentacionAsociada"] . "</td>";
                        echo "<td><a href='procesar_cambios-t2.php?id=" . $row["ID_Tecnica"] . "' class='btn btn-primary'>Cambiar</a></td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='46'>No hay registros para mostrar</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <script>
        function goBack() {
            window.history.back();
        }
    </script>
</body>

</html>
