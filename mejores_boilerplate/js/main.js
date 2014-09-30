var fotos = []; // Lista de objetos Foto
var RUTABASE = "http://localhost:8080/ph2p3/"; // Para timthumb

$(document).ready(function(){
  $('.submit').button();

  $('.submit').click(function() {
    $(this).button('loading');
  });  
});

// CLASE FOTO
function Foto(id, titulo, fecha, pais, album, usuario, numVotos, puntuacionTotal, fichero, fechaRegistro) {
	"use strict";

	/* PROPIEDADES */
	this.id = id;
	this.titulo = titulo;
	this.fecha = fecha;
	this.pais = pais;
	this.album = album;
	this.usuario = usuario;
	this.numVotos = numVotos;
	this.puntuacionTotal = puntuacionTotal;
	this.fichero = fichero;
	this.fechaRegistro = fechaRegistro; // Fecha en que se subio la foto

	/* METODOS */

	// Acceso a propiedades
	this.getId = function() { return this.id; };
	this.getTitulo = function() { return this.titulo; };
	this.getFecha = function() { return this.fecha; };
	this.getPais = function() { return this.pais; };
	this.getAlbum = function() { return this.album; };
	this.getUsuario = function() { return this.usuario; };
	this.getVotos = function() { return this.numVotos; };
	this.getPuntuacionTotal = function() { return this.puntuacionTotal; };
	this.getFichero = function() { return this.fichero; };
	this.getFechaRegistro = function() { return this.fechaRegistro; };

	// Devuelve en formato texto el tiempo transcurrido
	// desde que se tomo la foto ("Hace 7 meses...")
	this.getFechaEnTexto = function() {

		var seg = Math.floor((new Date() - this.getFechaRegistro()) / 1000);
	    var tiempo = Math.floor(seg / 31536000);

	    if (tiempo > 1) {
	        return tiempo + " años";
	    }
	    tiempo = Math.floor(seg / 2592000);
	    if (tiempo > 1) {
	        return tiempo + " meses";
	    }
	    tiempo = Math.floor(seg / 86400);
	    if (tiempo > 1) {
	        return tiempo + " días";
	    }
	    tiempo = Math.floor(seg / 3600);
	    if (tiempo > 1) {
	        return tiempo + " horas";
	    }
	    tiempo = Math.floor(seg / 60);
	    if (tiempo > 1) {
	        return tiempo + " minutos";
	    }
	    return Math.floor(seg) + " segundos";
	};

	// Calcula la puntuacion media de la foto utilizando los campos numVotos y puntuacionTotal.
	this.getPuntuacionMedia = function() {
		if(this.getVotos() > 0) {
			return ((this.getPuntuacionTotal() / this.getVotos()).toFixed(2));
		}
		else {
			return 0;
		}
	};
}

