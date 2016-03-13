<body>

	<?php // Recogemos la direccion del host para pasarlo a timthumb para todas las imagenes
		$host = $_SERVER['HTTP_HOST'].rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
	?>

	<!-- Cabecera de la web -->
	<header id="header" class="header">
		<div class="bg-dark border-radius">
			<div id="titulos" class="titulos">
				<h1><a href="index.php" title="Inicio">Photo Spot</a></h1>
				<h2>El lugar para tus fotografías.</h2>
			</div>

			<section id="zona-usuario" class="zona-usuario">
				<?php
				// Usuario logado, muestra información
				if(isset($_SESSION['sesion'])) {
					// Recogemos la foto desde la base de datos
					require_once("includes/connectBD.inc"); // $iden

					$usuario = $_SESSION['sesion'];
					$sentencia = "SELECT Foto FROM ".$tablePrefix."usuarios
									WHERE NomUsuario = '$usuario'";

					$result = mysqli_query($iden, $sentencia);
					$row = mysqli_fetch_array($result);

					if($row['Foto'] !== NULL) $userFoto = $row['Foto'];
					else $userFoto = "files/profile/default-avatar.png";

					$userFoto = $_COOKIE['avatar'];

					require_once('includes/header_info_usuario.inc');
				}
				// Usuario no logado, formulario de login
				else {
					require_once('includes/header_form_acceso.inc');
				} ?>
			</section>

			<!-- Menu de navegacion principal -->
			<nav id="mainNav" class="main-nav center">
				<ul id="mainNavItemList">
					<li><a href="index.php?content=1">Portada</a></li>
					<li><a href="index.php?content=2">Buscar</a></li>
					<li><a href="index.php?content=4">Sobre el sitio</a></li>
					<?php if(isset($_SESSION['sesion'])) {
						echo    "<li><a href=\"index.php?content=10\">Subir foto</a></li>".
								"<li><a href=\"index.php?content=11\">Mis álbumes</a></li>".
							 	"<li><a href=\"index.php?content=17\">Historial</a></li>";
					}?>
				</ul>
			</nav>
		</div>

		<!-- Informacion y enlace de registro -->
		<?php if(!isset($_SESSION['sesion'])) { ?>
		<article id="info-pruebas" class="center info-pruebas">
			<p>
				Este sitio es solo una demostración, un campo de pruebas para el estudio de distintas tecnologías web.
				No se asegura su buen funcionamiento ni la preservación de los datos.
			</p>
		</article>
		<article id="info-registro" class="center info-registro">
			<p>
				Photo Spot es el lugar donde podrás compartir tus fotos con todo el mundo.
				Registrate ahora y tendrás acceso a las fotos del resto de usuarios, además
				de poder subir las tuyas y crear álbumes.<br />
				<a class="boton" href="index.php?content=5">¡Regístrate ahora!</a>
			</p>
		</article>
		<?php } ?>

	</header>