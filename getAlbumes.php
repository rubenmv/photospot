<?php
    $usuario = $_POST['usuario'];
    if(isset($_POST['row'])) $first = $_POST['row'];
    $maxAlbumes = 6; // Maximo de fotos a devolver
    $limit = $maxAlbumes + 1; // Para comprobar si quedan mas

    require_once("includes/connectBD.inc"); // $iden

    // Recupera todos los álbumes del usuario en sesión
    $sentencia =    "SELECT a.*, u.NomUsuario, p.NomPais FROM usuarios u, albumes a
                            LEFT JOIN paises p ON a.Pais = p.IdPais
                        WHERE a.Usuario = u.IdUsuario AND u.NomUsuario = '$usuario'
                    LIMIT $first, $limit";
    $result = mysqli_query($iden, $sentencia);

    // Se han encontrado albumes
    if($result && mysqli_num_rows($result) != 0) {
        // Si hay resultados, los guardamos en un vector de objetos JSON
        $output = $albumes = array();

        // Si los resultados obtenidos indican que quedan fotos
        if(mysqli_num_rows($result) > $maxAlbumes) { $output['last'] = $first; } // Ultima fila a imprimir
        else { $output['last'] = true; } // No quedan fotos

        for ($i=0; $i < $maxAlbumes && $row = mysqli_fetch_array($result); $i++) {
            // Para cada álbum buscamos una foto para su portada. Será la última foto introducida.
            $sentencia =   "SELECT Fichero
                                FROM fotos
                            WHERE Album = $row[IdAlbum]
                            ORDER BY Fecha DESC
                            LIMIT 1";

            $result2 = mysqli_query($iden, $sentencia);
            // Si hay fotos
            if($result2 && mysqli_num_rows($result2) != 0) {
                $portada = mysqli_fetch_array($result2);
                $portada = "$portada[Fichero]";
                mysqli_free_result($result2);
            }
            // En caso de no haber fotos, ponemos la imagen por defecto
            else { $portada = "files/album-default.png"; }

            $album = array( 'id' => $row['IdAlbum'],
                            'titulo' => $row['Titulo'],
                            'descripcion' => $row['Descripcion'],
                            'portada' => $portada,
                            'fecha' => $row['Fecha'],
                            'pais' => $row['NomPais'],
                            'usuario' => $row['NomUsuario']);
            array_push($albumes, $album);
        }
        array_push($output, $albumes);

        $outJSON = json_encode($output);

        echo $outJSON;
        mysqli_free_result($result);
    }
    // No encuentra nada
    else { echo "false"; }

    if(isset($iden)) { mysqli_close($iden); }
    exit;
?>