<?php
/* Verificar si el usuario está autenticado
if (!isset($_SESSION['username'])) {
    // Si no está autenticado, redirigirlo a la página de inicio de sesión
    header("Location: login.php");
    exit();
}*/

// Si el usuario está autenticado, mostrar el nombre de usuario
//$username = $_SESSION['username'];

// Realizar la conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "Trompudo117";
$dbname = "fototeca_uaa";


$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
echo "Si entre a el archivo";
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
echo $id;
if ($id > 0) {
    $stmt = $conn->prepare("SELECT DocumentacionAsociada FROM Fototeca WHERE ID_Tecnica = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($pdf_data);
    $stmt->fetch();

    if ($stmt->num_rows > 0) {
        header("Content-Type: application/pdf");
        header("Content-Disposition: inline; filename=document.pdf");
        echo "Voy a mostrar el pdf";
        echo $pdf_data;
    } else {
        echo "Archivo no encontrado.";
    }

    $stmt->close();
} else {
    echo "ID inválido.";
}
$conn->close();

/* Verifica si el ID del documento ha sido proporcionado en la URL
if (isset($_GET['id'])) {
$id = intval($_GET['id']);

// Prepara la consulta SQL para seleccionar el documento PDF
$sql = "SELECT DocumentacionAsociada FROM Fototeca WHERE ID_Tecnica = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->store_result();

// Verifica si se encontró un registro con el ID proporcionado
if ($stmt->num_rows > 0) {
// Extrae el contenido del documento PDF
$stmt->bind_result($pdf1);
$stmt->fetch();
$stmt->close();
$conn->close();

$pdf = $pdf1;


// Configura los encabezados para la respuesta HTTP para mostrar el PDF
header('Content-Type: application/pdf');
header('Content-Disposition: inline; filename="documento.pdf"');
echo $pdf;
} else {
// Mensaje de error si no se encuentra el documento
echo "No se encontró ningún documento.";
}
} else {
// Mensaje de error si no se proporciona un ID
echo "ID no proporcionado.";
}
*/