<!-- Usuario no logado, formulario de login -->
<form id="form-login" class="form-login" method="post" onsubmit="peticionAJAXLogin('acceso.php?accion=entrar'); return false;">
	<fieldset>
	<legend id="legend-login">Acceso de usuario</legend>
		<!-- USUARIO Y ENLACE REGISTRO -->
		<div class="fGrupo">
			<label class="label1" for="usuario">Usuario</label>
			<input type="text" name="usuario" id="usuario" class="required" onfocus="resetError(this)" tabindex="1" />

			<div class="recordar">
				<a href="index.php?content=5">¿Nuevo usuario?</a>
			</div>
		</div>
		<!-- CLAVE Y RECORDAR -->
		<div class="fGrupo">
			<label class="label1" for="password">Contraseña</label>
			<input type="password" name="password" id="password" class="required" onfocus="resetError(this)" tabindex="2" />

			<div class="recordar">
				<label class="small" for="recordar"><span title="No lo uses en ordenadores públicos">Recordarme</span></label>
				<input type="checkbox" name="recordar" id="recordar" class="checkbox" tabindex="3" />
			</div>
		</div>

		<div class="fGrupo">
			<label class="label1">&nbsp;</label>
			<button class="boton boton-small" type="submit" value="Acceder" tabindex="4">Acceder</button>
		</div>
	</fieldset>
</form>