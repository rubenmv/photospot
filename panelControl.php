<?php
	// Se guarda en $iden
	require_once("includes/connectBD.inc");

	$usuario = $_SESSION['sesion'];
	$sentencia = "SELECT u.*, p.NomPais FROM usuarios u
					LEFT JOIN paises p ON p.IdPais = u.pais
					WHERE NomUsuario = '$usuario'";

	$result = mysql_query($sentencia, $iden);
	$row = mysql_fetch_array($result);

	$fechaSQL = $row['FNacimiento'];
?>

<!-- Contenido -->
<section id="content" class="panel-control">
	<h3>Panel de control</h3>

	<aside id="controles-usuario" class="nav-aside">
		<ul>
			<li><a href="index.php?content=9">Crear álbum</a></li>
			<li><a href="index.php?content=10">Añadir foto a álbum</a></li>
			<li><a href="index.php?content=11">Mis álbumes</a></li>
			<li><a href="index.php?content=15">Modificar mis datos</a></li>
			<li><a href="index.php?content=14&amp;msg=1">Darme de baja</a></li>
			<li><a href="acceso.php?accion=salir" onclick="salirSesion();">Salir</a></li>
		</ul>
	</aside>

	<section id="info-usuario" class="info-usuario">
		<table id="tDatos">
			<tbody>
				<?php if($row['Foto'] !== NULL) { ?>
				<tr><td class="cLeft">Foto:</td>
					<td><img class="avatarGrande" src="<?php echo $row['Foto']; ?>" alt="Foto de perfil" /></td></tr>
				<?php } ?>
				<tr><td class="cLeft">Usuario:</td>
					<td><?php echo $row['NomUsuario']; ?></td></tr>
				<tr><td class="cLeft">Email:</td>
					<td><?php echo $row['Email']; ?></td></tr>
				<tr><td class="cLeft">Sexo:</td>
					<td><?php
						if($row['Sexo'] == 0) echo "Hombre";
						else echo "Mujer";
						?>
					</td>
				</tr>
				<tr><td class="cLeft">Fecha de nacimiento:</td>
					<td>
						<time class="fecha" datetime="<?php echo $fechaSQL; ?>"><?php echo $fechaSQL; ?></time>
					</td>
				</tr>
				<?php if($row['NomPais'] != NULL) { ?>
					<tr><td class="cLeft">País:</td>
						<td><?php echo $row['NomPais']; ?></td>
					</tr>
				<?php } if($row['Ciudad'] != NULL) { ?>
					<tr><td class="cLeft">Ciudad:</td>
						<td><?php echo $row['Ciudad']; ?></td></tr>
				<?php } ?>
			</tbody>
		</table>
	</section>
</section>