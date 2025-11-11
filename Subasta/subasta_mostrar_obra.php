<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Obras</title>
    <link rel="icon" href="../Imagenes/2-removebg-preview.png" type="image/png">
    <link rel="stylesheet" href="Styles.css">
</head>
<body>
    <?php
    session_start();
    include 'conexion_galeria.php';
    include('../menu.php');

    // Cerrar subastas automáticamente si la fecha de fin ha pasado
    $updateQuery = "UPDATE subasta SET ID_Condicion = 3 WHERE fecha_fin <= CURDATE() AND ID_Condicion != 3";
    mysqli_query($mysqli, $updateQuery);

    if (isset($_GET['ID_Obra'])) {
        $ID_Obra = $_GET['ID_Obra'];

        # Consulta de datos de la obra, artista, subasta inicial y detalles adicionales
        $sqlObras = "
            SELECT obra.titulo, obra.fecha, artista.nombre AS nombre_artista, artista.apellido AS apellido_artista, 
                obra.imagen, subasta.valor_inicial, subasta.ID_Condicion, subasta.ID_Subasta, 
                subasta.fecha_inicio, subasta.fecha_fin, condicion.descripcion AS condicion,
                detalle_obra.detalles, detalle_obra.alto, detalle_obra.ancho, detalle_obra.profundidad,
                material.descripcion AS descripcion_material, tecnica.descripcion AS descripcion_tecnica
            FROM obra
            INNER JOIN artista ON obra.ID_Artista = artista.ID_Artista 
            LEFT JOIN subasta ON obra.ID_Obra = subasta.ID_Obra
            LEFT JOIN condicion ON subasta.ID_Condicion = condicion.ID_Condicion
            LEFT JOIN detalle_obra ON obra.ID_Detalle = detalle_obra.ID_Detalle
            LEFT JOIN material ON detalle_obra.ID_Material = material.ID_Material
            LEFT JOIN tecnica ON detalle_obra.ID_Tecnica = tecnica.ID_Tecnica
            WHERE obra.ID_Obra = $ID_Obra";

        $resultadoObras = mysqli_query($mysqli, $sqlObras);
        $filaObras = mysqli_fetch_assoc($resultadoObras); // Solo un resultado esperado

        // Obtener la oferta más alta si la subasta está en curso o finalizada
        $valorFinal = null;
        if ($filaObras['ID_Condicion'] == 2 || $filaObras['ID_Condicion'] == 3) {
            $sqlOfertaMax = "SELECT MAX(propuesta) AS oferta_maxima FROM oferta WHERE ID_Subasta = " . $filaObras['ID_Subasta'];
            $resultadoOfertaMax = mysqli_query($mysqli, $sqlOfertaMax);
            $filaOfertaMax = mysqli_fetch_assoc($resultadoOfertaMax);
            $valorFinal = $filaOfertaMax['oferta_maxima'] ?? $filaObras['valor_inicial'];
        }
    }
    ?>
    <div id="obra">
    <?php
    if (isset($filaObras)) { ?>
            <!-- Imagen a la derecha -->
            <div class="imagen-principal">
                <img src="../Imagenes/<?php echo $filaObras['imagen']; ?>" width="350" height="350">
            </div>

            <!-- Información de la obra y detalles a la izquierda -->
            <div class="info-obra">
                <div class="antes-imagen">
                    <h2><?php echo $filaObras['titulo']; ?></h2>
                    <p><strong>Fecha:</strong> <?php echo $filaObras['fecha']; ?></p>
                    <p><strong>Artista:</strong> <?php echo $filaObras['nombre_artista'] . ' ' . $filaObras['apellido_artista']; ?></p>
                    <p><strong>Subasta inicial:</strong> $<?php echo number_format($filaObras['valor_inicial'], 2); ?></p>
                    <p><strong>Condición:</strong> <?php echo $filaObras['condicion']; ?></p>
                    <p><strong>Fecha inicio de subasta:</strong> <?php echo $filaObras['fecha_inicio']; ?></p>
                    <p><strong>Fecha fin de subasta:</strong> <?php echo $filaObras['fecha_fin']; ?></p>

                    <?php if ($filaObras['ID_Condicion'] == 2) { ?>
                        <p><strong>Oferta más alta actual:</strong> $<?php echo number_format($valorFinal, 2); ?></p>
                    <?php } elseif ($filaObras['ID_Condicion'] == 3) { ?>
                        <p><strong>Valor de cierre:</strong> $<?php echo number_format($valorFinal, 2); ?></p>
                    <?php } ?>
                </div>
                <br>
                <div class="despues-imagen">             
                    <p><strong>Alto:</strong> <?php echo $filaObras['alto']; ?> cm</p>
                    <p><strong>Ancho:</strong> <?php echo $filaObras['ancho']; ?> cm</p>
                    <p><strong>Profundidad:</strong> <?php echo $filaObras['profundidad']; ?> cm</p>
                    <p><strong>Material:</strong> <?php echo $filaObras['descripcion_material']; ?></p>
                    <p><strong>Técnica:</strong> <?php echo $filaObras['descripcion_tecnica']; ?></p>
                    <p><strong>Detalles:</strong> <?php echo $filaObras['detalles']; ?></p>
                <br>

                   <!-- Botón de actualizar, visible solo para administradores y solo si la subasta no está finalizada -->
                    <?php if (isset($_SESSION['ROL']) && $_SESSION['ROL'] == 0 && $filaObras['ID_Condicion'] != 3) { ?>
                        <a href="subasta_modificar.php?ID_Obra=<?php echo $ID_Obra; ?>"><button>Modificar</button></a>
                    <?php } 
                        if (isset($filaObras['ID_Subasta'])) { ?> 
                        <?php if (!empty($_SESSION['DNI_Usuario']) && $_SESSION['ROL'] == 1 && $filaObras['ID_Condicion'] != 3) { ?>
                            <a href="../Oferta/oferta_alta.php?ID_Subasta=<?php echo $filaObras['ID_Subasta']; ?>">
                                <button>Ofertar</button>
                            </a>
                        <?php } elseif (!isset($_SESSION['DNI_Usuario'])) { ?>
                            <p style="text-align: center;">¡Para poder ofertar tenes que registrarte!</p>
                            <a href="../Usuarios/login.php">
                                <button>Registrarse</button>
                            </a>
                        <?php } ?>
                    <?php } ?>
                </div>
            </div>
        <?php } else { ?>
            <p>No se encontró información para esta obra.</p>
        <?php } ?>
    </div>
</body>
</html>
