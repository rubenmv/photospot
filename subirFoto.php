<section id="content">
	<h3>Subir fotografía</h3>

<?php
		// Se guarda en $iden
		require_once("includes/connectBD.inc");

		$usuario = $_SESSION['sesion'];

		// Recupera todos los álbumes del usuario en sesión
		$sentencia = "SELECT * FROM albumes, usuarios WHERE Usuario = IdUsuario AND NomUsuario = '$usuario'";
		$result = mysql_query($sentencia, $iden);

		if($result && mysql_num_rows($result) != 0) {
			mysql_free_result($result);
?>

	<!-- Formulario de registro	-->
	<form id="fFoto" method="post" action="valida.php?id=<?php echo $_GET['content'];?>" enctype="multipart/form-data" onsubmit="return validarCampos(this);">
		<fieldset>
		<legend id="lFoto">Formulario de registro de nuevo usuario</legend>

			<?php if(isset($_GET['msg']))
				switch ($_GET['msg']) {
					// Datos introducidos incorrectos
					case 1:
						echo "<p class=\"fError\">Debes darle un título a la foto.</p>";
						break;
					case 2:
						echo "<p class=\"fError\">Datos incorrectos. Revise el formulario.</p>";
						break;
					// Error en la query
					case 3:
						echo "<p class=\"fError\">Hubo un problema. Inténtelo más tarde.</p>";
						break;
				}
			?>

			<!-- Álbumes desde la base de datos -->
			<?php require_once("includes/form_album.inc"); ?>

			<!-- Título -->
			<div class="fGrupo">
				<label class="label1" for="titulo">Título</label>
				<div class="controls">
					<input type="text" name="titulo" id="titulo" class="required" title="Campo obligatorio" onfocus="resetError(this)" />
					<p class="fAyuda">Dále un título a la fotografía.</p>
				</div>
			</div>

			<!-- Paises desde la base de datos -->
			<?php require_once("includes/form_pais.inc"); ?>

			<!-- Fichero -->
			<?php $required = 'required'; require_once("includes/form_file.inc"); ?>

			<div class="fGrupo">
				<label class="label1">&nbsp;</label> <!-- Para alinear el boton -->
				<div class="controls">
					<button name="crear" id="crear" class="boton" type="submit" value="Crear">Subir</button>
					<a href="index.php?content=8" class="boton botonCancel">Cancelar</a>
				</div>
			</div>

		</fieldset>
	</form>
	<?php
	// No se han encontrado álbumes
	} else {
		echo "<p>¡No se han encontrado álbumes!<br />
		      <a href=\"index.php?content=9\">Crea tu primer álbum para subir fotos.</a></p>";
	}

	if(isset($iden)) {
		mysql_close($iden);
	}
	?>
</section>