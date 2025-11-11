<?php
session_start();
include 'conexion_galeria.php';

if(isset($_SESSION['DNI_Usuario'])) {
    $DNI_Usuario = $_SESSION['DNI_Usuario'];
    $sql = "UPDATE usuario SET estado = 'B' WHERE DNI_Usuario='".$DNI_Usuario."'";
    mysqli_query($mysqli, $sql);

    session_destroy(); // se cierra la sesión
    echo '<script>alert("Usuario eliminado correctamente");</script>';
    echo '<script>window.location.href = "../index.php";</script>';
    exit();
}
?>
