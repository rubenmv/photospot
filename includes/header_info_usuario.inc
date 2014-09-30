<!-- Usuario logado, muestra información -->
<p>
	<div class="inline-block">
		<a id="zona-info-usuario" href="index.php?content=8" title="Panel de control"><?php echo $usuario;?></a><br/>
		<a id="zona-info-cerrar" href="acceso.php?accion=salir">Cerrar sesión</a>
	</div>
	<div class="inline-block">
		<a id="zona-info-usuario" href="index.php?content=8" title="Panel de control"><img id="zona-info-foto" src="timthumb.php?src=<?php echo $userFoto;?>&amp;w=32&amp;h=32" alt="Panel de control" /></a>
	</div>
</p>
