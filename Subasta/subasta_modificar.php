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
    include 'conexion_galeria.php';
    session_start();
    include('../menu.php');

    if (isset($_GET['ID_Obra'])) {
        $ID_Obra = $_GET['ID_Obra'];

        // Consulta para obtener los datos existentes
        $sql = "
            SELECT obra.titulo, obra.fecha, obra.imagen, subasta.valor_inicial, subasta.ID_Condicion, 
                   subasta.fecha_inicio, subasta.fecha_fin
            FROM obra
            LEFT JOIN subasta ON obra.ID_Obra = subasta.ID_Obra
            WHERE obra.ID_Obra = '$ID_Obra'";

        $resultado = mysqli_query($mysqli, $sql);

        if (mysqli_num_rows($resultado) > 0) {
            $fila = mysqli_fetch_assoc($resultado);
?>

    <div id="modificar">
    <fieldset>
        <legend>Modificar subasta</legend>
        <form action="subasta_modificar.php" method="post">
            <label>Título de la obra: </label>
            <input type="text" value="<?php echo $fila['titulo']; ?>" readonly><br>

            <label>Subasta inicial:</label>
            <input type="number" name="valor_inicial" value="<?php echo $fila['valor_inicial']; ?>" step="0.01" required><br>

            <label>Fecha de inicio:</label>
            <input type="date" name="fecha_inicio" value="<?php echo $fila['fecha_inicio']; ?>" required><br>

            <label>Fecha de fin:</label>
            <input type="date" name="fecha_fin" value="<?php echo $fila['fecha_fin']; ?>" required><br>

            <label>Condición:</label>
            <select name="ID_Condicion" required>
                <?php
                    $sqlCondiciones = "SELECT * FROM condicion";
                    $resultadoCondiciones = mysqli_query($mysqli, $sqlCondiciones);
                    
                    while ($filaCondicion = mysqli_fetch_assoc($resultadoCondiciones)) {
                        // Verifica si el valor de ID_Condicion es el mismo y lo marca como seleccionado
                        $selected = ($filaCondicion['ID_Condicion'] == $fila['ID_Condicion']) ? 'selected' : '';
                        echo "<option value='{$filaCondicion['ID_Condicion']}' $selected>{$filaCondicion['descripcion']}</option>";
                    }
                ?>
            </select><br>

            <input type="hidden" name="ID_Obra" value="<?php echo $ID_Obra; ?>">
            <input type="submit" value="Modificar">
        </form>
    </fieldset>
    </div>
<?php
        } else {
            echo "No se encontró la obra.";
        }
    } else {
        echo "ID de la obra no proporcionado.";
    }

    if (isset($_POST['valor_inicial']) && isset($_POST['ID_Condicion']) && isset($_POST['fecha_inicio']) && isset($_POST['fecha_fin'])) {
        $ID_Obra = $_POST['ID_Obra'];
        $valor_inicial = $_POST['valor_inicial'];
        $ID_Condicion = $_POST['ID_Condicion'];
        $fecha_inicio = $_POST['fecha_inicio'];
        $fecha_fin = $_POST['fecha_fin'];

        // Validación de fechas
        $fecha_hoy = date('Y-m-d');  // Obtener la fecha actual

        // Validar que la fecha de inicio no sea anterior a la fecha actual
        if ($fecha_inicio < $fecha_hoy) {
            echo "<script>alert('La fecha de inicio no puede ser anterior a hoy.');</script>";
            echo '<script>window.location.href = "subasta_modificar.php?ID_Obra=' . $ID_Obra . '";</script>';
            exit;
        }

        // Validar que la fecha de fin no sea anterior a la fecha actual ni antes de la fecha de inicio
        if ($fecha_fin < $fecha_hoy || $fecha_fin < $fecha_inicio) {
            echo "<script>alert('La fecha de fin no puede ser anterior a hoy ni a la fecha de inicio.');</script>";
            echo '<script>window.location.href = "subasta_modificar.php?ID_Obra=' . $ID_Obra . '";</script>';
            exit;
        }

        // Validar que la fecha de inicio no sea igual a la fecha de fin
        if ($fecha_inicio == $fecha_fin) {
            echo "<script>alert('La fecha de inicio no puede ser igual a la fecha de fin.');</script>";
            echo '<script>window.location.href = "subasta_modificar.php?ID_Obra=' . $ID_Obra . '";</script>';
            exit;
        }

        // Verificación adicional si la condición es 'En curso' (ID_Condicion = 2)
        if ($ID_Condicion == 2) {  
            // Verificamos si los campos de detalle_obra están completos
            $sqlDetalle = "
                SELECT detalles, alto, ancho, profundidad
                FROM detalle_obra
                WHERE ID_Detalle = (SELECT ID_Detalle FROM obra WHERE ID_Obra = '$ID_Obra')
            ";
            $resultadoDetalle = mysqli_query($mysqli, $sqlDetalle);
            $detalle = mysqli_fetch_assoc($resultadoDetalle);

            if (empty($detalle['detalles']) || empty($detalle['alto']) || empty($detalle['ancho']) || empty($detalle['profundidad'])) {
                echo "<script>alert('Debe completar todos los campos de detalle de la obra antes de cambiar su condición a En curso.');</script>";
                echo '<script>window.location.href = "../Obras/admi_det_tec_obras.php?ID_Obra=' . $ID_Obra . '";</script>';
                exit;
            }

            // Validar que la fecha de inicio y fin estén completas antes de cambiar la condición
            if (empty($fecha_inicio) || empty($fecha_fin)) {
                echo "<script>alert('Debe completar las fechas de inicio y fin antes de cambiar la condición a En curso.');</script>";
                echo '<script>window.location.href = "subasta_modificar.php?ID_Obra=' . $ID_Obra . '";</script>';
                exit;
            }
        }

        // Actualización de la subasta en la base de datos
        $sql = "UPDATE subasta 
        SET valor_inicial='$valor_inicial', 
            ID_Condicion='$ID_Condicion', 
            fecha_inicio='$fecha_inicio', 
            fecha_fin='$fecha_fin' 
        WHERE ID_Obra='$ID_Obra'";

        // Ejecutar el UPDATE
        if (mysqli_query($mysqli, $sql)) {
            // Verificar si las fechas son correctas y si es necesario actualizar la condición de la subasta
            if ($fecha_fin <= date('Y-m-d') && $ID_Condicion != 3) {
                $sqlFinalizada = "UPDATE subasta 
                                  SET ID_Condicion = 3 
                                  WHERE ID_Obra = '$ID_Obra'";
                mysqli_query($mysqli, $sqlFinalizada);
            }

            echo '<script>alert("Subasta actualizada correctamente.");</script>';
            echo '<script>window.location.href = "../index.php?ID_Obra=' . $ID_Obra . '";</script>';
            exit;
        } else {
            echo "Error al modificar la subasta: " . mysqli_error($mysqli);
        }
    }
?>

</body>
</html>