// Peticion de 10 mejores fotos mediante JQuery
function getMejoresFotos(url) {
	"use strict";

	$.ajax({
	    type:"POST",
		url: url,
		async: true, // Asincrona
		dataType: "json", // Vamos a recibir un JSON
		success: function(datos){ // Función a ejecutar si todo OK.
			if(datos !== false) {
				fotos = [];
				// Crear objetos fotos con los datos obtenidos
				for (var i = 0; i < datos.length; i++) {
					// Convertimos las fechas a Date
					if(datos[i].fecha !== null) {
						// Formato ISO 8601 "2011-10-10"
						datos[i].fecha = new Date(datos[i].fecha);
					}

					if(datos[i].fechaRegistro !== null) {
						// ISO 8601 para el Date.parse(). Formato "2011-10-10T14:48:00"
						datos[i].fechaRegistro = datos[i].fechaRegistro.replace(' ', 'T');
						datos[i].fechaRegistro = new Date(datos[i].fechaRegistro);
					}
					// Introducimos la nueva foto
					fotos.push(new Foto(parseInt(datos[i].id),
										datos[i].titulo,
										datos[i].fecha,
										datos[i].pais,
										datos[i].album,
										datos[i].usuario,
										parseInt(datos[i].numVotos),
										parseInt(datos[i].puntuacionTotal),
										datos[i].fichero,
										datos[i].fechaRegistro)
					);
				};

				// Imprimimos las fotos
				var contenido = "<div id=\"expand-all\" class=\"span9 text-center\">" +
								"<button type=\"button\" class=\"btn btn-info\" data-toggle=\"collapse\" data-target=\".collapse\">Ver / Ocultar todos los detalles &raquo;</button>" +
								"</div>";
				for (var i = 0; i < fotos.length; i++) {
					contenido +="<div class=\"span6 text-center\">" +
								"<figure class=\"figure\">" +
									"<a href=\"../index.php?content=7&amp;id="+fotos[i].getId()+"\">" +
										// La RUTABASE debe estar definida al inicio del fichero
										"<img class=\"miniature\" alt=\""+fotos[i].getTitulo()+"\" src=\"../timthumb.php?src="+RUTABASE+fotos[i].getFichero()+"&amp;w=500&amp;h=300\"/>" +
									"</a>" +
									"<figcaption id=\"f"+i+"\" class=\"figcaption collapse\">" +
										"<a href=\"../index.php?content=7&amp;id="+fotos[i].getId()+"\">" +
											"<h3>"+fotos[i].getTitulo()+"</h3>" +
											"<p><strong>Por "+fotos[i].getUsuario()+"</strong></p>" +
											"<p class=\"bold\">";
											if(fotos[i].getPais() !== null) {
												contenido += "En <span class=\"paisRes\">"+fotos[i].getPais()+"</span>. ";
											}
											if(fotos[i].getFecha() !== null) {
												contenido += "Hace <time class=\"fechaRes\" datetime=\""+fotos[i].getFecha().toISOString()+"\">"+fotos[i].getFechaEnTexto()+"</time>";
											}
					contenido +=			"<p><strong>Valoración de los usuarios</strong></p>" +
											"<p class=\"lead\">"+fotos[i].getPuntuacionMedia()+" sobre 5<br /><meter min=\"0\" max=\"5\" value=\""+fotos[i].getPuntuacionMedia()+"\" low=\"2\" high=\"4\" optimum=\"4\" title=\"Puntuación de "+fotos[i].getPuntuacionMedia()+" sobre 5\">"+fotos[i].getPuntuacionMedia()+"</meter></p>" +
											"</p>" +
										"</a>" +
									"</figcaption>" +
								"</figure>" +
								"<button type=\"button\" class=\"btn btn-info\" data-toggle=\"collapse\" data-target=\"#f"+i+"\">Ver / Ocultar detalles &raquo;</button>" +
								"</div>";
				};
				$('#mejores-fotos').html(contenido);
			}
			else {
				console.log("No se han encontrado fotos.");
			}
		},
		error: function(datos){ // Función a ejecutar si ERROR
			console.log(datos);
			console.log("Ha habido un problema al realizar la peticion");
		}
	});
}

// Peticion ajax para login mediante JQuery
function peticionAJAXLogin(url) {
	"use strict";
	// Para entrar pasamos el usuario y password
	var login = document.getElementById("usuario").value;
	var pass = document.getElementById("password").value;
	var args = "usuario=" + login + "&password=" + pass;

	args += "&v=" + (new Date()).getTime(); // Truco: evita utilizar la cache

	$.ajax({
	    type:"POST",
		url: url,
		data: args,
		async: true, // Asincrona
		dataType: "json", // Vamos a recibir un JSON
		success: function(datos){ // Función a ejecutar si todo OK.
			// Comprobamos la respuesta
			var respuesta = datos;
			var zonaUsuario = document.getElementById('zona-usuario');

			// Intenta acceder pero hay error
			if(respuesta === false) {
				if(document.getElementById('error-login') === null) {
					var span = document.createElement('span');
					span.id = 'error-login';
					span.className = 'fError';
					span.innerHTML = 'El usuario o contraseña son incorrectos.';
					zonaUsuario.appendChild(span);
				}
			}
			// Accede correctamente
			else {
				// Se cambia el formulario por la info del usuario
				var html = "<p><a href=\"../index.php?content=8\" title=\"Panel de control\">" +
							"<span id=\"zona-info-usuario\"><?php echo $_SESSION['sesion'];?></span>" +
							"<img id=\"zona-info-foto\" src=\"../timthumb.php?src=<?php echo $userFoto;?>&amp;w=32&amp;h=32\" alt=\"Panel de control\" /></a>" +
							"<a id=\"zona-info-cerrar\" href=\"../acceso.php?accion=salir\">Cerrar sesión</a>" +
						"</p>";

				
				zonaUsuario.innerHTML = html;

				// Utilizamos la info obtenida en JSON: nombre de usuario y foto
				var campo = document.getElementById('zona-info-usuario');
				campo.innerHTML = datos.usuario;
				campo = document.getElementById('zona-info-foto');
				campo.src = "../timthumb.php?src="+datos.foto+"&w=32&h=32";
			}
		},
		error: function(datos){ // Función a ejecutar si ERROR
			console.log(datos);
			console.log("Ha habido un problema al realizar la peticion");
		}
	});
}


