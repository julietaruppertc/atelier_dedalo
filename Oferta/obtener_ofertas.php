<?php
include 'conexion_galeria.php';

if (isset($_GET['id_subasta'])) {
    $idSubasta = $_GET['id_subasta'];
    $sql = "SELECT contar_ofertas_subasta($idSubasta) AS total_ofertas";
    $result = mysqli_query($mysqli, $sql);

    if ($result) {
        $data = mysqli_fetch_assoc($result);
        echo json_encode($data); // Retorna los datos como JSON
    } else {
        echo json_encode(['total_ofertas' => 0]); // Si no hay ofertas, devolvemos 0
    }
}
?>
