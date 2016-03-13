<?php
	// Se conecta al SGBD
	if(!($iden = mysqli_connect("localhost", "root", ""))) {
		die("Error: No se pudo conectar.");
	}

	
	// Selecciona la base de datos
	if(!mysqli_select_db($iden, "ps_db")) {
		die("Error: No existe la base de datos");
	}
	
	// Evita problemas de codificación por parte del servidor
	// al realizar consultas
	mysqli_set_charset($iden, "utf8");

	// Prefijo para las tablas en el caso en que se comparta base de datos
	// Hay que tener en cuenta que si se importan los datos de demostracion (ps_bd.sql)
	// entonces hay que sustituir en ese fichero para poner el nuevo prefijo
	$tablePrefix = "photospot_";
?>