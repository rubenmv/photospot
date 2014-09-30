	<!-- Contenido -->
	<section id="content">
		<?php
			// Error 1 signfica que el usuario no existe o la contraseña es incorrecta
			if(isset($_GET['msg'])) {	?>
				<p id="errorLogin" class="fError">
				<?php
					if($_GET['msg'] == 1) { echo "El usuario no existe, regístrate para acceder"; }
					elseif($_GET['msg'] == 2) { echo "La contraseña es incorrecta"; }
					elseif($_GET['msg'] == 3) { echo "Debes acceder con tu usuario para visitar esa página"; }
				?>
				</p>
		<?php } ?>
	</section>

<?php
	// Pié de pagina y cierre de </body> y </html>
	require_once("includes/pie.inc");
?>
