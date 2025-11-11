<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Modificar artistas</title>
    <link rel="icon" href="../Imagenes/2-removebg-preview.png" type="image/png">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php
        include 'conexion_galeria.php';
        session_start();
        include('../menu.php');

        #verificar si el ID del artista esta definido cuando llega
        if(isset($_GET['ID_Artista'])) {
            $ID_Artista = $_GET['ID_Artista'];

            $sql = "SELECT * FROM artista where ID_Artista='".$ID_Artista."'";
            $resultado = mysqli_query($mysqli,$sql);
    
        if(mysqli_num_rows($resultado) > 0) {
        $fila = mysqli_fetch_assoc($resultado);
    ?>

    <div id="tabla_modificar">
        <fieldset>
            <legend>Modificar artista</legend>
            <form action="admi_modificar_artista.php" method="get">
                <label>Nombre</label>
                <input type="text" name="nombre" value="<?php echo $fila['nombre']?>" placeholder="Nombre"><br>

                <label>Apellido</label>
                <input type="text" name="apellido" value="<?php echo $fila['apellido']?>" placeholder="Apellido"><br>

                <label>Nacionalidad</label>
                <input type="text" name="nacionalidad" value="<?php echo $fila['nacionalidad']?>" placeholder="Nacionalidad"><br>

                <label>Nacimiento</label>
                <input type="date" name="nacimiento" value="<?php echo $fila['nacimiento']?>" placeholder="Nacimiento"><br>

                <label>Fallecimiento</label>
                <input type="date" name="fallecimiento" value="<?php echo $fila['fallecimiento']?>" placeholder="Fallecimiento"><br>

                <input type="hidden" name="ID_Artista" value="<?php echo $fila['ID_Artista'] ?>">
                <input type="submit" value="Modificar">
            </form>
        </fieldset>
    <?php
        }
    }

        if(isset($_GET['nombre'], $_GET['apellido'], $_GET['nacionalidad'], $_GET['nacimiento'], $_GET['fallecimiento'])){
            #adentro para verificar que definidos
            $nombre = $_GET['nombre'];
            $apellido = $_GET['apellido'];
            $nacionalidad = $_GET['nacionalidad'];
            $nacimiento = $_GET['nacimiento'];
            $fallecimiento = $_GET['fallecimiento'];
            $ID_Artista = $_GET['ID_Artista'];

            #se modifica solo si los 3 principales no son == a nulo
            if($nombre != NULL && $apellido != NULL && $nacionalidad != NULL){
                $sql2 = "UPDATE artista SET nombre='".$nombre."', apellido='".$apellido."', nacionalidad='".$nacionalidad."', nacimiento='".$nacimiento."', fallecimiento='".$fallecimiento."' WHERE ID_Artista='".$ID_Artista."'";
                mysqli_query($mysqli,$sql2);
                echo '<script>alert("Se modificó correctamente");</script>'; 
                echo '<script>window.location.href = "admi_artistas.php";</script>'; 
                exit;
            }
        }

    ?>
    </div>
</body>
</html>

