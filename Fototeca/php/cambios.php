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

//Realizar la conexión a la base de datos
$servername = "localhost";
$db_username = "root";
$db_password = "Sandia2016.!";
$dbname = "FototecaBD";

$conn = new mysqli($servername, $db_username, $db_password, $dbname);
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

#-------SeccionTecnica------------------------------
$sql = "SELECT ID_Tecnica, NumeroInventario, ClaveTecnica, 
               ProcesoFotografico, FondoColeccion, Formato, NumeroNegativoCopia,
               Tipo 
        FROM SeccionTecnica";

#-----------Clave-----------------------------------
$sql = "SELECT ID_Tecnica, ID_Cultural 
        FROM Clave";

#----------Datacion---------------------------------
$sql = "SELECT ID_Tecnica, ID_Datacion, FechaAsunto, FechaToma 
        FROM Datacion";

#------Ubicacion Geografica-------------------------
$sql = "SELECT ID_Tecnica, ID_Ubicacion, LugarAsunto, LugarToma 
        FROM UbicacionGeografica";

#----------Epocario--------------------------------
$sql = "SELECT ID_Tecnica, ID_Epoca, Epoca 
        FROM Epocario";

#-----------Autoria---------------------------------
$sql = "SELECT ID_Tecnica, ID_Autoria, Autor, AutorPrimigenio, AgenciaEstudio, 
               EditorColeccionista, Lema 
        FROM Autoria";

#----------Indicativo--------------------------------
$sql = "SELECT ID_Indicativo, ID_Autoria, Sello, Cuño, Firma, Etiqueta, Imprenta, Otro 
        FROM Indicativo";

#-----------Denominacion------------------------------
$sql = "SELECT ID_Tecnica, ID_Denominacion, TituloOrigen, TituloCatalografico, TituloSerie 
        FROM Denominacion";

#-----------Descriptores------------------------------
$sql = "SELECT ID_Tecnica, ID_Descriptores, TemaPrincipal, Descriptores 
        FROM Descriptores";

#------------Protagonistas----------------------------
$sql = "SELECT ID_Tecnica, ID_Protagonistas, Personajes 
        FROM Protagonistas";

