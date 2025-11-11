<?php
include_once 'config.php'; // Incluye la configuración
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atelier Dédalo</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>style.css">
</head>
<body>
    <div id="menu">
        <nav>
            <a href="<?php echo BASE_URL; ?>index.php" class="logo">
                <img src="<?php echo BASE_URL; ?>Imagenes/logo_chico.png" alt="Logo Atelier Dédalo">
            </a>
            <ul>
                <?php if (!isset($_SESSION['ROL']) || ($_SESSION['ROL'] != 0 && $_SESSION['ROL'] != 1)) { ?>
                    <li><a href="<?php echo BASE_URL; ?>Usuarios/login.php">Ingresar</a></li>
                <?php } ?>
                <?php if (isset($_SESSION['DNI_Usuario']) && $_SESSION['ROL'] == 0) { ?>
                    <li><a href="<?php echo BASE_URL; ?>Artistas/admi_artistas.php">Agregar Artista</a></li>
                    <li><a href="<?php echo BASE_URL; ?>Obras/admi_obras.php">Agregar Obras</a></li>
                    <li><a href="<?php echo BASE_URL; ?>Oferta/ver_ofertas.php">Ver Ofertas</a></li>
                <?php } ?>
                <?php if (isset($_SESSION['DNI_Usuario'])) { ?>
                    <li><a href="<?php echo BASE_URL; ?>Usuarios/usuario_datos.php">Mis Datos</a></li>
                    <li><a href="<?php echo BASE_URL; ?>index.php?cerrarsesion=1">Cerrar Sesión</a></li>
                <?php } ?>
            </ul>
        </nav>
    </div>
</body>
</html>
