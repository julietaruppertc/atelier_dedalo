<?php
include 'conexion_galeria.php';

$ID_Obra = $_GET['ID_Obra'];

$sql = "DELETE FROM obra WHERE ID_Obra='".$ID_Obra."'";

if (mysqli_query($mysqli, $sql)) {
    echo '<script>alert("Obra eliminada correctamente."); 
    window.location.href = "admi_obras.php";</script>';
} else {
    echo '<script>alert("Hubo un error al eliminar la obra."); 
    window.location.href = "admi_obras.php";</script>';
}
?>
