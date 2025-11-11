<?php
include 'conexion_galeria.php';

if (isset($_GET['obra'])) {
    $obra = mysqli_real_escape_string($mysqli, $_GET['obra']);

    $sql = "SELECT titulo FROM obra WHERE titulo LIKE '%$obra%' LIMIT 5";
    $result = mysqli_query($mysqli, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        echo '<div id="sugerencias" class="sugerencias-container">';
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<div class="sugerencia" onclick="seleccionarObra(\'' . $row['titulo'] . '\')">' . $row['titulo'] . '</div>';
        }
        echo '</div>';
    } else {
        echo '<div id="sugerencias" class="sugerencias-container"><div class="no-result">No se encontraron obras</div></div>';
    }
}
?>
