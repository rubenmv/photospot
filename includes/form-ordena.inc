<form id="form-ordena" class="hidden" method="post" onsubmit="sortFotos(); return false;">
	<fieldset>
		<legend>Ordenar resultados:</legend>
		<label for="criterio-ordenacion">Ordenar por </label>
		<select name="criterio-ordenacion" id="criterio-ordenacion">
			<option value="titulo">Título</option>
			<option value="fecha">Fecha</option>
			<option value="pais">País</option>
		</select>
		<label for="orden">Descendente</label>
		<input type="checkbox" id="orden" name="orden" />
		<button name="ordenar" id="ordenar" class="boton" type="submit" value="Ordenar" >Ordenar</button>
	</fieldset>
</form>

<script type="text/javascript" src="js/sort.js"></script>