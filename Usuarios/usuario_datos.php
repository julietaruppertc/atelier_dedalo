<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mis datos</title>
    <link rel="icon" href="../Imagenes/2-removebg-preview.png" type="image/png">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php
        session_start();
        include 'conexion_galeria.php';
        include('../menu.php');
    ?>
    <div id="datos_usuario">
    <?php
    $DNI_Usuario = $_SESSION['DNI_Usuario'];

    // Consulta para obtener los datos del usuario
    $sqlUsuario = "SELECT DNI_Usuario, nombre, apellido, mail, telefono, localidad, provincia, CP, ID_Tipo FROM usuario WHERE DNI_Usuario = '$DNI_Usuario'";
    $resultUsuario = mysqli_query($mysqli, $sqlUsuario);

    if ($resultUsuario) {
        $usuario = mysqli_fetch_assoc($resultUsuario);
        $ID_Tipo = $usuario['ID_Tipo'];

        // Mostrar los datos del usuario
        echo '<h2>Mis Datos</h2>';
        echo "<p>DNI: {$usuario['DNI_Usuario']}</p>";
        echo "<p>Nombre: {$usuario['nombre']}</p>";
        echo "<p>Apellido: {$usuario['apellido']}</p>";
        echo "<p>Mail: {$usuario['mail']}</p>";
        echo "<p>Teléfono: {$usuario['telefono']}</p>";
        echo "<p>Localidad: {$usuario['localidad']}</p>";
        echo "<p>Provincia: {$usuario['provincia']}</p>";
        echo "<p>CP: {$usuario['CP']}</p>";
        if(isset($_SESSION['DNI_Usuario']) && $_SESSION['ROL'] == 1){
            ?>
            <a href="usuario_modificar.php">Modificar usuario</a><br>
            <a href="usuario_baja.php">Eliminar usuario</a><br>
            <?php
        }
        if ($ID_Tipo == 1) {
            // El usuario no es administrador, mostramos solo sus propias ofertas
            echo '<h2>Mis ofertas realizadas</h2>';
            
            $sqlMisOfertas = "
                SELECT * FROM vista_oferta_usuarios 
                WHERE DNI_Usuario = '$DNI_Usuario'
                ORDER BY fecha_fin DESC";

            $resultMisOfertas = mysqli_query($mysqli, $sqlMisOfertas);

            if ($resultMisOfertas && mysqli_num_rows($resultMisOfertas) > 0) {
                echo '<table border="1">
                    <thead>
                        <tr>
                            <th>Obra</th>
                            <th>Artista</th>
                            <th>Oferta</th>
                            <th>Oferta más alta</th>
                            <th>Fecha de Inicio</th>
                            <th>Fecha de Fin</th>
                            <th>Condición</th>
                        </tr>
                    </thead>
                    <tbody>';

            while ($subasta = mysqli_fetch_assoc($resultMisOfertas)) {
                echo "<tr>
                        <td><a href='../Subasta/subasta_mostrar_obra.php?ID_Obra={$subasta['ID_Obra']}'>{$subasta['nombre_obra']}</a></td>
                        <td>{$subasta['nombre_artista']}</td>
                        <td>$" . number_format($subasta['oferta'], 1) . "</td>
                        <td>$" . number_format($subasta['oferta_mas_alta'], 1) . "</td>
                        <td>{$subasta['fecha_inicio']}</td>
                        <td>{$subasta['fecha_fin']}</td>
                        <td>{$subasta['condicion']}</td>
                    </tr>";
            }

            echo "</tbody></table>";
            } else {
                echo "<p>No has realizado ofertas.</p>";
            }
        }
        
    } else {
        echo "Error al obtener los datos del usuario: " . mysqli_error($mysqli);
    }
    ?>
    </div>
</body>
</html>
