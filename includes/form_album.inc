<?php
// Aquí se recueperan los álbumes de la base de datos y
// se muestra un selector solo si existe alguno.

// Se guarda en $iden
require_once("connectBD.inc");

// Usuario de la sesion
$sessionUser = $_SESSION['sesion'];
$sentencia = "SELECT IdAlbum, Titulo FROM albumes a, usuarios u WHERE NomUsuario = '$sessionUser' AND Usuario = IdUsuario";
$result = mysqli_query($iden, $sentencia);

// Solo se muestra el selector si se ha encontrado algún álbum
if($result) {
?>
<div class="fGrupo">
	<label class="label1">Escoge un álbum</label>
	<div class="controls">
		<select id="album" name="album" class="required" onchange="resetError(this);">
			<option value="0" selected="selected">[Escoge un álbum]</option>
			<?php // El resto de álbumes a través de la BD
				while($row = mysqli_fetch_array($result)) {
					echo "<option value=".$row['IdAlbum'].">".$row['Titulo']."</option>";
				}
				// Libera memoria del resultado y cerrar
				mysqli_free_result($result);
			?>
		</select>
	</div>
</div>
<?php
// En caso de no haber álbumes, se pide crear uno
} else
	echo "<p>¡No hay álbumes! <a href='index.php?content=9'>Crea tu primer álbum</a> para poder subir fotografías.</p>";
?>
