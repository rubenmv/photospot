<?php 
	require_once("includes/connectBD.inc"); // $iden

	$idFoto = $_POST['idFoto'];
	$puntos = $_POST['puntos'];
	
	// Recogemos los votos y puntuacion actual de la foto
	$sentencia =   "SELECT NumVotos, PuntuacionTotal FROM fotos
						WHERE IdFoto = $idFoto";

	$result = mysqli_query($iden, $sentencia);

	if($result) {
		$row = mysqli_fetch_array($result);

		$numVotos = $row['NumVotos'] + 1; // Aumenta un voto
		$puntuacionTotal = $row['PuntuacionTotal'] + $puntos;

		mysqli_free_result($result);

		// Actualizamos los campos de la foto
		$sentencia = "UPDATE fotos SET NumVotos = $numVotos, PuntuacionTotal = $puntuacionTotal
						WHERE IdFoto = $idFoto";

		$result = mysqli_query($iden, $sentencia);

		if($result) {
			// Devolvemos la nueva media
			$media = $puntuacionTotal / $numVotos;
			echo $media;
		}
		else {
			echo "false";
		}
	}
	else {
		echo "false";
	}

	if(isset($iden)) {
		mysqli_close($iden);
	}

	exit;
 ?>