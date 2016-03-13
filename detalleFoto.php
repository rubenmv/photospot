<!-- Contenido -->
<section id="content">
	<h3>Detalle de fotografía</h3>

	<?php
	// Se guarda en $iden
	require_once("includes/connectBD.inc");

	// Id de la foto a mostrar
	$idFoto = $_GET["id"];

	$sentencia =   "SELECT f.Album, f.Titulo AS TituloFoto, f.Fecha, f.NumVotos, f.PuntuacionTotal, f.Fichero,
						   a.Titulo AS TituloAlbum, p.NomPais, u.NomUsuario
					FROM ".$tablePrefix."fotos f
						LEFT JOIN ".$tablePrefix."albumes a ON f.Album = a.IdAlbum
						LEFT JOIN ".$tablePrefix."paises p ON f.Pais = p.IdPais
						LEFT JOIN ".$tablePrefix."usuarios u ON a.Usuario = u.IdUsuario
					WHERE f.IdFoto = $idFoto";
	$result = mysqli_query($iden, $sentencia);

	if($result) {
		$row = mysqli_fetch_array($result);

		?>
		<section id="detalle-foto" class="detalle-foto">
			<a href="<?php echo $row['Fichero']; ?>">
				<img src="<?php echo $row['Fichero']; ?>" alt="<?php echo $row['TituloFoto']; ?>" />
			</a><br />

			<section id="detalle-info" class="detalle-info">
				<h4 id="tituloFoto"><?php echo $row['TituloFoto']; ?></h4>
				<p>
					Álbum: <a href="index.php?content=13&amp;id=<?php echo $row['Album']; ?>"><?php echo $row['TituloAlbum']; ?></a>
				</p>
				<p>
					Por <span class="bold"><?php echo $usuario; ?></span><br />
						<span class="bold fecha"><?php echo $row['Fecha']; ?></span><br />
					<?php
						if ($row['NomPais'] != NULL) {	?>
							en <span class="bold"><?php echo $row['NomPais']; ?></span>
					<?php }	?>
				</p>
				<p>
					<span class="bold">Valoración de los usuarios</span><br />
					<div id="puntuacion-foto">
					<?php
						// Calculamos la puntuacion de la foto
						if($row['NumVotos'] > 0) {
							$puntuacion = $row['PuntuacionTotal'] / $row['NumVotos'];
							$puntuacion = round($puntuacion, 1);
					?>
							<meter id="medidor-puntos" min="0" max="5" value="<?php echo $puntuacion; ?>" low="2" high="4" optimum="4" title="Puntuación de <?php echo $puntuacion; ?> sobre 5"><?php echo $puntuacion; ?></meter>
					<?php
						} // No ha sido votada aun
						else { ?>
							Nadie ha votado esta foto. ¡Sé el primero!
					<?php } ?>
					</div>
				</p>
	<?php
		mysqli_free_result($result);
	}

	if(isset($iden)) { mysqli_close($iden); }

	?>
				<form id="form-votar" method="post" onsubmit="peticionAJAXVotar('votar.php', <?php echo $idFoto; ?>); return false;">
					<fieldset>
						<legend>Deja tu valoración:</legend>
							<select id="select-voto" name="select-voto">
								<option value="1">1</option>
								<option value="2">2</option>
								<option value="3" selected="selected">3</option>
								<option value="4">4</option>
								<option value="5">5</option>
							</select>
						<button class="boton boton-small" type="submit" value="Enviar">Votar</button>
					</fieldset>
				</form>
			</section>
		</section>

	<section id="section-comentarios" class="detalle-comentarios">
		<h4>Comentarios</h4>
		<!-- Formulario de nuevo comentario -->
		<form id="form-comentario" method="post" onsubmit="peticionAJAXEnviarComentario('insertComentario.php', <?php echo $idFoto; ?>, '<?php echo $usuario; ?>'); return false;">
			<fieldset>
				<legend id="tComentario">Deja un comentario</legend>

				<div class="fGrupo">
					<label id="new-comment-help" for="comentario">Deja un comentario (150 caracteres máx.)</label>
					<textarea name="comentario" id="comentario" maxlength="150"></textarea>
				</div>

				<div class="fGrupo"><button class="boton" type="submit" value="Enviar">Enviar</button></div>
			</fieldset>
		</form>

		<?php
			// Comprobamos si se recibe referencia a algun comentario en concreto
			if(isset($_GET['ref'])) { $ref = $_GET['ref']; }
			else { $ref = 0; }
		?>
		<!-- Lista de comentarios -->
		<script type="text/javascript">peticionAJAXComentarios('getComentarios.php', <?php echo $idFoto; ?>, 1, <?php echo $ref; ?>);</script>
	</section>
</section>

<!-- Una vez cargado todo, agregamos un nuevo elemento al historial -->
<script type="text/javascript">addToHistorial(<?php echo $idFoto; ?>);</script>