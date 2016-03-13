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
		$sentencia = "SELECT Clave, Foto FROM ".$tablePrefix."usuarios WHERE NomUsuario = '$usuario'";

		// Guardamos el resultado
		$result = mysqli_query($iden, $sentencia);

		// Si todo va bien y existe el usuario
		if($result && mysqli_num_rows($result) != 0) {
			// Recogemos la clave y podemos liberar/cerrar
			$info = mysqli_fetch_array($result);
			mysqli_free_result($result);
			mysqli_close($iden);

			// El password es correcto
			if($info['Clave'] == $pass) {

				$_SESSION['sesion'] = $usuario;

				// Recordar
				if(isset($_POST['recordar'])) {
					//$_COOKIE['recordar'] = $usuario;
					setcookie("recordar", $usuario, time()+(365*24*60*60), "/", NULL); // Un año en segundos
					$recordar = true;
				}
				else { $recordar = false; }

				$foto = "http://".$_SERVER['HTTP_HOST'].rtrim(dirname($_SERVER['PHP_SELF']), '/\\')."/";

				if (is_null($info['Foto'])) { $foto .= "files/profile/default-avatar.png"; }
				else 						{ $foto .= $info['Foto']; }

				// Creamos la cookie para la ruta del avatar
				setcookie("avatar", $foto, time()+(365*24*60*60), "/", NULL);

				// Se crea el objeto JSON (usuario, foto, recordar)
				$userData = array('usuario'	=>	$usuario,
								  'foto'	=>	$foto,
								  'recordar' => $recordar);

				$respuesta = json_encode($userData);

				header('Content-Type: application/json');
				echo $respuesta;
			}
			// El password no es correcto
			else { echo "false"; }
		}
		// El usuario no existe
		else { echo "false"; }

	}
	// Si se quiere salir de la sesión
	elseif($accion == 'salir') {
		// Borra todas las variables de sesión
		$_SESSION = array();
		// Borra la cookie de sesión y avatar
		if(isset($_COOKIE['recordar'])) { setcookie('recordar', '', time()-3600, '/', NULL); }
		if(isset($_COOKIE['avatar'])) { setcookie('avatar', '', time()-3600, '/', NULL); }

		// Destruye la sesión
		session_destroy();

		header("Location: http://$host$uri/index.php");
		exit;
	}
?>
