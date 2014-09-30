<!-- Contenido -->
<section id="content">
	<h3>Búsqueda</h3>
	<!-- Formulario de registro	-->
	<form id="form-busqueda" class="form-page" method="post" onsubmit="peticionAJAXBusqueda('buscarFotos.php', 0); return false;">
		<fieldset>
			<legend id="lBusqueda">Formulario de búsqueda</legend>
			<!-- Titulo -->
			<div class="fGrupo">
				<label class="label1" for="titulo">Título de la fotografía</label>
				<div class="controls">
					<input type="text" name="titulo" id="titulo" />
				</div>
			</div>
			<!-- Fecha -->
			<div class="fGrupo">
				<label class="label1">Fecha</label>
				<div class="controls">
					<?php include 'includes/form-busqueda-fechas.inc'; ?>
				</div>
			</div>
			<!-- Paises desde la base de datos -->
			<?php require_once("includes/form_pais.inc"); ?>
		</fieldset>

		<div class="fGrupo">
			<label class="label1">&nbsp;</label> <!-- Para alinear el boton -->
			<div class="controls">
				&nbsp;<button name="buscar" class="boton" type="submit" value="Buscar">Buscar</button>
			</div>
		</div>
	</form>

	<?php include_once 'includes/form-ordena.inc'; ?>
</section>
