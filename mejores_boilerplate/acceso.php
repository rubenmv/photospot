<?php
	session_start();

	$accion = $_GET['accion'];
	// Primera parte de la url
	$host = $_SERVER['HTTP_HOST'];
	$uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');

	// Si se quiere hacer login.
	if($accion == 'entrar') {

		$usuario = $_POST['usuario'];
		// El password se pasa por hash sha256
		$pass = hash('sha256', $_POST['password']);

		// Conectamos con la BD. Se guarda en $iden
		require("includes/connectBD.inc");

		// Busca el usuario en la BD y recupera su clave
		$sentencia = "SELECT Clave, Foto FROM usuarios WHERE NomUsuario = '$usuario'";

		// Guardamos el resultado
		$result = mysql_query($sentencia, $iden);

		// Si todo va bien y existe el usuario
		if($result && mysql_num_rows($result) != 0) {
			// Recogemos la clave y podemos liberar/cerrar
			$info = mysql_fetch_array($result);
			mysql_free_result($result);
			mysql_close($iden);

			// El password es correcto
			if($info['Clave'] == $pass) {
				$_SESSION['sesion'] = $usuario;

				// Recordar
				if(isset($_POST['recordar'])) {
					$_COOKIE['recordar'] = $usuario;
					$_COOKIE['visitaActual'] = $fecha;
					$_COOKIE['ultimaVisita'] = "Esta es tu primera visita";

					setcookie("recordar", $usuario, time()+(365*24*60*60), "/"); // Un año en segundos
					// Recogemos la fecha actual
					$diaHora = date('Y-m-d')."T".date('H:i');
					$fecha = "Última visita: <time datetime=\"".$diaHora."\"><span class=\"fecha\">".date('d/m/Y')."</span> a las ".date('H:i:s')." horas</time>";
					// Esta cookie recoge la fecha actual
					setcookie("visitaActual", $fecha, time()+(365*24*60*60), "/");
					// Esta cookie guardará la fecha de la visita anterior $_COOKIE['visita']
					setcookie("ultimaVisita", "Esta es tu primera visita", time()+(365*24*60*60), "/");
				}

				$path = "http://".$_SERVER['HTTP_HOST'].rtrim(dirname($_SERVER['PHP_SELF']), '/\\')."/";
				// Se crea el objeto JSON (usuario, foto, recordar)
				$userData = array('usuario' => $usuario,
								'foto' => $path.$info['Foto'],
								'recordar' => isset($_POST['recordar']));

				$respuesta = json_encode($userData);

				header('Content-Type: application/json');
				echo $respuesta;
			}
			// El password no es correcto
			else {
				echo "false";
			}
		}
		// El usuario no existe
		else {
			echo "false";
		}

	}
	// Si se quiere salir de la sesión
	elseif($accion == 'salir') {
		// Borra todas las variables de sesión
		$_SESSION = array();
		// Borra la cookie de sesión
		if(isset($_COOKIE['recordar'])) {
			setcookie('recordar', '', time()-3600);
		}
		if(isset($_COOKIE['visitaActual'])) {
			setcookie('visitaActual', '', time()-3600);
		}
		if(isset($_COOKIE['ultimaVisita'])) {
			setcookie('ultimaVisita', '', time()-3600);
		}
		
		// Destruye la sesión
		session_destroy();

		header("Location: http://$host$uri/index.php");
		exit;
	}

	
?>
