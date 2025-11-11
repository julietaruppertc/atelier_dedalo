<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="icon" href="../Imagenes/2-removebg-preview.png" type="image/png">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php 
        session_start();
        include 'conexion_galeria.php'; 
        include('../menu.php');
    ?>

    <div id="iniciar_sesion" class="form-container">
        <fieldset class="form-fieldset">
            <legend>Iniciar sesión</legend>
            <form action="login.php" method="post" class="form">
                <label>Ingrese su usuario</label>
                <input type="text" name="DNI_Usuario" placeholder="DNI" required class="input-field"><br>

                <label>Ingrese su contraseña</label>
                <input type="password" name="password" placeholder="Contraseña" required class="input-field"><br>

                <input type="submit" value="Iniciar sesión" class="submit-btn">
            </form>
        </fieldset>
    </div>

    <details id="registrarse">
        <summary>Registrarse</summary>
        <fieldset class="form-fieldset">
            <form action="login.php" method="post">
                <label>Nombre</label>
                <input type="text" name="nombre" class="input-field" placeholder="Nombre" required><br>

                <label>Apellido</label>
                <input type="text" name="apellido" class="input-field" placeholder="Apellido" required ><br>

                <label>DNI</label>
                <input type="number" name="DNI" class="input-field" placeholder="Sin puntos" required ><br>

                <label>Correo electrónico</label>
                <input type="email" name="mail" class="input-field" placeholder="Correo electrónico" required ><br>

                <label>Teléfono</label>
                <input type="number" name="telefono" class="input-field" placeholder="Sin espacios" required ><br>

                <label>Localidad</label>
                <input type="text" name="localidad" class="input-field" placeholder="Localidad" required ><br>

                <label>Provincia</label>
                <input type="text" name="provincia" class="input-field" placeholder="Provincia" required ><br>

                <label>Código Postal</label>
                <input type="number" name="CP" class="input-field" placeholder="CP" required ><br>

                <label>Contraseña</label>
                <input type="password" name="password" class="input-field" placeholder="Contraseña" required ><br>

                <div class="boton-contenedor">
                <input type="submit" class="submit-btn" value="Registrarse">
                </div>
            </form>
        </fieldset>
    </details>

    <?php
        if(isset($_SESSION['DNI_Usuario'])) { 
            echo 'Bienvenido ' . $_SESSION['DNI_Usuario'];
            header('Location: ../index.php');
            exit();
        } else { 
            if(isset($_POST['DNI_Usuario']) && isset($_POST['password'])) {
                $DNI_Usuario = $_POST['DNI_Usuario'];
                $password = $_POST['password'];

                $sql = "SELECT * FROM usuario WHERE DNI_Usuario = '$DNI_Usuario' AND password = MD5('$password') AND estado = 'A'";
                $result = mysqli_query($mysqli, $sql);
                $encontrado = mysqli_fetch_assoc($result);

                if($encontrado) { 
                    $_SESSION['DNI_Usuario'] = $DNI_Usuario;
                    $_SESSION['ROL'] = $encontrado['ID_Tipo'];
                    
                    header('Location: ../index.php');
                    exit();
                } else {
                    echo "<script>alert('Usuario o contraseña incorrectos.');</script>";
                }
            }
        }

        if(isset($_POST['nombre'], $_POST['apellido'], $_POST['DNI'], $_POST['mail'], $_POST['telefono'], $_POST['localidad'], $_POST['provincia'], $_POST['CP'], $_POST['password'])) {
            if (empty($_POST['nombre']) || empty($_POST['apellido']) || empty($_POST['DNI']) || empty($_POST['mail']) || empty($_POST['telefono']) || empty($_POST['localidad']) || empty($_POST['provincia']) || empty($_POST['CP']) || empty($_POST['password'])) {
                echo "<script>alert('Todos los campos son obligatorios.');</script>";
            } else {
                $nombre = $_POST['nombre'];
                $apellido = $_POST['apellido'];
                $DNI = $_POST['DNI'];
                $mail = $_POST['mail'];
                $telefono = $_POST['telefono'];
                $localidad = $_POST['localidad'];
                $provincia = $_POST['provincia'];
                $CP = $_POST['CP'];
                $password = $_POST['password'];
                $tipo_usuario = 1; 
                $estado = 'A'; 

                // Validar si el DNI ya existe
                $sqlVerificar = "SELECT DNI_Usuario FROM usuario WHERE DNI_Usuario = '$DNI'";
                $resultadoVerificar = mysqli_query($mysqli, $sqlVerificar);
                
                if(mysqli_num_rows($resultadoVerificar) > 0) {
                    echo "<script>alert('El DNI ya está registrado.');</script>";
                } else {
                    $sqlInsertarUsuario = "INSERT INTO usuario(DNI_Usuario, nombre, apellido, mail, telefono, password, ID_Tipo, estado, localidad, provincia, CP) 
                    VALUES ('$DNI', '$nombre', '$apellido', '$mail', '$telefono', MD5('$password'), '$tipo_usuario', '$estado', '$localidad', '$provincia', '$CP')";
                    
                    if(mysqli_query($mysqli, $sqlInsertarUsuario)) {
                        $_SESSION['DNI_Usuario'] = $DNI;  
                        $_SESSION['ROL'] = $tipo_usuario;
                        
                        header('Location: ../index.php');
                        exit();
                    } else {
                        echo "<script>alert('Error al registrar el usuario.');</script>";
                    }
                }
            }
        }
    ?>

</body>
</html>


