<?php

	// Primera parte de la url
	$host = $_SERVER['HTTP_HOST'];
	$uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
	$rutaHostCompleta = "http://".$_SERVER['HTTP_HOST'].rtrim(dirname($_SERVER['PHP_SELF']), '/\\')."/";
	$extra = 'index.php';
	$avatarDir = 'files/profile/';
	$fotoDir = 'files/';
	session_start();

	// Las fecha tienen que ser en formato yyyy-mm-dd
	function checkDateString($date) {
		if (date('Y-m-d', strtotime($date)) == $date) { return true; }
		else { return false; }
	}

	if(isset($_GET['id'])) {

		// Comprobamos si existe el usuario
		require("includes/connectBD.inc"); // $iden

		$id = $_GET['id'];
		// Indica si se pasa la validación
		$valida = true;

		/*  Códigos de página:
			5   -   Registro de nuevo usuario
			9   -   Crear álbum
			10  -   Subir fotografía
			14  -   Baja de usuario
			15  -   Modificación de datos
		*/
		switch ($id) {
			// REGISTRO DE NUEVO USUARIO
			case 5:
				require("includes/validaUsuario.inc");

				// Si todo está bien, creamos la sentencia
				if ($valida) {
					// Se cifra el password antes de introducirlo
					$pass = hash('sha256', $pass);
					$sentencia = "INSERT INTO `usuarios` (`NomUsuario`, `Clave`, `Email`, `Sexo`, `FNacimiento`, `Ciudad`, `Pais`) VALUES
								  ('$nombre', '$pass', '$email', $sexo, '$fecha', $ciudad, $pais)";
				}
				break;
			// CREAR ÁLBUM
			case 9:
				// Titulo obligatorio
				if (isset($_POST['titulo']) && trim($_POST['titulo']) != '') $titulo = $_POST['titulo'];
				else $valida = false;

				// No obligatorios
				$desc = $pais = 'NULL';

				// Recogemos lo que falte
				if (isset($_POST['desc']) && trim($_POST['desc']) != '') {
						if (strlen($_POST['desc']) <= 150) {
							$desc = $_POST['desc'];
							$desc = "'$desc'";
						}
						else $valida = false;
				}

				if (isset($_POST['pais']) && $_POST['pais'] > 0) $pais = $_POST['pais'];

				// Si todo está bien, creamos la sentencia
				if ($valida) {
					$usuario = $_SESSION['sesion'];
					// Obtenemos la id del usuario
					$sentencia = "SELECT IdUsuario FROM usuarios WHERE NomUsuario = '$usuario'";
					$result = mysql_query($sentencia, $iden);
					if ($result) {
						$row = mysql_fetch_array($result);
						mysql_free_result($result);

						$sentencia = "INSERT INTO `albumes` (`Titulo`, `Descripcion`, `Pais`, `Usuario`) VALUES
									('$titulo', $desc, $pais, $row[IdUsuario])";
					}
					else { $valida = false; }
				}
				break;
			// SUBIR FOTOGRAFÍA
			case 10:
				// Album es obligatorio
				if(isset($_POST['album']) && $_POST['album'] > 0)
					$album = $_POST['album'];
				else $valida = false;

				// Titulo obligatorio
				if (isset($_POST['titulo']) && trim($_POST['titulo']) != '') {
					$titulo = $_POST['titulo'];
				}
				else $valida = false;

				// Fichero obligatorio
				if (trim($_FILES['foto']['name']) != '') {
					$foto = $fotoDir.time()."_".$_FILES['foto']['name'];
				}
				else $valida = false;

				// No obligatorios
				$pais = 'NULL';

				if(isset($_POST['pais']) && $_POST['pais'] > 0)
					$pais = $_POST['pais'];

				if($valida) {
					$sentencia = "INSERT INTO `fotos` (`Titulo`, `Pais`, `Album`, `Fichero`) VALUES
									('$titulo', $pais, $album, '$foto')";
				}
				break;
			// BAJA DE USUARIO
			case 14:
				if (isset($_SESSION['sesion'])) {
					$usuario = $_SESSION['sesion'];
					// Recogemos todas sus fotos asociadas para borrarlas despues
					// Foto de usuario
					$sentencia = "SELECT Foto FROM usuarios WHERE NomUsuario = '$usuario'";
					$result = mysql_query($sentencia, $iden);
					$row = mysql_fetch_array($result);
					$avatar = $row['Foto'];
					mysql_free_result($result);
					// Fotos de albumes
					$sentencia = "SELECT Fichero FROM fotos, albumes, usuarios
									WHERE Album = IdAlbum
									AND Usuario = IdUsuario
									AND NomUsuario = '$usuario'";
					$fotosAlbumes = mysql_query($sentencia, $iden);

					// Como el nombre de usuario es único, lo utilizamos para borrarlo
					$sentencia = "DELETE FROM usuarios WHERE NomUsuario = '$usuario'";
				}
				// No valida, pero al ser id=14 redirige a index.php (ver más adelante)
				else { $valida = false; }
				break;
			// MODIFICACIÓN DE DATOS DE USUARIO
			case 15:
				require("includes/validaUsuario.inc");
				// Si todo está bien, creamos la sentencia
				if ($valida) {
					$usuario = $_SESSION['sesion'];
					// Creamos 2 sentencias distintas dependiendo de si se ha cambiado el password
					if (isset($pass)) {
						$pass = hash('sha256', $pass);
						$sentencia = "UPDATE usuarios SET
										NomUsuario = '$nombre',
										Clave = '$pass',
										Email = '$email',
										Sexo = $sexo,
										FNacimiento = '$fecha',
										Ciudad = $ciudad,
										Pais = $pais
										WHERE NomUsuario = '$usuario'";
					}
					else {
						$sentencia = "UPDATE usuarios SET
										NomUsuario = '$nombre',
										Email = '$email',
										Sexo = $sexo,
										FNacimiento = '$fecha',
										Ciudad = $ciudad,
										Pais = $pais
										WHERE NomUsuario = '$usuario'";
					}
				}
				break;
		}// FIN SWITCH

		// Si la validación se ha pasado EJECUTAMOS LA SENTENCIA SQL
		if ($valida) {
			$result = mysql_query($sentencia, $iden);

			// Si la sentencia falla, redirigimos con error 3 (Hubo un problema)
			if (!$result) {
				$extra .= '?content='.$id.'&msg=3';
			}
			// Si todo ha ido bien, redirigimos a donde corresponda
			else {
				// FOTO DE AVATAR
				if(isset($foto) && $foto != 'NULL') {
					// Y estamos en los casos de registro
					if($id == 5) {
						$ruta = $avatarDir.mysql_insert_id().'-'.$foto;
						$foto = "'$ruta'";
					}
					// Cuando es una modificacion
					else if ($id == 15) {
						$sentencia = "SELECT IdUsuario, Foto FROM usuarios WHERE NomUsuario = '$nombre'";
						$result = mysql_query($sentencia, $iden);
						$row = mysql_fetch_array($result);
						mysql_free_result($result);
						// Hacemos unlink solo cuando exista foto
						if($row['Foto'] !== NULL) { unlink($row['Foto']); }

						if($result) {
							if(isset($_POST['borraFoto'])) {
								$foto = 'NULL';
								setcookie("avatar", $rutaHostCompleta."files/profile/default-avatar.png", time()+(365*24*60*60), "/", NULL);
							}
							else {
								$foto = $avatarDir.$row['IdUsuario'].'-'.$foto;
								$ruta = $foto;
								setcookie("avatar", $rutaHostCompleta.$foto, time()+(365*24*60*60), "/", NULL);
								$foto = "'$foto'";
							}
						}
						else {
							$extra .= '?content='.$id.'&msg=3'; // Error en datos
						}
					}

					if($id==5 || $id==15) {
						// Hacemos el update sobre el usuario recien creado
						$sentencia = "UPDATE usuarios SET Foto = $foto
									  WHERE NomUsuario = '$nombre'";
						$result = mysql_query($sentencia, $iden);

						// Si la query ha funcionado
						if($result) {
							if ($foto != 'NULL') { move_uploaded_file($_FILES["foto"]["tmp_name"], $ruta); }
						}
						else { $extra .= '?content='.$id.'&msg=3'; } // Error en datos
					}
				}

				switch ($id) {
					case 5:
						// Página con información de registro
						$extra .= '?content=12&user='.$nombre;
						break;
					case 9:
						// Album creado --> 11 - Mis álbumes
						$extra .= '?content=11';
						break;
					case 10:
						// Copiar la foto al directorio
						move_uploaded_file($_FILES["foto"]["tmp_name"], $foto);
						// Foto subida --> 13 - Detalle álbum
						$extra .= '?content=13&id='.$album;
						break;
					case 14:
						// Baja correcta, borramos las fotos
						if($avatar !== NULL) {
							unlink($avatar);
						}
						while ($row = mysql_fetch_array($fotosAlbumes)) {
							unlink($row['Fichero']);
						}
						mysql_free_result($fotosAlbumes);

						// Borra todas las variables de sesión
						$_SESSION = array();
						// Borra la cookie de sesión
						if(isset($_COOKIE['recordar'])) {
							setcookie('recordar', '', time()-3600);
						}
						// Destruye la sesión
						session_destroy();
						$extra .= '?content=14&msg=2';
						break;
					case 15:
						// Cambiamos el usuario de la sesión
						$_SESSION['sesion'] = $nombre;

						$extra .= '?content=8';
						break;
				}
			}
		}
		// BAJA DE USUARIO
		else if ($id != 14) {
			// Cuando sea la baja, vamos a portada, en caso contrario devolvemos error de datos
			$extra .= '?content='.$id.'&msg=2';
		}

	}

	if(isset($iden))
		mysql_close($iden);

	header("Location: http://$host$uri/$extra");
	exit;
?>