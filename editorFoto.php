<!-- Contenido -->
<section id="content">
	<h3>Editor de fotografía</h3>
	
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
		<section id="editor">
			<canvas id="canvas" width="640" height="480">
				Tu navegador no soporta canvas.
			</canvas>
			<script type="text/javascript" src="js/canvas-editor.js"></script>

			<form id="form-edit-controls" onsubmit="return false;">
				<label class="bold">Herramienta de dibujo</label><br>
				<button onclick="setTool('line');">Línea</button> 
				<button onclick="setTool('rectangle');">Rectángulo</button> 
				<button onclick="setTool('circle');">Círculo</button><br><br>

				<label for="color" class="bold">Color</label><br>
				<input id="color" name="color" type="color" />
				<label for="bg_transp">Fondo transparente</label>&nbsp;
				<input id="bg_transp" name="bg_transp" type="checkbox" checked="checked" /><br><br>

				<label for="grosor" class="bold">Grosor</label><br>
				<span>1<input id="grosor" name="grosor" type="range" min="1" max="10" step="1" />10</span><br><br>
				
				<input type="button" value="Escalar" onclick="scale();"/>
				<input id="factor_escala" type="text" value="100" style="width: 30px" />%<br><br>
				
				<label for="grosor" class="bold">Rotar</label><br>
				<button onclick="rotate(-90);">&lt;- 90º</button>
				<button onclick="rotate(90);">90º -&gt;</button><br /><br>

				<label class="bold">Filtros de color</label><br>
				<button onclick="filtroColor('GR');">Escala de grises</button>
				<button onclick="filtroColor('S');">Sepia</button>
				<button onclick="filtroColor('R');">R</button>
				<button onclick="filtroColor('G');">G</button>
				<button onclick="filtroColor('B');">B</button><br /><br>

				<button onclick="resetImagen();">Resetear la imagen</button>
				<button onclick="saveCanvas();">Guardar</button>
			</form>

			<script>
				// Cargamos la imagen original
				cargarImagen("<?php echo $row['Fichero']; ?>");
				initEditor();
			</script>
		</section>		
			
	<?php
		mysqli_free_result($result);
	}
	
	if(isset($iden)) {
		mysqli_close($iden);
	}

	?>
</section>
