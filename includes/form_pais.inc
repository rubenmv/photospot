<?php
// Aquí se recueperan los países de la base de datos y
// se muestra un selector solo si existe alguno.

// Se guarda en $iden
require_once("connectBD.inc");

$sentencia = "SELECT IdPais, NomPais FROM paises";
$result = mysql_query($sentencia, $iden);

// Solo se muestra el selector si se ha encontrado algún país
if($result) {
?>
<div class="fGrupo">
    <label class="label1" for="pais">País</label>
    <div class="controls">
        <select id="pais" name="pais">
            <option value="0" selected="selected">[Escoge un país]</option>
            <?php while($row = mysql_fetch_array($result)) { ?>
                    <option value="<?php echo $row['IdPais']; ?>"><?php echo $row['NomPais']; ?></option>
             <?php }
    mysql_free_result($result);
} ?>
        </select>
    </div>
</div>