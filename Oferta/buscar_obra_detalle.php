<?php
include 'conexion_galeria.php'; // Asegúrate de que este archivo contenga la conexión a la base de datos

if (isset($_GET['titulo'])) {
    $titulo_obra = mysqli_real_escape_string($mysqli, $_GET['titulo']); // Sanitiza la entrada

    // Llamada al procedimiento almacenado
    $sqlSubasta = "CALL ObtenerInformacionSubastaPorTitulo('$titulo_obra')";
    $resultSubasta = mysqli_query($mysqli, $sqlSubasta);

    if ($resultSubasta && mysqli_num_rows($resultSubasta) > 0) {
        echo '<h3>Detalles de la Subasta</h3>
              <table border="1" style="margin: 10px auto;">
                  <thead>
                      <tr>
                          <th>Obra</th>
                          <th>Artista</th>
                          <th>Cantidad de Ofertas</th>
                          <th>Oferta más alta</th>
                          <th>Fecha de Inicio</th>
                          <th>Fecha de Fin</th>
                          <th>Condición</th>
                      </tr>
                  </thead>
                  <tbody>';

        while ($subasta = mysqli_fetch_assoc($resultSubasta)) {
            echo "<tr>
                    <td>{$subasta['nombre_obra']}</td>
                    <td>{$subasta['nombre_artista']}</td>
                    <td>{$subasta['cantidad_ofertas']}</td>
                    <td>{$subasta['oferta_mas_alta']}</td>
                    <td>{$subasta['fecha_inicio']}</td>
                    <td>{$subasta['fecha_fin']}</td>
                    <td>{$subasta['condicion']}</td>
                </tr>";
        }
        echo "</tbody></table>";

        // Liberar el resultado del procedimiento almacenado
        mysqli_next_result($mysqli);

        // Consulta adicional para obtener las ofertas
        $sqlOfertas = "
        SELECT 
            oferta.propuesta AS monto, 
            oferta.dia_hora, 
            oferta.DNI_Usuario,
            usuario.nombre AS nombre_usuario,
            usuario.apellido AS apellido_usuario
        FROM oferta
        INNER JOIN subasta ON oferta.ID_Subasta = subasta.ID_Subasta
        INNER JOIN obra ON subasta.ID_Obra = obra.ID_Obra
        INNER JOIN usuario ON oferta.DNI_Usuario = usuario.DNI_Usuario
        WHERE obra.titulo LIKE '%$titulo_obra%'
        ORDER BY oferta.dia_hora DESC";

        $resultOfertas = mysqli_query($mysqli, $sqlOfertas);

        echo "<h3>Detalles de las Ofertas</h3>";

        if ($resultOfertas && mysqli_num_rows($resultOfertas) > 0) {
            echo '<table border="1" style="margin: 10px auto;">
                    <thead>
                        <tr>
                            <th>DNI</th>
                            <th>Nombre y apellido del Usuario</th>
                            <th>Monto</th>
                            <th>Día y Hora</th>
                        </tr>
                    </thead>
                    <tbody>';

            while ($oferta = mysqli_fetch_assoc($resultOfertas)) {
                echo "<tr>
                        <td>{$oferta['DNI_Usuario']}</td>
                        <td>" . htmlspecialchars($oferta['nombre_usuario'] . " " . $oferta['apellido_usuario']) . "</td>
                        <td>{$oferta['monto']}</td>
                        <td>{$oferta['dia_hora']}</td>
                    </tr>";
            }
            echo "</tbody></table>";
        } else {
            echo "<p>No hay ofertas registradas para esta obra.</p>";
        }
    } else {
        echo "<p>No se encontraron subastas para la obra seleccionada.</p>";
    }

    // Verifica si hay más resultados pendientes y los libera
    while (mysqli_more_results($mysqli)) {
        mysqli_next_result($mysqli);
    }
}
?>