#------------Observaciones----------------------------
$sql = "SELECT ID_Tecnica, ID_Observaciones, InscripcionOriginal, Conjunto, Anotaciones, 
               NumerosInterseccion, DocumentacionAsociada 
        FROM Observaciones";

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
                    <th>Acciones</th>
                </tr>
                <tr>
                    <th>ID Cultural</th>
                </tr>
                <tr>
                    <th>ID Datación</th>
                    <th>Fecha Asunto</th>
                    <th>Fecha Toma</th>
                </tr>
                <tr>
                    <th>ID Ubicación</th>
                    <th>Lugar de Asunto</th>
                    <th>Lugar de Toma</th>
                </tr>
                <tr>
                    <th>ID Epoca</th>
                    <th>Epoca</th>
                </tr>
                <tr>
                    <th>ID Autoria</th>
                    <th>Autor</th>
                    <th>Autor Primigenio</th>
                    <th>Agencia/Estudio</th>
                    <th>Editor/Coleccionista</th>
                    <th>Lema</th>
                </tr>
                <tr>
                    <th>ID Indicativo</th>
                    <th>Sello</th>
                    <th>Cuño Primigenio</th>
                    <th>Firma</th>
                    <th>Etiqueta</th>
                    <th>Imprenta</th>
                    <th>Otro</th>
                </tr>
                <tr>
                    <th>ID Denomicnación</th>
                    <th>Título Origen</th>
                    <th>Título Catalográfico</th>
                    <th>Título Serie</th>
                </tr>
                <tr>
                    <th>ID Descriptores</th>
                    <th>Tema Principal</th>
                    <th>Descriptores</th>
                </tr>
                <tr>
                    <th>ID Protagonistas</th>
                    <th>Personajes</th>
                </tr>
                <tr>
                    <th>ID Observaciones</th>
                    <th>Inscripcion Original</th>
                    <th>Conjunto</th>
                    <th>Anotaciones</th>
                    <th>Números Interseccion</th>
                    <th>Documentacion Asociada</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row["ID_Tecnica"] . "</td>";
                        echo "<td>" . $row["NInventario"] . "</td>";
                        echo "<td>" . $row["ClTecnica"] . "</td>";
                        echo "<td>" . $row["PFoto"] . "</td>";
                        echo "<td>" . $row["Fondo"] . "</td>";
                        echo "<td>" . $row["Formato"] . "</td>";
                        echo "<td>" . $row["NCopia"] . "</td>";
                        echo "<td>" . $row["Tipo"] . "</td>";
                        echo "</tr>";

                        echo "<tr>";
                        echo "<td>" . $row["ID_Cultural"] . "</td>";
                        echo "</tr>";

                        echo "<tr>";
                        echo "<td>" . $row["ID_Datacion"] . "</td>";
                        echo "<td>" . $row["FechAsunto"] . "</td>";
                        echo "<td>" . $row["FechToma"] . "</td>";
                        echo "</tr>";

                        echo "<tr>";
                        echo "<td>" . $row["ID_Ubicacion"] . "</td>";
                        echo "<td>" . $row["LugarAsunto"] . "</td>";
                        echo "<td>" . $row["LugarToma"] . "</td>";
                        echo "</tr>";

                        echo "<tr>";
                        echo "<td>" . $row["ID_Epoca"] . "</td>";
                        echo "<td>" . $row["Epoca"] . "</td>";
                        echo "</tr>";

                        echo "<tr>";
                        echo "<td>" . $row["ID_Autoria"] . "</td>";
                        echo "<td>" . $row["Autor"] . "</td>";
                        echo "<td>" . $row["Autor_Primi"] . "</td>";
                        echo "<td>" . $row["Agencia"] . "</td>";
                        echo "<td>" . $row["Editor"] . "</td>";
                        echo "<td>" . $row["Lema"] . "</td>";
                        echo "</tr>";

                        echo "<tr>";
                        echo "<td>" . $row["ID_Indicativo"] . "</td>";
                        echo "<td>" . $row["Sello"] . "</td>";
                        echo "<td>" . $row["Cuno"] . "</td>";
                        echo "<td>" . $row["Firma"] . "</td>";
                        echo "<td>" . $row["Etiqueta"] . "</td>";
                        echo "<td>" . $row["Imprenta"] . "</td>";
                        echo "<td>" . $row["Otro"] . "</td>";
                        echo "</tr>";
                        
                        echo "<tr>";
                        echo "<td>" . $row["ID_Denominacion"] . "</td>";
                        echo "<td>" . $row["TitOrigen"] . "</td>";
                        echo "<td>" . $row["TitCatalo"] . "</td>";
                        echo "<td>" . $row["TitSerie"] . "</td>";
                        echo "</tr>";

                        echo "<tr>";
                        echo "<td>" . $row["ID_Descriptores"] . "</td>";
                        echo "<td>" . $row["TemaPrin"] . "</td>";
                        echo "<td>" . $row["Descriptores"] . "</td>";
                        echo "</tr>";

                        echo "<tr>";
                        echo "<td>" . $row["ID_Protagonistas"] . "</td>";
                        echo "<td>" . $row["Personajes"] . "</td>";
                        echo "</tr>";

                        echo "<tr>";
                        echo "<td>" . $row["ID_Observaciones"] . "</td>";
                        echo "<td>" . $row["InscripOriginal"] . "</td>";
                        echo "<td>" . $row["Conjunto"] . "</td>";
                        echo "<td>" . $row["Anotaciones"] . "</td>";
                        echo "<td>" . $row["NInterseccion"] . "</td>";
                        echo "<td>" . $row["DocAsociada"] . "</td>";
                        echo "</tr>";
                        
                    }
                } else {
                    echo "<tr><td colspan='9'>No hay registros para mostrar</td></tr>";
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