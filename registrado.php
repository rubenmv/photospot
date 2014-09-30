<?php

	$host = $_SERVER['HTTP_HOST'];
	$uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
	$extra = 'index.php';

	if(isset($_GET['user'])) {
		$usuario = $_GET['user'];
	}

	require_once("includes/connectBD.inc"); // $iden
	// Recuperamos los datos del nuevo usuario desde la BD
	$sentencia = "SELECT u.*, p.NomPais FROM usuarios u
					LEFT JOIN paises p ON p.IdPais = u.pais
					WHERE NomUsuario = '$usuario'";
	$result = mysqli_query($iden, $sentencia);

	if($result) {
		$row = mysqli_fetch_array($result);
		mysqli_free_result($result);
		mysqli_close($iden);

		$fecha = $row['FNacimiento'];
 ?>
		<section id="content">
			<h4>Registro completado</h3>

			<table id="tRegistro">
				<thead>
						<tr><th class="tTitulo" colspan="2"><h4>Datos introducidos</h4></th></tr>
				</thead>
				<tbody>
					<?php if($row['Foto'] != NULL) { ?>
					<tr><td class="cLeft">Foto:</td>
						<td><img class="avatarGrande" src="<?php echo $row['Foto']; ?>" alt="Foto de perfil" /></td></tr>
					<?php } ?>
					<tr><td class="cLeft">Nombre de usuario:</td>
						<td><?php echo $row["NomUsuario"]; ?></td></tr>
					<tr><td class="cLeft">Email:</td>
						<td><?php echo $row["Email"]; ?></td></tr>
					<tr><td class="cLeft">Sexo:</td>
						<td>
							<?php
							if($row['Sexo'] == 0) echo "Hombre";
							else echo "Mujer";
							?>
						</td></tr>
					<tr><td class="cLeft">Fecha de nacimiento:</td>
						<td><time class="fechaRes fecha" datetime="<?php echo $fecha; ?>"><?php echo $fecha; ?></time></td></tr>

					<?php if($row['NomPais'] != NULL) { ?>
						<tr><td class="cLeft">País:</td>
							<td><?php echo $row['NomPais']; ?></td></tr>
					<?php } if($row['Ciudad'] != NULL) { ?>
						<tr><td class="cLeft">Ciudad:</td>
							<td><?php echo $row['Ciudad']; ?></td></tr>
					<?php } ?>
				</tbody>
			</table>
		</section>
<?php } ?>

