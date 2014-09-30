<?php
	if (isset($_GET['msg'])) {
		$msg = $_GET['msg'];
	}

?>
<section id="content">
<?php

	/* Codigos de accion:
		1 -> Confirmar la baja por parte del usuario
		2 -> Confirma que la baja ha sido realizada
		3 -> Ha habido un problema interno de MySQL
	*/

	// El usuario debe confirmar la baja
	if($msg == 1) {
 ?>
		<h3>Darme de baja</h3>
		<p id="informacion">
			¿Estás seguro que quieres darte de baja? Si confirmas se eliminará tu información de usuario
			y ya no tendrás acceso a las fotografías.
		</p>
		<form id="fBaja" method="post" action="valida.php?id=<?php echo $_GET['content'];?>">
			<fieldset>
			<legend id="lBaja">Darme de baja</legend>
				<div class="fGrupo">
					<label class="label1">&nbsp;</label> <!-- Para alinear el boton -->
					<div class="controls">
						<button name="Confirmar" id="Confirmar" class="boton" type="submit" value="Confirmar">Confirmar</button>
						<a href="index.php?content=8" class="boton botonCancel">Cancelar</a>
					</div>
				</div>
			</fieldset>
		</form>
<?php
	}
	// La baja ha sido realizada
	else if ($msg == 2) {
?>
		<h4>Baja realizada</h4>
		<p id="informacion">
			La baja ha sido realizada correctamente. Sentimos perderte como usuario.
		</p>
<?php
	}
	// Error de MySQL al dar de baja
	else if ($msg == 3) {
?>
		<h4>Hubo un error</h4>
		<p id="informacion">
			Lo sentimos pero ha habido un error al intentar realizar tu solicitud de baja.<br />
			Por favor, inténtalo más tarde.
		</p>
<?php
	}
	// Redirige a portada
	else {
		// Primera parte de la url
		$host = $_SERVER['HTTP_HOST'];
		$uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
		$extra = "index.php";

		header("Location: http://$host$uri/$extra");
	}
?>
 </section>
