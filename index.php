<?php
	/*
	Indices para el parámetro 'content':

	1	-	Portada
	2	-	Búsqueda
	3	-	Resultados
	4	-	Información
	5	-	Registro
	6	-	Login y Error de login
	7	-	Detalle de fotografía
	8	-	Menú de usuario
	9	-	Crear álbum
	10	-	Subir fotografía
	11	-	Mis álbumes
	12	-	Registro terminado
	13	-	Detalle álbum
	14	-	Baja usuario
	15	-	Modificar datos
	16	-	Editor de foto
	17	-	Historial de fotos visitadas
	*/

	// Se recoge el parametro de contenido
	if(isset($_GET['content'])) {
		$content = $_GET['content'];
	}
	else {
		$content = '1';
	}

	// Sesion. También redirige cuando no hay permisos.
	require_once("includes/sesion.inc");

	// Titulo de página
	$title = "Photo Spot - ";
	switch ($content) {
		// Inicio
		case '1': $title = $title."Portada"; break;
		// Buscar
		case '2': $title = $title."Búsqueda"; break;
		// Resultados
		case '3': $title = $title."Resultados de búsqueda"; break;
		// Información
		case '4': $title = $title."Información"; break;
		// Registro
		case '5': $title = $title."Registro de nuevo usuario"; break;
		// Login
		case '6': $title = $title."Login / Acceso de usuario"; break;
		// Registrado
		case '12': $title = $title."Registro terminado"; break;

		// PÁGINAS PRIVADAS
		// Detalle de foto
		case '7': $title = $title."Detalle de fotografía"; break;
		// Panel de control
		case '8': $title = $title."Panel de control"; break;
		// Crear álbum
		case '9': $title = $title."Crear álbum"; break;
		// Subir foto
		case '10': $title = $title."Subir fotografía"; break;
		// Mis álbumes
		case '11': $title = $title."Mis álbumes"; break;
		// Detalle álbum
		case '13': $title = $title."Detalle álbum"; break;
		// Baja de usuario
		case '14': $title = $title."Darme de baja"; break;
		// Modificar datos
		case '15': $title = $title."Modificar mis datos"; break;
		// Editor de foto
		case '16': $title = $title."Editar fotografía"; break;
		// Hitorial de fotos visitadas
		case '16': $title = $title."Mi historial"; break;

		// Página no encontrada
		default: $title = $title."Página no encontrada"; break;
	}

	// Contenido HTML
	// Declaración html, <html> y <head>
	require_once("includes/head.inc");

	// Inicio del <body>
	// Título del sitio, menú de navegación y formulario de acceso
	require_once("includes/header.inc");

	// Mostramos el contenido según el parámetro content
	switch ($content) {
		// Inicio
		case '1': require_once("portada.php"); break;
		// Buscar
		case '2': require_once("busqueda.php"); break;
		// Resultados
		case '3': require_once("resultados.php"); break;
		// Información
		case '4': require_once("informacion.php"); break;
		// Registro
		case '5': require_once("registro.php"); break;
		// Login
		case '6': require_once("formAcceso.php"); break;
		// Registrado
		case '12': require_once("registrado.php"); break;

		// PÁGINAS PRIVADAS
		// Detalle de foto
		case '7': require_once("detalleFoto.php"); break;
		// Panel de control
		case '8': require_once("panelControl.php"); break;
		// Crear álbum
		case '9': require_once("crearAlbum.php"); break;
		// Subir foto
		case '10': require_once("subirFoto.php"); break;
		// Mis álbumes
		case '11': require_once("misAlbumes.php"); break;
		// Detalle álbum
		case '13': require_once("detalleAlbum.php"); break;
		// Baja de usuario
		case '14': require_once("bajaUsuario.php"); break;
		// Modificar datos
		case '15': require_once("modDatos.php"); break;
		// Editor de foto
		case '16': require_once("editorFoto.php"); break;
		// Historial de fotos visitadas
		case '17': require_once("historial.php"); break;
	}

	// Pié de pagina y cierre de </body> y </html>
	require_once("includes/pie.inc");

?>
