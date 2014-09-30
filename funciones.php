<?php
function searchDateRange() {
	<?php
		function daysInMonth($month, $year) { // calculate number of days in a month
			return $month == 2 ? ($year % 4 ? 28 : ($year % 100 ? 29 : ($year % 400 ? 28 : 29))) : (($month - 1) % 7 % 2 ? 30 : 31);
		}
		if (isset($fechaNacimiento)) {
			list($yearDef, $monthDef, $dayDef) = explode('-', $fechaNacimiento);
		}

		$meses = array('Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
		$year = date("Y"); // Año actual
	?>

	<div class="date-fields">
		<!-- Año -->
		<select id="dateYear1" name="dateYear1" class="date date-first-year" onchange="updateDaysField(this);">
			<option value="0" <?php if(!isset($fechaNacimiento)) { echo "selected=\"selected\""; } ?>>Año</option>
			<?php // Imprimimos cien años desde el actual
				for ($i = $year; $i > $year-100; $i--) { ?>
					<option value="<?php echo $i; ?>" <?php if(isset($fechaNacimiento) && $i == $yearDef) { echo "selected=\"selected\""; } ?>><?php echo $i; ?></option>
			<?php } ?>
		</select>
		<!-- Mes -->
		<select id="nacDateMonth" name="nacDateMonth" class="date date-nac-month" onchange="updateDaysField(this);">
			<option value="0" <?php if(!isset($fechaNacimiento)) { echo "selected=\"selected\""; } ?>>Mes</option>
			<?php foreach ($meses as $i => $mes) {
					$i++;
					if ($i == $monthDef) { echo "<option value=\"".$i."\" selected=\"selected\">".$mes."</option>"; }
					else { echo "<option value=\"".$i."\">".$mes."</option>"; }
				} ?>
		</select>
		<!-- Día -->
		<select id="nacDateDay" name="nacDateDay" class="date date-nac-day" onchange="updateDateField('fechaNac');">
			<option value="0" <?php if(!isset($fechaNacimiento)) { echo "selected=\"selected\""; } ?>>Día</option>
			<?php
				for ($i=1; $i <= daysInMonth($monthDef, $yearDef); $i++) {
					if ($i == $dayDef) { echo "<option value=\"".$i."\" selected=\"selected\">".$i."</option>"; }
					else { echo "<option value=\"".$i."\">".$i."</option>"; }
				}
			?>
		</select>
		<input type="text" id="firstDate" name="firstDate" class="hidden" title="Obligatorio. Ej: mymail@gmail.com" value="<?php if(isset($fechaNacimiento)) echo $fechaNacimiento; ?>" onchange="resetError(this)" />
		<p class="fAyuda">Obligatorio</p>
	</div>
}

?>