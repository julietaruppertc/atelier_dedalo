<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ver Ofertas</title>
    <link rel="icon" href="../Imagenes/2-removebg-preview.png" type="image/png">
    <link rel="stylesheet" href="STYLES.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        .sugerencia {
            padding: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease, box-shadow 0.3s ease;
        }

        .sugerencia:hover {
            background-color: #f0f0f0;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>
<body>
    <?php
        session_start();
        include 'conexion_galeria.php';
        include('../menu.php');
    ?>

    <div id="tabla_buscar">
        <fieldset class="form-fieldset">
            <legend>Buscar Obra de Arte</legend>
            <label for="obra">Ingrese el nombre de la obra:</label>
            <input type="text" id="obra" name="obra" onkeyup="sugerirObras()">
            <ul id="sugerencias" style="border: 1px solid #ccc; display: none; padding: 5px;"></ul>
            <button onclick="buscarSubasta()">Buscar</button>
        </fieldset>
        <div id="resultados"></div>
    </div>

    <div id="tabla_vista">
        <?php
            echo '<h2 style="margin: 0 auto;">Ofertas Realizadas por Todos los Usuarios</h2>';
            $sqlSubastas = "SELECT * FROM vista_ofertas ORDER BY `Condición`";
            $resultSubastas = mysqli_query($mysqli, $sqlSubastas);

            if ($resultSubastas && mysqli_num_rows($resultSubastas) > 0) {
                echo '<table border="1">
                        <thead>
                            <tr>
                                <th>Usuario</th>
                                <th>Correo Electrónico</th>
                                <th>Teléfono</th>
                                <th>Obra</th>
                                <th>Artista</th>
                                <th>Oferta</th>
                                <th>Fecha de Inicio</th>
                                <th>Fecha de Fin</th>
                                <th>Condición</th>
                            </tr>
                        </thead>
                        <tbody>';
                while ($subasta = mysqli_fetch_assoc($resultSubastas)) {
                    echo "<tr>
                        <td>{$subasta['Usuario']}</td>
                        <td>{$subasta['Correo Electrónico']}</td>
                        <td>{$subasta['Teléfono']}</td>
                        <td><a href='../Subasta/subasta_mostrar_obra.php?ID_Obra={$subasta['ID_Obra']}'>{$subasta['Obra']}</a></td>
                        <td>{$subasta['Artista']}</td>
                        <td>$" . number_format($subasta['Oferta']) . "</td>
                        <td>{$subasta['Fecha de Inicio']}</td>
                        <td>{$subasta['Fecha de Fin']}</td>
                        <td>{$subasta['Condición']}</td>
                    </tr>";
                }
                echo "</tbody></table>";
            } else {
                echo "<p>No hay ofertas para mostrar.</p>";
            }

            if (isset($_GET['titulo'])) {
                $titulo = mysqli_real_escape_string($mysqli, $_GET['titulo']);

                $sqlObra = "SELECT ID_Obra FROM obra WHERE titulo = '$titulo'";
                $resultObra = mysqli_query($mysqli, $sqlObra);

                if ($resultObra && mysqli_num_rows($resultObra) > 0) {
                    $rowObra = mysqli_fetch_assoc($resultObra);
                    $idObra = $rowObra['ID_Obra'];

                    $sql = "SELECT o.ID_Oferta, o.monto, u.nombre AS usuario
                            FROM oferta o
                            JOIN usuario u ON o.ID_Usuario = u.ID_Usuario
                            WHERE o.ID_Obra = $idObra";

                    $result = mysqli_query($mysqli, $sql);

                    echo "<h3>Detalles de las Ofertas</h3>";
                    if ($result && mysqli_num_rows($result) > 0) {
                        echo "<ul>";
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<li>Oferta #{$row['ID_Oferta']}: \${$row['monto']} - Usuario: {$row['usuario']}</li>";
                        }
                        echo "</ul>";
                    } else {
                        echo "<p>No hay ofertas registradas para esta obra.</p>";
                    }
                } else {
                    echo "<p>Obra no encontrada.</p>";
                }
            }
        ?>
    </div>

    <script>
        function sugerirObras() {
            var obra = document.getElementById("obra").value;
            if (obra.length < 1) {
                document.getElementById("sugerencias").style.display = "none";
                return;
            }
            $.ajax({
                url: 'sugerencias_obras.php',
                type: 'GET',
                data: { obra: obra },
                success: function(response) {
                    if (response.trim() !== "") {
                        document.getElementById("sugerencias").style.display = "block";
                        document.getElementById("sugerencias").innerHTML = response;
                    } else {
                        document.getElementById("sugerencias").style.display = "none";
                    }
                }
            });
        }

        function seleccionarObra(nombre) {
            document.getElementById("obra").value = nombre;
            document.getElementById("sugerencias").style.display = "none";
        }

        function buscarSubasta() {
            var obra = document.getElementById("obra").value;
            $.ajax({
                url: 'buscar_subasta.php',
                type: 'GET',
                data: { obra: obra },
                success: function(response) {
                    document.getElementById("resultados").innerHTML = response;
                }
            });
        }
    </script>
</body>
</html>
