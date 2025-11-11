<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Modificar obras</title>
    <link rel="icon" href="../Imagenes/2-removebg-preview.png" type="image/png">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php
        include 'conexion_galeria.php';
        session_start();
        include('../menu.php');

        # Verificar si el ID de la obra está definido cuando llega
        $ID_Obra = $_GET['ID_Obra'];
        $sql = "SELECT * FROM obra WHERE ID_Obra='$ID_Obra'";
        $resultado = mysqli_query($mysqli, $sql);

        if (mysqli_num_rows($resultado) > 0) {
            $fila = mysqli_fetch_assoc($resultado);
            $ID_Artista = $fila['ID_Artista'];

            // Obtener el nombre del artista
            $sqlArtista = "SELECT nombre, apellido FROM artista WHERE ID_Artista = '$ID_Artista'";
            $resultadoArtista = mysqli_query($mysqli, $sqlArtista);
            if ($filaArtista = mysqli_fetch_assoc($resultadoArtista)) {
                $nombreArtista = $filaArtista['nombre'] . ' ' . $filaArtista['apellido'];
            } else {
                $nombreArtista = ''; // Si no se encuentra el artista
            }
    ?>

    <div id="modificar">
        <fieldset>
            <legend>Modificar obra</legend>
            <form action="admi_modificar_obras.php" method="post" enctype="multipart/form-data">

                <label>Título</label>
                <input type="text" name="titulo" value="<?php echo htmlspecialchars($fila['titulo']); ?>" placeholder="Título"><br>

                <label>Artista</label>
                <input type="text" id="buscar-artista" placeholder="Escribe para buscar artistas" value="<?php echo htmlspecialchars($nombreArtista); ?>">
                <input type="hidden" name="artista" id="artista-id" value="<?php echo $ID_Artista; ?>"><br>
                <div id="sugerencias"></div>

                <label>Fecha</label>
                <input type="number" name="fecha" value="<?php echo htmlspecialchars($fila['fecha']); ?>" placeholder="Año"><br>

                <label>Cargar imagen de la obra</label>
                <input type="file" name="imagen" accept="image/*"><br>

                <input type="hidden" name="ID_Obra" value="<?php echo $ID_Obra; ?>">
                <input type="submit" value="Modificar">
            </form>
        </fieldset>
    </div>

    <?php
        }

        # Procesar la actualización
        if (isset($_POST['titulo'], $_POST['artista'], $_POST['fecha'])) {
            $ID_Obra = $_POST['ID_Obra'];
            $titulo = $_POST['titulo'];
            $artista = $_POST['artista'];
            $fecha = $_POST['fecha'];

            # Validar la carga de una imagen
            if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
                $imagen_nombre = $_FILES['imagen']['name'];
                $imagen_temporal = $_FILES['imagen']['tmp_name'];
                $ruta_directorio = "../Imagenes/";
                $ruta_destino = $ruta_directorio . basename($imagen_nombre);

                # Validar si es una imagen
                $imagen_tipo = mime_content_type($imagen_temporal);
                if (strpos($imagen_tipo, "image") === false) {
                    echo "El archivo no es una imagen válida.";
                    exit;
                }

                # Subir imagen
                if (move_uploaded_file($imagen_temporal, $ruta_destino)) {
                    $sql2 = "UPDATE obra SET titulo = ?, ID_Artista = ?, fecha = ?, imagen = ? WHERE ID_Obra = ?";
                    if ($stmt = mysqli_prepare($mysqli, $sql2)) {
                        mysqli_stmt_bind_param($stmt, "sisis", $titulo, $artista, $fecha, $imagen_nombre, $ID_Obra);
                        mysqli_stmt_execute($stmt);
                        echo '<script>alert("Se modificó correctamente");</script>';
                        echo '<script>window.location.href = "admi_obras.php";</script>';
                        exit;
                    }
                } else {
                    echo '<script>alert("Hubo un error al cargar la imagen");</script>';
                    echo '<script>window.location.href = "admi_obras.php";</script>';
                }
            } else { # Si no se sube una nueva imagen
                $sql2 = "UPDATE obra SET titulo = ?, ID_Artista = ?, fecha = ? WHERE ID_Obra = ?";
                if ($stmt = mysqli_prepare($mysqli, $sql2)) {
                    mysqli_stmt_bind_param($stmt, "sisi", $titulo, $artista, $fecha, $ID_Obra);
                    mysqli_stmt_execute($stmt);
                    echo '<script>alert("Se modificó correctamente");</script>';
                    echo '<script>window.location.href = "admi_obras.php";</script>';
                    exit;
                }
            }
        }
    ?>

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
