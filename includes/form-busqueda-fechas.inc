<?php
	function daysInMonth($month, $year) { // calculate number of days in a month
		return $month == 2 ? ($year % 4 ? 28 : ($year % 100 ? 29 : ($year % 400 ? 28 : 29))) : (($month - 1) % 7 % 2 ? 30 : 31);
	}

	$meses = array('Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
	$year = date("Y"); // Año actual
?>
		<!-- Fecha de inicio -->
		<label class="label2">Desde</label>
		<div class="date-fields">
			<!-- Año -->
			<select id="dateYear1" name="dateYear1" class="date date-selector" onchange="updateDaysField(this); updateDateField('fechaIni');">
				<option value="0">Año</option>
				<?php // Imprimimos cien años desde el actual
					for ($i = $year; $i > $year-100; $i--) { ?>
						<option value="<?php echo $i; ?>"><?php echo $i; ?></option>
				<?php } ?>
			</select>
			<!-- Mes -->
			<select id="dateMonth1" name="dateMonth1" class="date date-selector" onchange="updateDaysField(this); updateDateField('fechaIni');">
				<option value="0">Mes</option>
				<?php foreach ($meses as $i => $mes) {
						$i++;
						echo "<option value=\"".$i."\">".$mes."</option>";
					} ?>
			</select>
			<!-- Día -->
			<select id="dateDay1" name="dateDay1" class="date date-selector" onchange="updateDateField('fechaIni');">
				<option value="0">Día</option>
			</select>
			<input type="text" id="fechaIni" name="fechaIni" class="hidden"  value="" onchange="resetError(this)" />
		</div>
		<!-- Fecha de fin -->
		<br /><br /><label class="label2">Hasta</label>
		<div class="date-fields">
			<!-- Año -->
			<select id="dateYear2" name="dateYear2" class="date date-selector" onchange="updateDaysField(this); updateDateField('fechaFin');">
				<option value="0">Año</option>
				<?php // Imprimimos cien años desde el actual
					for ($i = $year; $i > $year-100; $i--) { ?>
						<option value="<?php echo $i; ?>"><?php echo $i; ?></option>
				<?php } ?>
			</select>
			<!-- Mes -->
			<select id="dateMonth2" name="dateMonth2" class="date date-selector" onchange="updateDaysField(this); updateDateField('fechaFin');">
				<option value="0">Mes</option>
				<?php foreach ($meses as $i => $mes) {
						$i++;
						echo "<option value=\"".$i."\">".$mes."</option>";
					} ?>
			</select>
			<!-- Día -->
			<select id="dateDay2" name="dateDay2" class="date date-selector" onchange="updateDateField('fechaFin');">
				<option value="0">Día</option>
			</select>
			<input type="text" id="fechaFin" name="fechaFin" class="hidden" value="" onchange="resetError(this)" />
		</div>