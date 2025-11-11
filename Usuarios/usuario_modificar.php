<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Modificar datos</title>
    <link rel="icon" href="../Imagenes/2-removebg-preview.png" type="image/png">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php
        include 'conexion_galeria.php';
        session_start();
        include('../menu.php');

        if(isset($_SESSION['DNI_Usuario'])) {
            $DNI_Usuario = $_SESSION['DNI_Usuario'];
            $sql = "SELECT * FROM usuario WHERE DNI_Usuario = '$DNI_Usuario'";
            $resultado = mysqli_query($mysqli, $sql);

            if(mysqli_num_rows($resultado) > 0) {
                $fila = mysqli_fetch_assoc($resultado);
    ?>

    <div id="modificar">
    <fieldset class="form-fieldset">
        <legend>Modificar usuario</legend>
        <form action="usuario_modificar.php" method="post">
            <label>Nombre</label>
            <input type="text" name="nombre" class="input-field" value="<?php echo $fila['nombre']?>" placeholder="Nombre"><br>

            <label>Apellido</label>
            <input type="text" name="apellido" class="input-field" value="<?php echo $fila['apellido']?>" placeholder="Apellido"><br>

            <label>Correo electrónico</label>
            <input type="email" name="mail" class="input-field"  value="<?php echo $fila['mail']?>" placeholder="Correo electrónico"><br>

            <label>Teléfono</label>
            <input type="number" name="telefono" class="input-field"  value="<?php echo $fila['telefono']?>" placeholder="Sin espacios"><br>

            <label>Localidad</label>
            <input type="text" name="localidad" class="input-field" value="<?php echo $fila['localidad']?>" placeholder="Localidad"><br>

            <label>Provincia</label>
            <input type="text" name="provincia" class="input-field" value="<?php echo $fila['provincia']?>" placeholder="Provincia"><br>

            <label>Código Postal</label>
            <input type="number" name="CP" class="input-field" value="<?php echo $fila['CP']?>" placeholder="Código Postal"><br>

            <label>Contraseña</label>
            <input type="password" name="password" class="input-field" placeholder="Dejar en blanco para no cambiar"><br>

            <input type="hidden" name="DNI_Usuario" value="<?php echo $fila['DNI_Usuario'] ?>">
            <input type="submit" value="Modificar" class="submit-btn">
        </form>
    </fieldset>
    </div>
<?php
    }
}

if (isset($_POST['DNI_Usuario'], $_POST['nombre'], $_POST['apellido'], $_POST['mail'], $_POST['telefono'], $_POST['localidad'], $_POST['provincia'], $_POST['CP'])) {
    $DNI_Usuario = $_POST['DNI_Usuario'];
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $mail = $_POST['mail'];
    $telefono = $_POST['telefono'];
    $localidad = $_POST['localidad'];
    $provincia = $_POST['provincia'];
    $CP = $_POST['CP'];
    $password = $_POST['password'];

    # Construir la consulta de actualización
    $sql2 = "UPDATE usuario SET nombre='$nombre', apellido='$apellido', mail='$mail', telefono='$telefono', localidad='$localidad', provincia='$provincia', CP='$CP'";

    # Verificar si se ingresó una nueva contraseña
    if (!empty($password)) {
        $password_encriptada = md5($password);
        $sql2 .= ", password='$password_encriptada'";
    }

    $sql2 .= " WHERE DNI_Usuario='$DNI_Usuario'";

    try {
        # Intentar ejecutar la actualización
        mysqli_query($mysqli, $sql2);
        header('Location: usuario_datos.php');
        exit();
    } catch (mysqli_sql_exception $e) {
        echo 'Error en la actualización: ' . $e->getMessage();
    }
}
?>

</body>
</html>

