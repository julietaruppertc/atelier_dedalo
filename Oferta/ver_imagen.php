<?php
session_start();
include 'conexion_galeria.php';

if (isset($_GET['ID_Obra'])) {
    $ID_Obra = $_GET['ID_Obra'];

    // Consulta para obtener el nombre del archivo de la imagen
    $sql = "SELECT imagen FROM obra WHERE ID_Obra = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("i", $ID_Obra);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($imagen);

    if ($stmt->fetch()) {
        // Verificar si se obtuvo el nombre del archivo
        if ($imagen) {
            $imagePath = "../Imagenes/" . $imagen;  // Ruta de la imagen almacenada en la carpeta Imagenes
            if (file_exists($imagePath)) {
                header("Content-Type: image/jpeg");  // Cambiar según el tipo de archivo (por ejemplo, image/png, image/jpeg)
                readfile($imagePath);  // Muestra la imagen
            } else {
                echo "La imagen no se encuentra en el directorio.";
            }
        } else {
            echo "Imagen no encontrada en la base de datos.";
        }
    } else {
        echo "No se encontró la obra con ID: $ID_Obra.";
    }

    $stmt->close();
} else {
    echo "ID de obra no proporcionado.";
}
?>
