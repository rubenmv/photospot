<!-- Contenido -->
<section id="content">
    <h3 id="tituloSeccion">Mis álbumes</h3>
    <div id="content-data"><!-- AQUI SE IMPRIME EL CONTENIDO MEDIANTE AJAX --></div>
    <?php
        if(!isset($usuario)) { $usuario = $_SESSION['sesion']; }
    ?>
    <script type="text/javascript">peticionAJAXAlbumes('getAlbumes.php', '<?php echo $usuario; ?>', 0);</script>
</section>
