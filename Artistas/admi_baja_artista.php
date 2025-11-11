<?php
include 'conexion_galeria.php';
session_start();

$ID_Artista = $_GET['ID_Artista'];

$sql = "DELETE FROM artista WHERE ID_Artista = '$ID_Artista'";

if (mysqli_query($mysqli, $sql)) {
    // Si la eliminación fue exitosa, mostrar el alert y redirigir
    echo '<script>
            alert("El artista ha sido eliminado correctamente.");
            window.location.href = "admi_artistas.php";
          </script>';
} else {
    // Si hubo un error con la eliminación, mostrar un mensaje de error
    echo '<script>
            alert("Hubo un error al eliminar el artista.");
            window.location.href = "admi_artistas.php";
          </script>';
}
?>
