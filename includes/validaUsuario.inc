<?php
// Validación del formulario de registro y modificación
// de datos de usuario.

// Expresiones regulares (PCRE)
$expReg = array(
		'nombre' => '/^[A-Za-z0-9]{3,15}$/',
		'clave'  => '/^(?=.*\d)(?=.*[A-Z])(?=.*[a-z])\w{6,15}$/',
		'email'  => '/^[\w-\+]+(\.[\w-]+)*@[A-Za-z0-9-]+(\.[A-Za-z0-9]+)*(\.[A-Za-z]{2,4})$/',
		'fecha'  => '/^\d{4}\-\d{2}\-\d{2}$/'
		);

// NOMBRE DE USUARIO
if(!preg_match($expReg['nombre'], $_POST['nombre'])) $valida = false;
// El formato del nombre es correcto
else {
	$nombre = $_POST['nombre'];

	$sentencia = "SELECT NomUsuario FROM ".$tablePrefix."usuarios WHERE NomUsuario = '$nombre'";
	$result = mysqli_query($iden, $sentencia);

	// Encuentra al menos un usuario con ese nombre
	if(mysqli_num_rows($result) > 0) {

		$row = mysqli_fetch_array($result);
		mysqli_free_result($result);

		// Si viene desde registro (5) o
		// viene desde modificación de datos (15) y el usuario es distinto al logado
		//		Devolvemos a la página anterior
		if ($id == 5 || ($id == 15 && $row['NomUsuario'] != $_SESSION['sesion'])) {
			mysqli_close($iden);
			$extra = 'index.php?content='.$id.'&msg=1'; // Usuario ya existe
			// No seguimos, redirige directamente
			header("Location: http://$host$uri/$extra");
			exit;
		}
	}
}

// CLAVE
// Si viene desde registro (5) o desde modificación (15) pero con contraseña distinta a 'default'
if($id == 5 || ($id == 15 && $_POST["pass"] != "default")) {
	if(!preg_match($expReg['clave'], $_POST['pass'])) $valida = false;
	// Si existe y es correcta miramos la confirmación de contraseña
	else if (isset($_POST["cpass"]) && $_POST["pass"] == $_POST["cpass"]) $pass = $_POST['pass'];
	else $valida = false;
}

// EMAIL
if(!preg_match($expReg['email'], $_POST['email']))	$valida = false;
else $email = $_POST['email'];

// FECHA
if( isset($_POST['fechaNac']) && trim($_POST['fechaNac']) !== '' ) {
	$fecha = $_POST['fechaNac'];
	if(!checkDateString($fecha)) { $valida = false; }
}
else { $valida = false; }

// SEXO
if(isset($_POST['sexo'])) $sexo = $_POST['sexo'];
else $sexo = 'NULL';

// PAÍS
if(isset($_POST['pais']) && $_POST['pais'] > 0)	$pais = $_POST['pais'];
else $pais = 'NULL';

// CIUDAD
if(isset($_POST['ciudad']) && trim($_POST['ciudad']) != '') {
	$ciudad = $_POST['ciudad'];
	$ciudad = "'$ciudad'";
}
else { $ciudad = 'NULL'; }

// FOTO DE USUARIO
if($_FILES['foto']['error'] == 0) {
	if (trim($_FILES['foto']['name']) != '') { // 'name' no es el de html, es el de FILES de php
		$foto = $_FILES['foto']['name'];
	}
	else { $foto = 'NULL'; }
}
// Si se quiere borrar le damos cualquier valor
else if (isset($_POST['borraFoto'])) { $foto = 'borra'; }

?>