<?php require_once("includes/connectBD.inc"); // $iden ?>

<section id="content" class="content">
	<section id="ultimas-fotos">
		<h3>Últimas fotografías</h3>

		<ul id="lista-resumen" class="lista-resumen">
		<?php
			// Últimas 6 fotos registradas en el sistema
			$sentencia = 	"SELECT IdFoto, Titulo, Fecha, Fichero, NomPais
								FROM ".$tablePrefix."fotos
								LEFT JOIN ".$tablePrefix."paises ON IdPais = Pais
								ORDER BY Fecha DESC
								LIMIT 12";
			$result = mysqli_query($iden, $sentencia);

			if($result && mysqli_num_rows($result) > 0) {
				require('includes/resumenes.inc');
				mysqli_free_result($result);
			}
		?>
		</ul>
	</section>
	<aside id="portada-aside">

			<?php
				// Las 10 mejores fotos segun su puntuacion media
				$sentencia = 	"SELECT IdFoto, Titulo, Fecha, Fichero, NomPais
									FROM ".$tablePrefix."fotos
									LEFT JOIN ".$tablePrefix."paises ON IdPais = Pais
									WHERE NumVotos > 0
									ORDER BY (PuntuacionTotal DIV NumVotos) DESC
									LIMIT 10";

				$result = mysqli_query($iden, $sentencia);



				if($result && mysqli_num_rows($result) > 0) { ?>
					<section id="destacadas">
						<h3><a href="mejores_boilerplate/index.php">Las mejores</a></h3>
						<ul id="lista-destacadas" class="lista-destacadas">
							<?php require('includes/resumenes.inc'); mysqli_free_result($result); ?>
						</ul>
						<script type="text/javascript" src="js/slideshow.js"></script>
					</section>
			<?php }	?>

		<section id="comentarios-recientes">
			<script type="text/javascript">peticionAJAXLastComments();</script>
		</section>
	</aside>
</section>

<?php mysqli_close($iden); ?>
