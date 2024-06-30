<?php
// Inicia sesión y verifica si el usuario está autenticado
session_start();
if (!isset($_SESSION['username'])) {
    // Redirige a la página de inicio de sesión si no hay una sesión activa
    header("Location: index.php");
    exit();
}

// Incluye el archivo de conexión a la base de datos
require_once 'conexion_BD.php';

// Verifica si el ID del documento ha sido proporcionado en la URL
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Prepara la consulta SQL para seleccionar el documento PDF
    $sql = "SELECT ImagenOficioVale, ImagenObra FROM DatosGenerales WHERE ID_DatosGenerales = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->store_result();

    // Verifica si se encontró un registro con el ID proporcionado
    if ($stmt->num_rows > 0) {
        // Extrae el contenido del documento PDF
        $stmt->bind_result($pdfOficio, $pdfObra);
        $stmt->fetch();
        $stmt->close();
        $conn->close();

        // Comprobar cuál PDF mostrar basado en un parámetro adicional en la URL
        if (isset($_GET['type']) && $_GET['type'] == 'obra') {
            $pdf = $pdfObra;
        } else {
            $pdf = $pdfOficio;
        }

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
