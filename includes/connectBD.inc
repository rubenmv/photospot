<?php
	// Se conecta al SGBD
	if(!($iden = mysqli_connect("localhost", "root", ""))) {
		die("Error: No se pudo conectar.");
	}

	
	// Selecciona la base de datos
	if(!mysqli_select_db($iden, "ps_bd")) {
		die("Error: No existe la base de datos");
	}
	
	// Evita problemas de codificación por parte del servidor
	// al realizar consultas
	mysqli_set_charset($iden, "utf8");
?>