<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

require_once 'conexion_BD.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql = "SELECT ImagenOficioVale FROM DatosGenerales WHERE ID_DatosGenerales = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($pdf);
        $stmt->fetch();
        $stmt->close();
        $conn->close();

        // Configurar headers para mostrar el PDF
        header('Content-Type: application/pdf');
        header('Content-Disposition: inline; filename="documento.pdf"');
        echo $pdf;
    } else {
        echo "No se encontró ningún documento.";
    }
} else {
    echo "ID no proporcionado.";
}
