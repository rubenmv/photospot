<?php
require_once("../includes/connectBD.inc"); // $iden

// Las 10 mejores fotos segun su puntuacion media
$sentencia = 	"SELECT  f.IdFoto, f.Titulo as TituloFoto, f.Fecha, f.NumVotos, f.PuntuacionTotal,
							f.Fichero, a.Titulo as TituloAlbum, u.NomUsuario, p.NomPais
					FROM ".$tablePrefix."fotos f
						LEFT JOIN ".$tablePrefix."paises p ON p.IdPais = f.Pais
						LEFT JOIN ".$tablePrefix."albumes a ON a.IdAlbum = f.Album
						LEFT JOIN ".$tablePrefix."usuarios u ON a.Usuario = u.IdUsuario
					WHERE NumVotos != 0
					ORDER BY (PuntuacionTotal DIV NumVotos) DESC
					LIMIT 10";
$result = mysqli_query($iden, $sentencia);

if($result && mysqli_num_rows($result) != 0) {
	// Si hay resultados, los guardamos en un vector de objetos JSON
	$fotos = array();
	for ($i=0; $row = mysqli_fetch_array($result); $i++) {
		$foto = array(  'id' => $row['IdFoto'],
						'titulo' => $row['TituloFoto'],
						'fecha' => $row['Fecha'],
						'pais' => $row['NomPais'],
						'usuario' => $row['NomUsuario'],
						'album' => $row['TituloAlbum'],
						'numVotos' => $row['NumVotos'],
						'puntuacionTotal' => $row['PuntuacionTotal'],
						'fichero' => $row['Fichero']);

		array_push($fotos, $foto);
	}

	$fotosJSON = json_encode($fotos);
	echo $fotosJSON;

	mysqli_free_result($result);
}
else {
	echo "false";
}

if(isset($iden)) {
	mysqli_close($iden);
}
exit;
?>