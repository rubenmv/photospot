<?php
	if(isset($_POST['row'])) $first = $_POST['row'];
	$maxFotos = 6; // Maximo de fotos a devolver
	$limit = $maxFotos + 1; // Para comprobar si quedan mas

	$criterios = array( 'titulo'    =>  $_POST['titulo'],
						'fechaIni'  =>  $_POST['fechaIni'],
						'fechaFin'  =>  $_POST['fechaFin'],
						'pais'      =>  $_POST['pais']);

	// Parte de la query referida a los criterios
	$condiciones = " WHERE ";

	// TITULO
	if(trim($criterios['titulo']) != "") {
		$condiciones .= "f.Titulo LIKE '%$criterios[titulo]%' ";
	}
	// FECHAS
	if(trim($criterios['fechaIni']) != "" || trim($criterios['fechaFin']) != "") {
		// Si existia titulo, agregamos AND
		if($condiciones != " WHERE ") { $condiciones .= " AND "; }

		// Cota inferior
		if($criterios['fechaIni'] != "") {
			$fecha_ini = $criterios['fechaIni'];
		}
		else {
			$fecha_ini = '0000-00-00'; // Primera fecha posible
		}
		$condiciones .= "f.Fecha >= '$fecha_ini'";
		// Cota superior
		if($criterios['fechaFin'] != "") {
			$fecha_fin = $criterios['fechaFin'];
		}
		else {
			$today = getdate(); // Hoy
			$fecha_fin = $today['year']."-".$today['mon']."-".$today['mday'];
		}
		$condiciones .= " AND f.Fecha <= '$fecha_fin'";
	}

	// PAIS
	if($criterios['pais'] > 0) {
		// Si existia titulo o fecha, agregamos AND
		if($condiciones != " WHERE ") $condiciones .= " AND ";
		$condiciones .= "f.Pais = $criterios[pais] ";
	}

	require_once("includes/connectBD.inc"); // $iden

	// Recogemos los resultados de la búsqueda
	$sentencia =   "SELECT  f.IdFoto, f.Titulo as TituloFoto, f.Fecha, f.NumVotos, f.PuntuacionTotal,
							f.Fichero, a.Titulo as TituloAlbum, p.NomPais
					FROM ".$tablePrefix."fotos f
						LEFT JOIN ".$tablePrefix."paises p ON p.IdPais = f.Pais
						LEFT JOIN ".$tablePrefix."albumes a ON a.IdAlbum = f.Album
					$condiciones
					ORDER BY f.Titulo ASC
					LIMIT $first, $limit";

	$result = mysqli_query($iden, $sentencia);

	if($result && mysqli_num_rows($result) != 0) {
		// Si hay resultados, los guardamos en un vector de objetos JSON
		$output = $fotos = array();

		// Si los resultados obtenidos indican que quedan fotos
		if(mysqli_num_rows($result) >= $limit) {
			$output['last'] = $first; // Ultima fila a imprimir
		}
		else {
			$output['last'] = true; // No quedan fotos
		}
		for ($i=0; $i < $maxFotos && $row = mysqli_fetch_array($result); $i++) {
			$foto = array(  'id' => $row['IdFoto'],
							'titulo' => $row['TituloFoto'],
							'fecha' => $row['Fecha'],
							'pais' => $row['NomPais'],
							'album' => $row['TituloAlbum'],
							'numVotos' => $row['NumVotos'],
							'puntuacionTotal' => $row['PuntuacionTotal'],
							'fichero' => $row['Fichero']);
			array_push($fotos, $foto);
		}
		array_push($output, $fotos);

		$fotosJSON = json_encode($output);
		echo $fotosJSON;

		mysqli_free_result($result);
	}
	else { echo "false"; }
	if(isset($iden)) { mysqli_close($iden); }
	exit;
?>