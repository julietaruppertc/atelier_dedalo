<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Cargar artistas</title>
	<link rel="icon" href="../Imagenes/2-removebg-preview.png" type="image/png">
	<link rel="stylesheet" href="styles.css">
</head>
<body>
	<?php session_start();
	include 'conexion_galeria.php';
	include('../menu.php');?>
	<div id="formulario_artista">
		<fieldset>
			<legend>Cargar artista nuevo</legend>
				<form action="admi_artistas.php" method="get">
					<label>Nombre</label>
					<input type="text" name="nombre" placeholder="Nombre" required><br>

					<label>Apellido</label>
					<input type="text" name="apellido" placeholder="Apellido" required><br>

					<label>Nacionalidad</label>
					<input type="text" name="nacionalidad" placeholder="Nacionalidad" required><br>

					<label>Nacimiento</label>
					<input type="date" name="nacimiento" placeholder="Nacimiento" required><br>

					<label>Fallecimiento</label>
					<input type="date" name="fallecimiento" placeholder="Fallecimiento"><br>

					<input type="submit" value="Cargar">
				</form>
		</fieldset>
		<?php
      			if(isset($_GET['nombre'], $_GET['apellido'], $_GET['nacionalidad'], $_GET['nacimiento'], $_GET['fallecimiento'])){
      				#adentro para verificar que definidos
      				$nombre = $_GET['nombre'];
				$apellido = $_GET['apellido'];
				$nacionalidad = $_GET['nacionalidad'];
				$nacimiento = $_GET['nacimiento'];
				$fallecimiento = $_GET['fallecimiento'];

				if($nombre != NULL && $apellido != NULL && $nacionalidad != NULL){
					$sqlInsertar = "INSERT INTO artista(nombre, apellido, nacionalidad, nacimiento, fallecimiento) VALUES ('$nombre','$apellido','$nacionalidad','$nacimiento','$fallecimiento')";
            			mysqli_query($mysqli,$sqlInsertar);
            			echo '<script>alert("Se guardó correctamente");</script>'; 
                		echo '<script>window.location.href = "admi_artistas.php";</script>'; 
                		exit;
					}
					else{
						echo '<script>
								alert("Hubo un error al cargar al artista.");
								window.location.href = "admi_artistas.php";
							</script>';
					}

     			}
        
        	$sql = "SELECT * FROM artista";
			$resultado =mysqli_query($mysqli,$sql);
		?>
	</div>
	<div id="tabla_artista">
  		<table>
      		<thead>
      			<th>ID del artista</th>
          		<th>Nombre</th>
          		<th>Apellido</th>
          		<th>Nacionalidad</th>
          		<th>Nacimiento</th>
				<th>Fallecimiento</th>
				<th></th>
      		</thead>
      	<tbody>
          	<?php
         		 while($filas = mysqli_fetch_assoc($resultado)) {
          	?>

          	<tr>
          		<td><?php echo $filas['ID_Artista'] ?></td>
              	<td><?php echo $filas['nombre'] ?></td>
              	<td><?php echo $filas['apellido'] ?></td>
              	<td><?php echo $filas['nacionalidad'] ?></td>
              	<td><?php echo $filas['nacimiento'] ?></td>
              	<td><?php echo $filas['fallecimiento'] ?></td>
              	<td>
    				<a href="admi_modificar_artista.php?ID_Artista=<?php echo $filas['ID_Artista']?>">Modificar</a>
                  	<a href="admi_baja_artista.php?ID_Artista=<?php echo $filas['ID_Artista']?>">Eliminar</a>
              	</td>
          	</tr>
          	<?php }?>
      	</tbody>
  		</table>
	</div>
</body>
</html>