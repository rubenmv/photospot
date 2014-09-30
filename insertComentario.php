<?php 
	$usuario = $_POST['usuario'];
	$texto = $_POST['texto'];
	$idFoto = $_POST['idFoto'];

	require_once("includes/connectBD.inc"); // $iden

	// Recogemos la id del usuario que envia el comentario
	$sentencia = "SELECT IdUsuario FROM usuarios WHERE NomUsuario = '$usuario'";
	$result = mysql_query($sentencia, $iden);

	if($result) {
		$row = mysql_fetch_array($result);
		$idUsuario = $row['IdUsuario'];
		mysql_free_result($result);

		$sentencia = "INSERT INTO `comentarios` (`IdFoto`, `IdUsuario`, `Texto`) VALUES
								  ('$idFoto', '$idUsuario', '$texto')";

		$result = mysql_query($sentencia, $iden);

		if(isset($iden)) {
			mysql_close($iden);
		}
		
		// Si todo va bien devolvemos el id de la foto
		if($result) {
			echo $idFoto;
		}
		else {
			echo "false";
		}
    	
	}
	else {
		echo "false";
	}
	exit;
?>