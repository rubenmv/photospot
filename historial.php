<!-- Contenido -->
<section id="content">
	<h3>Últimas fotos visitadas</h3>

	<aside id="controles-historial" class="nav-aside">
		<ul>
			<li id="enlace-historial"><a href="#" onclick="getHistorial('completo'); return false;">Ver historial completo</a></li>
			<li><a href="#" onclick="clearHistorial(); return false;">Borrar historial</a></li>
		</ul>
	</aside>

	<section id="seccion-historial" class="historial">
		<p class="big fError">El historial está vacío.</p>
	</section>

	<script type="text/javascript">getHistorial('sesion');</script>

</section>
