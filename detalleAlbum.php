<!-- Contenido -->
<section id="content">
	<?php
		require_once("includes/connectBD.inc"); // $iden

		// Seleccionamos el nombre del álbum
		$id = $_GET['id'];
		$sentencia = 	"SELECT Titulo, Descripcion, Fecha, Pais FROM ".$tablePrefix."albumes WHERE IdAlbum = $id";
		$result = mysqli_query($iden, $sentencia);
		$row = mysqli_fetch_array($result);

	// Información del álbum ?>
	<h3>Álbum: <?php echo $row['Titulo']; ?></h3>
	<section class="infoAlbum">
	<?php // Información opcional sobre el álbum
		if ($row['Descripcion'] != NULL) {
			echo "$row[Descripcion]<br />";
		}
		if ($row['Fecha'] != NULL || $row['Pais'] != NULL) {
			echo "Fotografías tomadas ";
			if($row['Fecha'] != NULL) {
				// Fecha MySQL a formato dd/mm/aaaa
				$fecha = explode("-", $row['Fecha']);
				$fecha = $fecha[2].'/'.$fecha[1].'/'.$fecha[0];
				echo "el <span class=\"bold\">$fecha</span>";
			}
			if($row['Pais'] != NULL) {
				// Ya no necesitamos el resultado anterior
				mysqli_free_result($result);
				// Seleccionamos el nombre del pais
				$sentencia = "SELECT NomPais FROM ".$tablePrefix."paises WHERE IdPais = $row[Pais]";
				$result = mysqli_query($iden, $sentencia);
				$row = mysqli_fetch_array($result); // Tampoco necesitamos el row anterior
				echo " en <span class=\"bold\">$row[NomPais]</span>";
			}
		}
		?>
	</section>

	<?php

		// Seleccionamos las fotos del álbum
		$sentencia = 	"SELECT IdFoto, Titulo, Fecha, Fichero, NomPais, Pais
							FROM ".$tablePrefix."fotos
								LEFT JOIN ".$tablePrefix."paises ON IdPais = Pais
							WHERE Album = $id
							ORDER BY Fecha DESC";
		$result = mysqli_query($iden, $sentencia);

		if($result && mysqli_num_rows($result) > 0) {
		?>
		<section class="res-fotos">
			<ul id="lista-resumen" class="lista-resumen">
			<?php
				require('includes/resumenes.inc');

				mysqli_free_result($result);
				mysqli_close($iden);
			?>
			</ul>
		</section>
		<?php
		} else {
			echo "<p>¡No se han encontrado fotos en este álbum!<br />
			      <a href=\"index.php?content=10\">Sube una foto a este álbum</a></p>";
		}
		?>
</section>
