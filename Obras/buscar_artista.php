<?php
include 'conexion_galeria.php';

if (isset($_POST['busqueda'])) {
    $busqueda = mysqli_real_escape_string($mysqli, $_POST['busqueda']);
    $sql = "SELECT ID_Artista, CONCAT(nombre, ' ', apellido) AS nombre_completo 
            FROM artista 
            WHERE nombre LIKE '%$busqueda%' OR apellido LIKE '%$busqueda%'";
    $resultado = mysqli_query($mysqli, $sql);

    if (mysqli_num_rows($resultado) > 0) {
        while ($fila = mysqli_fetch_assoc($resultado)) {
            echo '<div class="sugerencia" data-id="' . $fila['ID_Artista'] . '">' . $fila['nombre_completo'] . '</div>';
        }
    } else {
        echo '<div>No se encontraron resultados</div>';
    }
}
?>
