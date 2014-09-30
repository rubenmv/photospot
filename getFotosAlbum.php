<?php
    $album = $_POST['album'];
    if(isset($_POST['row'])) $first = $_POST['row'];
    $maxFotos = 6; // Maximo de fotos a devolver
    $limit = $maxFotos + 1; // Para comprobar si quedan mas

    require_once("includes/connectBD.inc"); // $iden

    // Recogemos los resultados de la búsqueda
    $sentencia =   "SELECT  f.IdFoto, f.Titulo as TituloFoto, f.Fecha, f.NumVotos, f.PuntuacionTotal,
                            f.Fichero, a.Titulo as TituloAlbum, p.NomPais
                    FROM fotos f
                        LEFT JOIN paises p ON p.IdPais = f.Pais
                        LEFT JOIN albumes a ON a.IdAlbum = f.Album
                    WHERE f.Album = $album
                    ORDER BY f.Fecha DESC
                    LIMIT $first, $limit";

    $result = mysql_query($sentencia, $iden);

    if($result && mysql_num_rows($result) != 0) {
        // Si hay resultados, los guardamos en un vector de objetos JSON
        $output = $fotos = array();

        // Si los resultados obtenidos indican que quedan fotos
        if(mysql_num_rows($result) > $maxFotos) { $output['last'] = $first; } // Ultima fila a imprimir
        else { $output['last'] = true; } // No quedan fotos

        for ($i=0; $i < $maxFotos && $row = mysql_fetch_array($result); $i++) {
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

        $outJSON = json_encode($output);

        echo $outJSON;
        mysql_free_result($result);
    }
    else { echo "false"; }
    if(isset($iden)) { mysql_close($iden); }
    exit;
 ?>