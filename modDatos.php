<div id="content">
    <h3>Modificar mis datos</h3>

    <!-- Formulario de registro -->
    <!--<form id="fRegistro" method="post" action="index.php?content=12" onsubmit="return validarCampos(this)">-->
    <form id="fRegistro" method="post" action="valida.php?id=<?php echo $_GET['content'];?>" enctype="multipart/form-data" onsubmit="return validarCampos(this);">
        <fieldset>
        <legend id="lRegistro">Modificación de datos de usuario</legend>

            <?php if(isset($_GET['msg']))
                switch ($_GET['msg']) {
                    // Falta el titulo
                    case 1: echo "<p class=\"fError\">Ya existe un usuario con ese nombre.</p>"; break;
                    // Datos incorrectos
                    case 2: echo "<p class=\"fError\">Datos incorrectos. Revise el formulario.</p>"; break;
                    // Error en la query
                    case 3: echo "<p class=\"fError\">Hubo un problema. Inténtelo más tarde.</p>"; break;
                }

                // Recogemos los datos del usuario desde la BD
                $userLogado = $_SESSION['sesion'];
                require_once("includes/connectBD.inc"); // $iden
                $sentencia = "SELECT * FROM ".$tablePrefix."usuarios WHERE NomUsuario = '$userLogado'";

                $result = mysqli_query($iden, $sentencia);

                if($result) {
                    $datos = mysqli_fetch_array($result);
                    mysqli_free_result($result); // Ya no hace falta
            ?>

                    <div class="fGrupo">
                        <label class="label1" for="nombre">Nombre de usuario</label>
                        <div class="controls">
                            <input type="text" name="nombre" id="nombre" class="required" maxlength="15" value="<?php echo $datos['NomUsuario']; ?>" title="Campo obligatorio" onfocus="resetError(this)" />
                            <p class="fAyuda">
                                Obligatorio. Entre 3 y 15 caracteres. Solo letras o números.<br />
                                Debe ser único.
                            </p>
                        </div>
                    </div>

                    <div class="fGrupo">
                        <label class="label1" for="pass">Nueva contraseña</label>
                        <div class="controls">
                            <!-- La clave tiene un value por defecto para evitar el autocompletado de los navegadores -->
                            <input type="password" name="pass" id="pass" maxlength="15" value="default" title="Campo obligatorio" onfocus="resetError(this)" />
                            <p class="fAyuda">
                                Puedes dejarlo como está si no quieres cambiarla.<br />
                                Entre 6 y 15 caracteres. Letras, números y "_".<br />
                                Debe incluir al menos una mayúscula, una minúscula y un número.
                            </p>
                        </div>
                    </div>

                    <div class="fGrupo">
                        <label class="label1" for="cpass">Confirma contraseña</label>
                        <div class="controls">
                            <input type="password" name="cpass" id="cpass" maxlength="15" value="default" title="Campo obligatorio" onfocus="resetError(this)" />
                            <p class="fAyuda">Vuelve a introducir la nueva contraseña</p>
                        </div>
                    </div>

                    <div class="fGrupo">
                        <label class="label1" for="email">Email</label>
                        <div class="controls">
                            <input type="text" name="email" id="email" class="required" maxlength="256" value="<?php echo $datos['Email']; ?>" title="Campo obligatorio" onfocus="resetError(this)" />
                            <p class="fAyuda">Obligatorio. Ej: rub3nmv@gmail.com</p>
                        </div>
                    </div>

                    <!-- Fecha de nacimiento -->
                    <?php $fechaNacimiento = $datos['FNacimiento'];
                    require_once("includes/form-fecha-nac.inc"); ?>

                    <!-- Sexo -->
                    <div class="fGrupo">
                        <label class="label1">Sexo</label>
                        <div class="controls">
                            <label for="Hombre">Hombre</label>
                            <input type="radio" class="radio" name="sexo" id="Hombre" value="0" <?php if($datos['Sexo'] == 0) echo "checked=\"checked\""; ?> />
                            <label for="Mujer">Mujer</label>
                            <input type="radio" class="radio" name="sexo" id="Mujer" value="1" <?php if($datos['Sexo'] == 1) echo "checked=\"checked\""; ?> />
                            <a href="javascript:;" class="reset-link" onclick="resetRadioGroup('sexo');">Borrar</a>
                        </div>
                    </div>

                    <?php // Paises desde la BD
                    $sentencia = "SELECT IdPais, NomPais FROM ".$tablePrefix."paises";
                    $result = mysqli_query($iden, $sentencia);

                    // Solo se muestra el selector si se ha encontrado algún país
                    if($result) {
                    ?>
                    <div class="fGrupo">
                        <label class="label1">País</label>
                        <div class="controls">
                            <select id="pais" name="pais">
                                <option value="0" <?php if ($datos['Pais'] == NULL) { echo "selected=\"selected\""; } ?>>Escoge un país</option>
                                <?php // El resto de países a través de la BD
                                    while($row = mysqli_fetch_array($result)) { ?>
                                        <option value="<?php echo $row['IdPais']; ?>" <?php if($datos['Pais'] == $row['IdPais']) echo "selected=\"selected\"";?>><?php echo $row['NomPais']; ?></option>
                                <?php }
                        // Libera memoria del resultado y cerrar
                        mysqli_free_result($result);
                        mysqli_close($iden);
                     } ?>
                            </select>
                        </div>
                    </div>

                    <div class="fGrupo">
                        <label class="label1" for="ciudad">Ciudad</label>
                        <div class="controls">
                            <input type="text" name="ciudad" id="ciudad" value="<?php if ($datos['Ciudad'] != NULL) echo $datos['Ciudad']; ?>" />
                        </div>
                    </div>

                    <div class="fGrupo">
                        <label class="label1" for="foto">Foto</label>
                        <div class="controls">
                            <?php if($datos['Foto'] == NULL) { ?>
                                <p class="fAyuda">No tienes foto, puedes escoger una desde el disco duro.</p>
                            <?php } else { ?>
                                <img class="avatarGrande" src="<?php echo $datos['Foto']; ?>" alt="Foto de perfil" />
                                <br /><input type="checkbox" class="checkbox" id="borraFoto" name="borraFoto" />
                                <label for="borraFoto">Eliminar la foto</label>
                                <p class="fAyuda">Puedes cambiar la foto escogiendo otra desde tu disco duro.</p>
                            <?php } ?>
                            <button type="button" id="buttonFile" class="boton" value="Seleccionar" onclick="triggerFilePicker('foto');">Seleccionar</button>
                            <input type="file" name="foto" id="foto" class="hidden" onclick="resetError(this);" onchange="printSelectedFile(this.value);" />
                            <input type="text" class="hidden" value="<?php echo $datos['Foto']; ?>" name="fotoAux" id="fotoAux" />
                        </div>
                    </div>

                    <div class="fGrupo">
                        <label class="label1">&nbsp;</label> <!-- Para alinear el boton -->
                        <div class="controls">
                            <button name="guardar" id="guardar" class="boton" type="submit" value="Guardar">Guardar cambios</button>
                            <a href="index.php?content=8" class="boton botonCancel">Cancelar</a>
                        </div>
                    </div>
            <?php
            } else {
                    die("No se ha encontrado el usuario.");
                }
             ?>
        </fieldset>
    </form>

</section>
