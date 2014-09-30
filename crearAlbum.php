<!-- Contenido -->
<section id="content">
	<h3>Nuevo álbum</h3>
	
	<!-- Formulario de registro	-->
	<form id="fAlbum" method="post" action="valida.php?id=<?php echo $_GET['content'];?>" onsubmit="return validarCampos(this)">
		<fieldset>
		<legend id="lAlbum">Formulario de registro de nuevo usuario</legend>

			<?php if(isset($_GET['msg']))
				switch ($_GET['msg']) {
					// Falta el título
					case 1:
						echo "<p class=\"fError\">Debes darle un título al álbum.</p>";
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

			<div class="fGrupo">
				<label class="label1" for="titulo">Título</label>
				<div class="controls">
					<input type="text" name="titulo" id="titulo" class="required" title="Campo obligatorio" required="required" onfocus="resetError(this)" />
					<p class="fAyuda">
						Obligatorio. Dále un nombre al álbum.
					</p>
				</div>
			</div>
			
			<div class="fGrupo">
				<label class="label1" for="desc">Descripción</label>
				<div class="controls">
					<textarea name="desc" id="desc" maxlength="150"></textarea>
					<p class="fAyuda">
						¿Que contendrá este álbum?<br />
						Máximo de 150 caracteres.
					</p>
				</div>
			</div>

			<!-- Paises desde la base de datos -->
			<?php require_once("includes/form_pais.inc"); ?>
			
			<div class="fGrupo">
				<label class="label1">&nbsp;</label> <!-- Para alinear el boton -->
				<div class="controls">
					<button name="crear" id="crear" class="boton" type="submit" value="Crear">Crear</button>
					<a href="index.php?content=8" class="boton botonCancel">Cancelar</a>
				</div>
			</div>

		</fieldset>
	</form>
</section>
