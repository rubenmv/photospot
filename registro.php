<!-- Contenido -->
<section id="content">
    <h3>Registro de nuevo usuario</h3>

    <!-- Formulario de registro -->
    <form id="fRegistro" class="form-page" method="post" action="valida.php?id=<?php echo $_GET['content'];?>" enctype="multipart/form-data" onsubmit="return validarCampos(this);">
        <fieldset>
            <legend id="lRegistro">Formulario de registro de nuevo usuario</legend>

            <?php if(isset($_GET['msg']))
                switch ($_GET['msg']) {
                    // Falta el titulo
                    case 1:
                        echo "<p class=\"fError\">Ya existe un usuario con ese nombre.</p>";
                        break;
                    // Datos incorrectos
                    case 2:
                        echo "<p class=\"fError\">Datos incorrectos. Revise el formulario.</p>";
                        break;
                    // Error en la query
                    case 3:
                        echo "<p class=\"fError\">Hubo un problema. Inténtelo más tarde.</p>";
                        break;
                }
            ?>
            <!-- Nombre de usuario -->
            <div class="fGrupo">
                <label class="label1" for="nombre">Nombre de usuario</label>
                <div class="controls">
                    <input type="text" name="nombre" id="nombre" class="required" title="Campo obligatorio. Entre 3 y 15 caracteres. Solo letras y números." maxlength="15" placeholder="Nombre de usuario"  onfocus="resetError(this)" />
                    <p class="fAyuda">
                        Obligatorio. Entre 3 y 15 caracteres. Solo letras o números.<br />
                    </p>
                </div>
            </div>
            <!-- Contraseña -->
            <div class="fGrupo">
                <label class="label1" for="pass">Contraseña</label>
                <div class="controls">
                    <input type="password" name="pass" id="pass" class="required" title="Obligatorio. Entre 6 y 15 caracteres. Letras, números y _." maxlength="15" placeholder="Contraseña" onfocus="resetError(this)" />
                    <p class="fAyuda">
                        Obligatorio. Entre 6 y 15 caracteres. Letras, números y "_".<br />
                        Debe incluir al menos una mayúscula, una minúscula y un número.
                    </p>
                </div>
            </div>
            <!-- Confirmar contraseña -->
            <div class="fGrupo">
                <label class="label1" for="cpass">Confirmar contraseña</label>
                <div class="controls">
                    <input type="password" name="cpass" id="cpass" class="required" title="Campo obligatorio. Repita la contraseña." maxlength="15" placeholder="Repita la contraseña"  onfocus="resetError(this)" />
                    <p class="fAyuda">Obligatorio. Vuelve a introducir la contraseña</p>
                </div>
            </div>

            <!-- Email -->
            <div class="fGrupo">
                <label class="label1" for="email">Email</label>
                <div class="controls">
                    <input type="text" name="email" id="email" class="required" title="Obligatorio. Ej: mymail@gmail.com" placeholder="Correo electrónico" onfocus="resetError(this)" />
                    <p class="fAyuda">Obligatorio. Ej: mymail@gmail.com</p>
                </div>
            </div>

            <!-- Fecha de nacimiento -->
            <?php require_once("includes/form-fecha-nac.inc"); ?>

            <!-- Sexo -->
            <div class="fGrupo">
                <label class="label1">Sexo</label>
                <div class="controls">
                    <label class="label2" for="Hombre">Hombre</label>
                    <input type="radio" class="radio" name="sexo" id="Hombre" value="0" />
                    <label class="label2" for="Mujer">Mujer</label>
                    <input type="radio" class="radio" name="sexo" id="Mujer" value="1" />
                    <a href="javascript:;" class="reset-link" onclick="resetRadioGroup('sexo');">Borrar</a>
                </div>
            </div>
            <!-- Pais -->
            <?php require_once("includes/form_pais.inc"); ?>
            <!-- Ciudad -->
            <div class="fGrupo">
                <label class="label1" for="ciudad">Ciudad</label>
                <div class="controls">
                    <input type="text" name="ciudad" id="ciudad" placeholder="Ciudad de residencia" />
                </div>
            </div>

            <!-- Fichero -->
            <?php require_once("includes/form_file.inc"); ?>

            <div class="fGrupo">
                <label class="label1">&nbsp;</label> <!-- Para alinear el boton -->
                <div class="controls">
                    <button name="enviar" id="enviar" class="boton" type="submit" value="Enviar">Enviar</button>
                </div>
            </div>

        </fieldset>
    </form>
</section>

<?php mysql_close($iden); ?>
