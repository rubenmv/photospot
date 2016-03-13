<?php 
	$usuario = $_POST['usuario'];
	$texto = $_POST['texto'];
	$idFoto = $_POST['idFoto'];

	require_once("includes/connectBD.inc"); // $iden

	// Recogemos la id del usuario que envia el comentario
	$sentencia = "SELECT IdUsuario FROM ".$tablePrefix."usuarios WHERE NomUsuario = '$usuario'";
	$result = mysqli_query($iden, $sentencia);

	if($result) {
		$row = mysqli_fetch_array($result);
		$idUsuario = $row['IdUsuario'];
		mysqli_free_result($result);

		$sentencia = "INSERT INTO `".$tablePrefix."comentarios` (`IdFoto`, `IdUsuario`, `Texto`) VALUES
								  ('$idFoto', '$idUsuario', '$texto')";

		$result = mysqli_query($iden, $sentencia);

		if(isset($iden)) {
			mysqli_close($iden);
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