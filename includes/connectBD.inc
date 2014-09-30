<?php
	// Se conecta al SGBD
	if(!($iden = mysql_connect("localhost", "root", "")))
		die("Error: No se pudo conectar");

	// Selecciona la base de datos
	if(!mysql_select_db("ps_bd", $iden))
		die("Error: No existe la base de datos");

	// Evita problemas de codificación por parte del servidor
	// al realizar consultas
	mysql_set_charset('utf8', $iden);
?>