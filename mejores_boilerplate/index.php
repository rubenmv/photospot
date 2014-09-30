<?php session_start(); ?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title></title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width">

        <link rel="stylesheet" href="css/bootstrap.min.css">
        <style>
            body {
                padding-top: 60px;
                padding-bottom: 40px;
            }
        </style>
        <link rel="stylesheet" href="css/bootstrap-responsive.min.css">
        <link rel="stylesheet" href="css/main.css">

        <script src="js/vendor/modernizr-2.6.2-respond-1.1.0.min.js"></script>
    </head>
    <body>
        <!--[if lt IE 7]>
            <p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
        <![endif]-->

        <!-- This code is taken from http://twitter.github.com/bootstrap/examples/hero.html -->

        <?php // Recogemos la direccion del host para pasarlo a timthumb para todas las imagenes
            $host = $_SERVER['HTTP_HOST'].rtrim(dirname($_SERVER['PHP_SELF']), '/\\')."/..";
        ?>

        <div class="navbar navbar-inverse navbar-fixed-top">
            <div class="navbar-inner">
                <div class="container">
                    <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </a>
                    <a class="brand" href="../index.php?content=1">Photo Spot</a>
                    <div class="nav-collapse collapse">
                        <ul id="mainNavItemList" class="nav">
                            <li><a href="../index.php?content=1">Portada</a></li>
                            <li><a href="../index.php?content=2">Buscar</a></li>
                            <li><a href="../index.php?content=4">Información</a></li>
                        </ul>
                        <div id="zona-usuario">
                        <?php
                            // Usuario logado, muestra información
                            if(isset($_SESSION['sesion'])) {
                                // Recogemos la foto desde la base de datos
                                require_once("../includes/connectBD.inc"); // $iden

                                $usuario = $_SESSION['sesion'];
                                $sentencia = "SELECT usuarios.Foto FROM usuarios
                                                WHERE NomUsuario = '$usuario'";

                                $result = mysqli_query($iden, $sentencia);
                                $row = mysqli_fetch_array($result);

                                if($row['Foto'] !== NULL) {
                                    $userFoto = $row['Foto'];
                                }
                                else {
                                    $userFoto = "../files/profile/default-avatar.png";
                                }

                                $userFoto = "http://".$host."/".$userFoto;
    
                                ?>
                                <!-- Usuario logado, muestra información -->
                                <p><a href="../index.php?content=8" title="Panel de control">
                                        <span id="zona-info-usuario"><?php echo $usuario;?></span>
                                        <img id="zona-info-foto" src="../timthumb.php?src=<?php echo $userFoto;?>&amp;w=32&amp;h=32" alt="Panel de control" />
                                        <a id="zona-info-cerrar" href="../acceso.php?accion=salir">Cerrar sesión</a>
                                </a></p>
                            <?php 
                            }
                            // Usuario no logado, formulario de login
                            else { ?>
                                <form id="form-login" class="navbar-form pull-right" method="post" onsubmit="peticionAJAXLogin('../acceso.php?accion=entrar'); return false;">
                                    <input class="span2" type="text" placeholder="Usuario" name="usuario" id="usuario" class="required" required="required">
                                    <input class="span2" type="password" placeholder="Contraseña" name="password" id="password" class="required" required="required">
                                    <button id="submit" type="submit" data-loading-text="Comprobando..." class="btn btn-primary btn-small submit">Acceder</button>
                                </form>
                            <?php } ?>                            
                        </div>

                    </div><!--/.nav-collapse -->
                </div>
            </div>
        </div>

        <div class="container">

            <!-- Main hero unit for a primary marketing message or call to action -->
            <div class="well">
                <h1>Las mejores fotos</h1>
                <p><br />Esta es una recopilación de las 10 mejores fotos de <strong>Photo Spot</strong>, según nuestros usuarios. ¡Échales un vistazo!.</p>
            </div>

            <!-- Example row of columns -->
            <div id="mejores-fotos" class="row">
            </div>
            <hr>

            <footer>
                <p>&copy; Rubén Martínez Vilar, 2012.<br />Programación Hipermedia 2. Ingeniería Multimedia.</p>
            </footer>

        </div> <!-- /container -->

        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="js/vendor/jquery-1.9.1.min.js"><\/script>')</script>

        <script src="js/vendor/bootstrap.min.js"></script>

        <script src="js/main.js"></script>
        <script type="text/javascript">getMejoresFotos('getMejoresFotos.php');</script>
        <script>
            var _gaq=[['_setAccount','UA-XXXXX-X'],['_trackPageview']];
            (function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];
            g.src=('https:'==location.protocol?'//ssl':'//www')+'.google-analytics.com/ga.js';
            s.parentNode.insertBefore(g,s)}(document,'script'));
        </script>
    </body>
</html>
