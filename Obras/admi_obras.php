<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cargar obras</title>
    <link rel="icon" href="../Imagenes/2-removebg-preview.png" type="image/png">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php 
    include 'conexion_galeria.php';
    session_start();
    include('../menu.php'); ?>
    <div id="carga_obras">
        <fieldset>
            <legend>Cargar obra nueva</legend>
            <form action="admi_obras.php" method="post" enctype="multipart/form-data">
                <label>Título</label>
                <input type="text" name="titulo" placeholder="Título" required><br>

                <label>Artista</label>
                <input type="text" id="buscar-artista" placeholder="Escribe para buscar artistas">
                <input type="hidden" name="artista" id="artista-id"><br>
                <div id="sugerencias"></div>

                <label>Fecha</label>
                <input type="number" name="fecha" placeholder="Año" required><br>

                <label>Cargar imagen de la obra</label>
                <input type="file" name="imagen" accept="image/*"><br>

                <input type="submit" value="Cargar">
            </form>
        </fieldset>
    </div>
    <div id="tabla_obras">
        <?php

        // Validación y carga de datos
        if (isset($_POST['titulo'], $_POST['artista'], $_POST['fecha'], $_FILES['imagen'])) {
            $titulo = $_POST['titulo'];
            $artista = $_POST['artista'];
            $fecha = $_POST['fecha'];
            $imagen_nombre = $_FILES['imagen']['name'];
            $imagen_temporal = $_FILES['imagen']['tmp_name'];
            $ruta_directorio = "../Imagenes/";
            $ruta_destino = $ruta_directorio . $imagen_nombre;

            if ($titulo && $artista && $fecha && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
                if (move_uploaded_file($imagen_temporal, $ruta_destino)) {
                    $sqlInsertarObra = "INSERT INTO obra(titulo, ID_Artista, fecha, imagen) VALUES ('$titulo', '$artista', '$fecha', '$imagen_nombre')";
                    mysqli_query($mysqli, $sqlInsertarObra);
                    echo '<script>alert("Obra guardada correctamente.");</script>';
                    echo '<script>window.location.href = "admi_obras.php";</script>';
                    exit;
                }
            } else {
                echo '<script>
                        alert("Hubo un error al cargar la obra.");
                        window.location.href = "admi_obras.php";
                    </script>';
            }
        }

        // Mostrar lista de obras
        $sqlObras = "SELECT obra.ID_Obra, obra.titulo, CONCAT(artista.nombre, ' ', artista.apellido) AS nombre_completo, obra.fecha
                     FROM obra
                     INNER JOIN artista ON obra.ID_Artista = artista.ID_Artista";
        $resultadoObras = mysqli_query($mysqli, $sqlObras);
        ?>

        <table>
            <thead>
                <tr>
                    <th>ID de la obra</th>
                    <th>Título</th>
                    <th>Artista</th>
                    <th>Fecha</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php while ($filaObras = mysqli_fetch_assoc($resultadoObras)) { ?>
                    <tr>
                        <td><?php echo $filaObras['ID_Obra']; ?></td>
                        <td><?php echo $filaObras['titulo']; ?></td>
                        <td><?php echo $filaObras['nombre_completo']; ?></td>
                        <td><?php echo $filaObras['fecha']; ?></td>
                        <td>
                            <a href="admi_modificar_obras.php?ID_Obra=<?php echo $filaObras['ID_Obra']; ?>">Modificar</a>
                            <a href="admi_det_tec_obras.php?ID_Obra=<?php echo $filaObras['ID_Obra']; ?>">Agregar detalles</a>
                            <a href="admi_baja_obras.php?ID_Obra=<?php echo $filaObras['ID_Obra']; ?>">Eliminar</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <script>
        $(document).ready(function () {
            $("#buscar-artista").on("input", function () {
                var query = $(this).val();
                if (query.length > 2) {
                    $.ajax({
                        url: "buscar_artista.php",
                        type: "POST",
                        data: { busqueda: query },
                        success: function (data) {
                            $("#sugerencias").html(data).fadeIn();
                        }
                    });
                } else {
                    $("#sugerencias").fadeOut();
                }
            });

            $(document).on("click", ".sugerencia", function () {
                var nombreArtista = $(this).text();
                var idArtista = $(this).data("id");
                $("#buscar-artista").val(nombreArtista);
                $("#artista-id").val(idArtista);
                $("#sugerencias").fadeOut();
            });
        });
    </script>
</body>
</html>
