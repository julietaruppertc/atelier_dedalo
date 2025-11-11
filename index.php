<!DOCTYPE html>
<html>
<head>
    <title>Atelier Dédalo</title>
    <link rel="icon" href="Imagenes/2-removebg-preview.png" type="image/png">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php session_start(); 
    include 'conexion_galeria.php';
    include 'menu.php';?>

    <div id="presentacion">
        <img src="Imagenes/Atelier_Dédalo.png" alt="Atelier Dédalo" width="100%">
        <br>
        <p>Nos enorgullecemos de conectar el arte con el mundo. Explora, analiza y participa en subastas de obras de talentosos artistas internacionales.</p>
        <br>
        <a href="about_us.php" class="boton">Conocé más sobre nosotros</a>
    </div>

    <?php

    $sqlObras = "
        SELECT obra.ID_Obra, obra.titulo, obra.imagen, condicion.descripcion AS condicion
        FROM obra
        LEFT JOIN subasta ON obra.ID_Obra = subasta.ID_Obra
        LEFT JOIN condicion ON subasta.ID_Condicion = condicion.ID_Condicion
        ORDER BY condicion.descripcion ASC";
    $resultadoObras = mysqli_query($mysqli, $sqlObras);
    
    $obrasPorCondicion = [
        'Pendiente' => [],
        'En curso' => [],
        'Finalizada' => []
    ];
    
    while ($filaObras = mysqli_fetch_assoc($resultadoObras)) {
        $condicion = $filaObras['condicion'];
        if (isset($obrasPorCondicion[$condicion])) {
            $obrasPorCondicion[$condicion][] = $filaObras;
        }
    }
    ?>

    <div class="contenedor-subastas">
        <?php foreach ($obrasPorCondicion as $condicion => $obras): ?>
            <?php if ($condicion == 'Pendiente' && (!isset($_SESSION['ROL']) || $_SESSION['ROL'] != 0)) continue; ?>
            <div class="subasta <?php echo strtolower(str_replace(' ', '-', $condicion)); ?>">
                <h2 style="text-align: center;">Subasta <?php echo $condicion; ?></h2>
                <div class="grid-subastas">
                    <?php if (count($obras) > 0): ?>
                        <?php foreach ($obras as $obra): ?>
                            <div class="obra">
                                <h3><?php echo $obra['titulo']; ?></h3>
                                <img src="Imagenes/<?php echo $obra['imagen']; ?>" alt="<?php echo $obra['titulo']; ?>" width="250" height="250">
                                <a href="Subasta/subasta_mostrar_obra.php?ID_Obra=<?php echo $obra['ID_Obra']; ?>">Ver Subasta</a>
                                <br>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>No hay obras en esta condición.</p>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <?php
        if (isset($_GET['cerrarsesion'])) {
            session_destroy();
            echo '<script>alert("La sesión se ha cerrado correctamente.");</script>';
            echo '<script>window.location.href = "index.php";</script>';
            exit();
        }
    ?>
</body>
</html>
