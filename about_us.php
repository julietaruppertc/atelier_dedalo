<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About us</title>
    <link rel="icon" href="Imagenes/2-removebg-preview.png" type="image/png">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php session_start(); 
    include 'conexion_galeria.php';
    include 'menu.php';?>

    <div id="about_us">
        <div class="contenido">
            <img src="Imagenes/mision.jpg" alt="Misión" class="imagen-secundaria">
            <div id="nombre">
                <h2>Atelier Dédalo</h2>
                <p>
                    El nombre "Atelier Dédalo" refleja la fusión entre creatividad, innovación y maestría. "Atelier", que significa taller en francés, simboliza un espacio donde los artistas crean y experimentan. "Dédalo" hace referencia al legendario artesano de la mitología griega, conocido por su habilidad para construir obras complejas y transformadoras.
                    Con este nombre, buscamos rendir homenaje a la destreza artística y la creatividad de los artistas internacionales que participan en nuestras subastas, ofreciendo un espacio donde el arte y la excelencia se encuentran.
                </p>
            </div>
        </div>
        <div class="contenido reverse">
            <img src="Imagenes/museo-malba-2.jpg" alt="Museo Malba" class="imagen-secundaria">
            <div id="subasta">
                <h2>¿Cómo trabajamos?</h2>
                <p>
                    En Atelier Dédalo, nos especializamos en conseguir obras excepcionales de artistas internacionales, seleccionadas por su originalidad y calidad. Trabajamos directamente con artistas y galerías de todo el mundo para ofrecer piezas únicas que reflejan las tendencias más actuales y la diversidad del arte global.
                    Una vez adquiridas, estas obras se presentan en nuestras exclusivas subastas, donde coleccionistas y amantes del arte tienen la oportunidad de adquirirlas. Cada subasta en nuestra galería es una celebración de la creatividad internacional, brindando acceso a piezas raras y valiosas que no se encuentran fácilmente en el mercado.
                </p>
            </div>
        </div>
    </div>

</body>
</html>