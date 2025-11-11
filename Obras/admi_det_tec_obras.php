<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Agregar detalles</title>
    <link rel="icon" href="../Imagenes/2-removebg-preview.png" type="image/png">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php
        include 'conexion_galeria.php';
        session_start();
        include('../menu.php'); 

        # Verificar si el ID de la obra está definido
        if (isset($_GET['ID_Obra'])) {
            $ID_Obra = $_GET['ID_Obra'];

            # Consultar si ya existen detalles de la obra
            $sql = "SELECT * FROM obra WHERE ID_Obra = '$ID_Obra'";
            $resultado = mysqli_query($mysqli, $sql);

            if (mysqli_num_rows($resultado) > 0) {
                $fila = mysqli_fetch_assoc($resultado);
                $ID_Detalle = $fila['ID_Detalle'];

                # Si ya existe un detalle, obtener esos datos
                if ($ID_Detalle) {
                    $sqlDetalles = "SELECT * FROM detalle_obra WHERE ID_Detalle = '$ID_Detalle'";
                    $resultadoDetalles = mysqli_query($mysqli, $sqlDetalles);
                    $detalle = mysqli_fetch_assoc($resultadoDetalles);
                }
    ?>

    <div id="detalles">
    <fieldset>
        <legend>Cargar detalles y técnica de la obra</legend>
        <form action="admi_det_tec_obras.php" method="post">
            <label>Alto</label>
            <input type="number" step="0.01" name="alto" placeholder="En cm" value="<?php echo isset($detalle['alto']) ? $detalle['alto'] : ''; ?>"><br>

            <label>Ancho</label>
            <input type="number" step="0.01" name="ancho" placeholder="En cm" value="<?php echo isset($detalle['ancho']) ? $detalle['ancho'] : ''; ?>"><br>

            <label>Profundidad</label>
            <input type="number" step="0.01" name="profundidad" placeholder="En cm" value="<?php echo isset($detalle['profundidad']) ? $detalle['profundidad'] : ''; ?>"><br>

            <label>Material</label>
            <select name="ID_Material">
                <?php
                    $sqlMaterial = "SELECT * FROM material";
                    $resultadoMaterial = mysqli_query($mysqli, $sqlMaterial);
                    while ($material = mysqli_fetch_assoc($resultadoMaterial)) {
                        $selected = isset($detalle['ID_Material']) && $detalle['ID_Material'] == $material['ID_Material'] ? 'selected' : '';
                        echo "<option value='{$material['ID_Material']}' $selected>{$material['descripcion']}</option>";
                    }
                ?>
            </select><br>

            <label>Técnica</label>
            <select name="ID_Tecnica">
                <?php
                    $sqlTecnica = "SELECT * FROM tecnica";
                    $resultadoTecnica = mysqli_query($mysqli, $sqlTecnica);
                    while ($tecnica = mysqli_fetch_assoc($resultadoTecnica)) {
                        $selected = isset($detalle['ID_Tecnica']) && $detalle['ID_Tecnica'] == $tecnica['ID_Tecnica'] ? 'selected' : '';
                        echo "<option value='{$tecnica['ID_Tecnica']}' $selected>{$tecnica['descripcion']}</option>";
                    }
                ?>
            </select><br>

            <label>Detalles</label>
            <textarea name="detalles" placeholder="Detalles"><?php echo isset($detalle['detalles']) ? $detalle['detalles'] : ''; ?></textarea><br>

            <input type="hidden" name="ID_Obra" value="<?php echo $ID_Obra; ?>">
            <input type="submit" value="Agregar">
        </form>
    </fieldset>
    </div>
    <?php
            }
        }

        # Procesar la inserción de detalles
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $ID_Obra = $_POST['ID_Obra'];
            $alto = $_POST['alto'];
            $ancho = $_POST['ancho'];
            $profundidad = $_POST['profundidad'];
            $ID_Material = $_POST['ID_Material'];
            $ID_Tecnica = $_POST['ID_Tecnica'];
            $detalles = $_POST['detalles'];

            # Insertar en detalle_obra
            $sqlInsertarDetalle = "INSERT INTO detalle_obra (ID_Material, ID_Tecnica, detalles, alto, ancho, profundidad)
                                   VALUES ('$ID_Material', '$ID_Tecnica', '$detalles', '$alto', '$ancho', '$profundidad')";
            if (mysqli_query($mysqli, $sqlInsertarDetalle)) {
                $ID_Detalle = mysqli_insert_id($mysqli);

                # Actualizar obra con el ID del detalle
                $sqlActualizarObra = "UPDATE obra SET ID_Detalle = '$ID_Detalle' WHERE ID_Obra = '$ID_Obra'";
                mysqli_query($mysqli, $sqlActualizarObra);

                echo '<script>alert("Se guardó correctamente");</script>';
                echo '<script>window.location.href = "admi_obras.php";</script>';
            } else {
                echo '<script>alert("Hubo un error al cargar los detalles de la obra.");</script>';
                echo '<script>window.location.href = "admi_obras.php";</script>';
            }
        }
    ?>
</body>
</html>
