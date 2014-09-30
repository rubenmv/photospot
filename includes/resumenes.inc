<?php

// Establecemos el ancho y alto por defecto de las miniaturas
if(!isset($imgWidth)) $imgWidth = 300;
if(!isset($imgHeight)) $imgHeight = 180;

while ($row = mysqli_fetch_array($result)) {

	$pais = $row['NomPais'];
	$fecha = $row['Fecha'];

	// Direccion completa de la imagen actual
	$pathImg = "http://".$host."/".$row['Fichero'];
 ?>
	<li class="resumen">
		<figure class="figure">
			<a href="index.php?content=7&amp;id=<?php echo $row['IdFoto']; ?>">
				<img class="miniature" draggable="true" src="timthumb.php?src=<?php echo $pathImg; ?>&amp;w=<?php echo $imgWidth; ?>&amp;h=<?php echo $imgHeight; ?>" alt="<?php echo $row['Titulo']; ?>" />
			</a>
			<figcaption class="figcaption">
				<a href="index.php?content=7&amp;id=<?php echo $row['IdFoto']; ?>">
					<h4 class="titulo"><?php echo $row['Titulo']; ?></h4>
					<?php if($fecha !== NULL || $pais !== NULL) { ?>
					<p>
						<?php if($fecha !== NULL) {?>
						<strong><time class="fecha" datetime="<?php echo $fecha; ?>"><?php echo $fecha; ?></time></strong><br />
						<?php }
							  if($pais !== NULL) {?>
						<strong><span class="pais"><?php echo $pais; ?></span></strong>
						<?php } ?>
					</p>
					<?php } ?>
				</a>
			</figcaption>
		</figure>
	</li>
<?php } ?>