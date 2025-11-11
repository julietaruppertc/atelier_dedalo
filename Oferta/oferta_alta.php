<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Realizar oferta</title>
    <link rel="icon" href="../Imagenes/2-removebg-preview.png" type="image/png">
    <link rel="stylesheet" href="STYLES.css">
</head>
<body>
    <?php
        session_start();
        include 'conexion_galeria.php';
        include('../menu.php');

        if (!isset($_GET['ID_Subasta'])) {
            echo "<script>alert('Error: ID de subasta no proporcionado.'); window.history.back();</script>";
            exit;
        }

        if (!isset($_SESSION['DNI_Usuario'])) {
            echo "<script>alert('Error: Debes iniciar sesión para ofertar.'); window.history.back();</script>";
            exit;
        }

        $ID_Subasta = $_GET['ID_Subasta'];
        $DNI_Usuario = $_SESSION['DNI_Usuario'];

        # Obtener datos de la obra
        $sqlObra = "SELECT o.ID_Obra, o.titulo, CONCAT(a.nombre, ' ', a.apellido) AS artista, s.valor_inicial, o.imagen, s.fecha_inicio, s.fecha_fin 
                    FROM obra o 
                    JOIN subasta s ON o.ID_Obra = s.ID_Obra 
                    JOIN artista a ON o.ID_Artista = a.ID_Artista 
                    WHERE s.ID_Subasta = ?";
        $stmtObra = $mysqli->prepare($sqlObra);
        $stmtObra->bind_param("i", $ID_Subasta);
        $stmtObra->execute();
        $resultObra = $stmtObra->get_result();
        $obra = $resultObra->fetch_assoc();
        $stmtObra->close();

        # Obtener la oferta más alta actual para empezar la transacción
        $mysqli->begin_transaction();
        try {
            $sqlOfertaMax = "SELECT MAX(propuesta) AS oferta_maxima FROM oferta WHERE ID_Subasta = ? FOR UPDATE";
            $stmtOfertaMax = $mysqli->prepare($sqlOfertaMax);
            $stmtOfertaMax->bind_param("i", $ID_Subasta);
            $stmtOfertaMax->execute();
            $resultOfertaMax = $stmtOfertaMax->get_result();
            $ofertaMaxima = $resultOfertaMax->fetch_assoc();
            $stmtOfertaMax->close();

            $ofertaMax = $ofertaMaxima['oferta_maxima'] ?? $obra['valor_inicial'];
            
            if ($_SERVER["REQUEST_METHOD"] === "POST") {
                $propuesta = isset($_POST['propuesta']) ? floatval($_POST['propuesta']) : null;
                
                if ($propuesta !== null && $propuesta >= $ofertaMax) {
                    $sql = "INSERT INTO oferta (ID_Subasta, DNI_Usuario, propuesta) VALUES (?, ?, ?)";
                    $stmt = $mysqli->prepare($sql);
                    $stmt->bind_param("isd", $ID_Subasta, $DNI_Usuario, $propuesta);
                    
                    if ($stmt->execute()) {
                        $mysqli->commit(); #termina la transacción
                        echo "<script>alert('Oferta realizada con éxito.'); window.location.href = 'oferta_alta.php?ID_Subasta=$ID_Subasta';</script>";
                    } else {
                        throw new Exception("Error al registrar la oferta.");
                    }
                    $stmt->close();
                } else {
                    echo "<script>alert('El monto de la oferta debe ser mayor o igual a la oferta más alta.');</script>";
                }
            }
        } catch (Exception $e) {
            $mysqli->rollback(); #en caso de error rollback para que cumpla ACID
            echo "<script>alert('Error: " . $e->getMessage() . "');</script>";
        }
    ?>

    <div id="realizar">
        <div class="imagen-obra">
            <img src="ver_imagen.php?ID_Obra=<?php echo $obra['ID_Obra']; ?>" alt="Imagen de la obra" width="350" height="350">
        </div>

        <div class="info-obra">
            <h2><?php echo htmlspecialchars($obra['titulo']); ?></h2>
            <p><strong>Artista:</strong> <?php echo htmlspecialchars($obra['artista']); ?></p>
            <p><strong>Valor Inicial:</strong> $<?php echo number_format($obra['valor_inicial'], 2); ?></p>
            <p><strong>Fecha de Inicio:</strong> <?php echo htmlspecialchars($obra['fecha_inicio']); ?></p>
            <p><strong>Fecha de Fin:</strong> <?php echo htmlspecialchars($obra['fecha_fin']); ?></p>
            <p><strong>Oferta más alta actual:</strong> $<?php echo number_format($ofertaMax, 2); ?></p>

            <form action="" method="POST">
                <label for="propuesta">Monto de la oferta:</label>
                <input type="number" name="propuesta" required style="width: 90%;padding: 10px; margin: 5px;border: 1px solid #7d2948; border-radius: 5px; font-size: 0.9rem;">
                <button type="submit">Ofertar</button>
            </form>
        </div>
    </div>
</body>
</html>
