<?php
	require_once("includes/connectBD.inc"); // $iden

	// Si se hace la peticion para una foto en concreto
	if (isset($_POST['idFoto'])) {
		$idFoto = $_POST['idFoto'];
		// Maximo de comentarios por pagina
		$max_comentarios_pagina = 5;


		// COMENTARIO COMO REFERENCIA
		if (isset($_POST['ref'])) {
			// Maximo de comentarios posteriores y anteriores al de referencia
			$max = floor($max_comentarios_pagina / 2);

			/* Si se recibe una referencia para resaltar un comentario
			 * 1. Recogemos los comentarios posteriores al que sirve de referencia
			 * 2. Unimos el comentario de referencia que vamos a resaltar
			 * 3. Unimos los comentarios anteriores a este para que aparezcan despues	*/
			$sentencia= "	(
							SELECT c. * , u.NomUsuario
							FROM comentarios c
							LEFT JOIN usuarios u ON c.IdUsuario = u.IdUsuario
							WHERE c.IdFoto = $idFoto
							AND c.IdComentario > $_POST[ref]
							ORDER BY c.IdComentario
							LIMIT $max
							)
							UNION ALL (
							SELECT c. * , u.NomUsuario
							FROM comentarios c
							LEFT JOIN usuarios u ON c.IdUsuario = u.IdUsuario
							WHERE c.IdFoto = $idFoto
							AND c.IdComentario = $_POST[ref]
							)
							UNION ALL (
							SELECT c. * , u.NomUsuario
							FROM comentarios c
							LEFT JOIN usuarios u ON c.IdUsuario = u.IdUsuario
							WHERE c.IdFoto = $idFoto
							AND c.IdComentario < $_POST[ref]
							ORDER BY c.IdComentario DESC
							LIMIT $max_comentarios_pagina
							)
							ORDER BY IdComentario DESC";
		}
		// PAGINA COMO REFERENCIA
		else {
			$pagina = $_POST['pagina'];
			// Calculo del primer comentario a mostrar segun la pagina
			$first = $pagina * $max_comentarios_pagina - ($max_comentarios_pagina);
			// Buscamos un comentario mas del maximo establecido, asi sabemos seguro si es el final
			$max = $max_comentarios_pagina + 1;

			$sentencia= "SELECT c.*, NomUsuario FROM comentarios c
						LEFT JOIN usuarios u ON c.IdUsuario = u.IdUsuario
					WHERE IdFoto = $idFoto
					ORDER BY Fecha DESC
					LIMIT $first, $max";
		}
	}
	// DESDE PORTADA, 10 MAS RECIENTES PARA <ASIDE>
	else {
		$max_comentarios_pagina = 20;

		$sentencia= "SELECT c.*, NomUsuario FROM comentarios c
						LEFT JOIN usuarios u ON c.IdUsuario = u.IdUsuario
					ORDER BY Fecha DESC
					LIMIT $max_comentarios_pagina";
	}

	$result = mysqli_query($iden, $sentencia);

	if($result && mysqli_num_rows($result) > 0) {
		// Si hay resultados, los guardamos en un vector de objetos JSON
		$comentarios = array();
		for ($i=0; $i < $max_comentarios_pagina && $row = mysqli_fetch_array($result); $i++) {
			$comentario = array(  'id' => $row['IdComentario'],
							'foto' => $row['IdFoto'],
							'usuario' => $row['NomUsuario'],
							'texto' => $row['Texto'],
							'fecha' => $row['Fecha']);

			array_push($comentarios, $comentario);
		}
		$respuesta = array();

		// Si los resultados obtenidos indican que hay al menos un comentario mas
		$respuesta['last'] = true;
		if(mysqli_num_rows($result) > $max_comentarios_pagina) {
			$respuesta['last'] = false;
		}

		$respuesta['comentarios'] = $comentarios;

		echo json_encode($respuesta);
		mysqli_free_result($result);
	}
	// No encuentra nada
	else { echo "false"; }

	if(isset($iden)) { mysqli_close($iden); }
	exit;
?>