<?php
	// Comprobación de sesión y redirecciones
	if (!isset($_SESSION)) {
		session_start();
	}

	// NOTA: Cuando se abra el navegador, aunque se "recuerde", no existirá la sesión
	if(!isset($_SESSION['sesion'])) {
		/*	Si se está recordando al usuario:
				1. Actualiza la visita
				2. Crea la sesión anterior */

		if(isset($_COOKIE['recordar'])) {
			// Sesion
			$_SESSION['sesion'] = $_COOKIE['recordar'];
			// Recarga la página
			$host = $_SERVER['HTTP_HOST'];
			$uri = $_SERVER['PHP_SELF'];
			header("Location: http://$host$uri");
			exit;
		}

		// Páginas privadas
		$privadas = array('7', '8', '9', '10', '11', '16', '17');
		if (isset($content) && in_array($content, $privadas, TRUE)) {
			// Si no hay sesión redirige a login
			header("Location: ?content=6&msg=3");
		}
	}
 ?>