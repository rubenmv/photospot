/*jslint browser: true,  plusplus: true*/

// OBJETOS GLOBALES
var obj;
var pagina = 1; // Pagina actual o que se quiere cargar (para comentarios o fotos)
var album_actual = 0; // Album actual de paginacion de fotos
var foto_actual = 0; // foto actual de paginacion de comentarios
var usuario_actual = ""; // Usuario actual de paginacion de albumes
var fotos = []; // Lista de objetos Foto. Array literal (mas rapido que new)
var albumes = []; // Lista de objetos Album. Array literal (mas rapido que new)
var comentarios = []; // Lista de objetos Comentario
var RUTABASE = "http://localhost/photospot/";

/*  ****************
	FUNCIONES VARIAS
	**************** */

// Funcion que inserta despues del nodo de referencia
function insertAfter(refNode, newNode) {
	refNode.parentNode.insertBefore( newNode, refNode.nextSibling );
}

// Comprueba si el elemento tiene el atributo/propiedad indicado
function hasProperty (element, property) {
	if(property in element) { return true; }
	else { return false; }
}

// Escribe el texto en el campo de un nodo según la compatibilidad del navegador
function setText(nodo, texto) {
	if(hasProperty(nodo, 'textContent'))    { nodo.textContent = texto; }
	else if(hasProperty(nodo, 'innerText')) { nodo.innerText = texto; }
	else if(hasProperty(nodo, 'text'))      { nodo.text = texto; }
}

/* Convierte timestamp mySQL (yyyy-mm-dd hh:mm:ss) a Date
 * IE8 o menor no recoge el formato ISO 8601 para fechas (yyyy-mm-ddThh:mm:ssZ)
 * por lo que mejor usar esta funcion crossbrowser */
function timestampSQLToDate (sqlString) {
	var fecha, dma, time;
	fecha = sqlString.split(" ");
	dma = fecha[0].split("-");

	if(fecha.length === 2) {
		time = fecha[1].split(":");
		// yyyy, mm-1,     dd,     hh,      mm,      ss
		fecha = new Date(dma[0], dma[1]-1, dma[2], time[0], time[1], time[2]);
	}
	else {
		fecha = new Date(dma[0], dma[1]-1, dma[2]);
	}

	return fecha;
}

// Da formato "dia de mes de año" a las fechas de clase "fecha"
function formatDates() {
	'use strict';
	var fechas, i, fecha, dma;

	fechas = document.getElementsByClassName('fecha');
	// Convertimos las fechas SQL (aaaa-mm-dd hh:mm:ss) a objetos Date de Javascript
	for (i = 0; i < fechas.length; i++) {
		fecha = timestampSQLToDate(fechas[i].innerHTML);
		fechas[i].innerHTML = fecha.getFechaFormateada();
	}
}

/* getElementsByClassName para nodos que no son el propio 'document'
 * Es necesaria esta funcion para compatibilidad entre todo tipo de browsers */
function getElementsByClassName(node, className) {
	var a = [];
	var re = new RegExp('(^| )'+className+'( |$)');
	var els = node.getElementsByTagName('*');
	for(var i=0,j=els.length; i<j; i++) {
		if(re.test(els[i].className)) { a.push(els[i]); }
	}
	return a;
}

// Devuelve fecha en formato "dia de mes de año"
Date.prototype.getFechaFormateada = function() {
	'use strict';

	var meses = ['Enero', 'Febrero', 'Marzo',
				'Abril', 'Mayo','Junio',
				'Julio', 'Agosto', 'Septiembre',
				'Octubre', 'Noviembre', 'Diciembre'];

	return (this.getDate()+" de "+meses[this.getMonth()]+" de "+this.getFullYear());
};

// Devuelve la hora como "hh:mm"
Date.prototype.getShortTime = function() {
	'use strict';
	var h, m;

	h = this.getHours();
	if(h < 10) h = "0"+h;
	m = this.getMinutes();
	if(m < 10) m = "0"+m;

	return (h+":"+m);
};

// Devuelve la fecha en formato legible por humanos
Date.prototype.getNiceDate = function () {
	'use strict';
	return this.getFechaFormateada() + " a las " + this.getShortTime();
};

// Calcula la diferencia con la fecha recibida en el formato indicado por m
Date.prototype.getDifFecha = function(fecha, m) {
	'use strict';

	// Milisegundos
	var dif = this - fecha;
	if(dif < 0) { dif*=-1; }
	switch(m) {
		// Años
		case 'A': dif = Math.floor(dif / 1000 / 60 / 60 / 24 / 365); break;
		// Meses
		case 'M': dif = Math.floor(dif / 1000 / 60 / 60 / 24 / 30); break;
		// Dias
		case 'D': dif = Math.floor(dif / 1000 / 60 / 60 / 24); break;
		// Horas
		case 'H': dif = Math.floor(dif / 1000 / 60 / 60); break;
	}

	return dif;
};

// Agrega un nuevo elemento al historial de fotos visitadas
function addToHistorial(e) {
	var elemento;

	// Titulo de la foto
	var t = document.getElementById('tituloFoto').innerHTML,
		// Fecha de la visita (actual)
		f = new Date();

	// HISTORIAL DE SESION
	if(sessionStorage.getItem('historial')) {
		// Recogemos el string del historial y lo parseamos como JSON
		var jsonHist = JSON.parse(sessionStorage.getItem('historial'));

		// Agregamos el nuevo elemento
		jsonHist.push({ id: e, titulo: t, fecha: f });
		// Convertimos a string
		elemento = JSON.stringify(jsonHist);
	}
	else {
		elemento = JSON.stringify([{ id: e, titulo: t, fecha: f}]);
	}
	sessionStorage.setItem('historial', elemento);

	// HISTORIAL PERMANENTE
	if(localStorage.getItem('historial')) {
		// Recogemos el string del historial y lo parseamos como JSON
		var jsonHist = JSON.parse(localStorage.getItem('historial'));

		// Agregamos el nuevo elemento
		jsonHist.push({ id: e, titulo: t, fecha: f });
		// Convertimos a string
		elemento = JSON.stringify(jsonHist);
	}
	else {
		elemento = JSON.stringify([{ id: e, titulo: t, fecha: f}]);
	}
	localStorage.setItem('historial', elemento);
}

function getHistorial(modo) {
	var enlace, json;

	if(modo === 'sesion') {
		json = JSON.parse(sessionStorage.getItem('historial'));
		enlace = "<a href=\"#\" onclick=\"getHistorial('completo'); return false;\">Ver historial completo</a>";
	}
	else if(modo === 'completo') {
		json = JSON.parse(localStorage.getItem('historial'));
		enlace = "<a href=\"#\" onclick=\"getHistorial('sesion'); return false;\">Ver historial reciente</a>";
	}

	document.getElementById('enlace-historial').innerHTML = enlace;

	// Existe hitorial
	if(json !== null) {
		var fecha;
		// Creamos el html con la lista de elementos
		var html = "<table id=\"tHistorial\"><thead><tr><th>Fotografía</th><th>Fecha de visita</th></tr></thead>";
		for (var i = json.length - 1; i >= 0; i--) {
			// Recogemos la fecha en objeto Date
			fecha = new Date(json[i].fecha);
			html += "<tr><td><a href=\"index.php?content=7&amp;id="+json[i].id+"\">"+json[i].titulo+"</a></td><td>"+fecha.getNiceDate()+"</td></tr>";
		};
		html += "</table>";
		document.getElementById('seccion-historial').innerHTML = html;
	}
	// No existe historial
	else {
		document.getElementById('seccion-historial').innerHTML = '<p class="big fError">No hay historial reciente.</p>';
	}

}

function clearHistorial() {
	if(sessionStorage.getItem("historial")) {
		sessionStorage.removeItem('historial');
	}
	if(localStorage.getItem("historial")) {
		localStorage.removeItem('historial');
	}

	document.getElementById('seccion-historial').innerHTML = '<p class="big fError">Historial borrado.</p>';
}

// Agrega una imagen animada de carga para las peticiones ajax
function setLoader(idNode, replace) {
	var imagen = document.createElement('img');
	imagen.id = 'ajax-loader';
	imagen.className = 'ajax-loader';
	imagen.src = 'img/ajax-loader.gif';
	imagen.alt = 'Cargando';
	if(replace) document.getElementById(idNode).innerHTML = '';
	document.getElementById(idNode).appendChild(imagen);
}
// Elimina la imagen animada de carga para ajax
function removeLoader() {
	var loader = document.getElementById('ajax-loader'),
		padre  = loader.parentNode;
	padre.removeChild(loader);
}

// Parsea los datos recibidos en formato JSON para ser leidos en javascript
function parseJSON(JSONString) {
	if(window.JSON) // JSON nativo
		return JSON.parse(JSONString);
	else // Compatibilidad IE
		return eval( '(' + JSONString + ')' );
}

// CLASE FOTO
function Foto(id, titulo, fecha, pais, album, numVotos, puntuacionTotal, fichero) {
	'use strict';

	/* PROPIEDADES */
	this.id = id;
	this.titulo = titulo;
	this.fecha = fecha;
	this.pais = pais;
	this.album = album;
	this.numVotos = numVotos;
	this.puntuacionTotal = puntuacionTotal;
	this.fichero = fichero;

	/* METODOS */
	this.getId = function() { return this.id; };
	this.getTitulo = function() { return this.titulo; };
	this.getFecha = function() { return this.fecha; };
	this.getPais = function() { return this.pais; };
	this.getAlbum = function() { return this.album; };
	this.getVotos = function() { return this.numVotos; };
	this.getPuntuacionTotal = function() { return this.puntuacionTotal; };
	this.getFichero = function() { return this.fichero; };

	// Devuelve en formato texto el tiempo transcurrido
	// desde que se tomo la foto ("Hace 7 meses...")
	this.getFechaEnTexto = function() {

		var seg = Math.floor((new Date() - this.getFecha()) / 1000);
		var tiempo = Math.floor(seg / 31536000);

		if (tiempo > 1)
			return tiempo + " años";
		tiempo = Math.floor(seg / 2592000);

		if (tiempo > 1)
			return tiempo + " meses";
		tiempo = Math.floor(seg / 86400);

		if (tiempo > 1)
			return tiempo + " días";
		tiempo = Math.floor(seg / 3600);

		if (tiempo > 1)
			return tiempo + " horas";
		tiempo = Math.floor(seg / 60);

		if (tiempo > 1)
			return tiempo + " minutos";

		return Math.floor(seg) + " segundos";
	};

	// Calcula la puntuacion media de la foto utilizando los campos numVotos y puntuacionTotal.
	this.getPuntuacionMedia = function() {
		if(this.getVotos() > 0)
			return ((this.getPuntuacionTotal() / this.getVotos()).toFixed(2));
		else
			return 0;
	};
}

// CLASE ALBUM
function Album(id, titulo, descripcion, portada, fecha, pais, usuario) {
	'use strict';

	/* PROPIEDADES */
	this.id = id;
	this.titulo = titulo;
	this.descripcion = descripcion;
	this.portada = portada;
	this.fecha = fecha;
	this.pais = pais;
	this.usuario = usuario;

	/* METODOS */
	this.getId = function() { return this.id; };
	this.getTitulo = function() { return this.titulo; };
	this.getDescripcion = function() { return this.descripcion; };
	this.getPortada = function() { return this.portada; };
	this.getFecha = function() { return this.fecha; };
	this.getPais = function() { return this.pais; };
	this.getUsuario = function() { return this.usuario; };
}

// CLASE COMENTARIO
function Comentario(id, usuario, foto, texto, fecha) {
	'use strict';
	/* PROPIEDADES */
	this.id = id;
	this.usuario = usuario;
	this.foto = foto;
	this.texto = texto;
	this.fecha = fecha;

	/* METODOS */
	this.getId = function() { return this.id; };
	this.getUsuario = function() { return this.usuario; };
	this.getFoto = function() { return this.foto; };
	this.getTexto = function() { return this.texto; };
	this.getFecha = function() { return this.fecha; };
}

// Funcion que crea un objeto AJAX
function crearObjAjax() {
	'use strict';
	var xmlhttp;

	if(window.XMLHttpRequest)
		xmlhttp = new XMLHttpRequest();
	else if(window.ActiveXObject)
		xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
	return xmlhttp;
}

/* ******** */
/* 3. LOGIN */
/* ******** */
// Peticion AJAX de login
function peticionAJAXLogin(url) {
	'use strict';
	obj = crearObjAjax();

	if(obj) {
		// Para entrar pasamos el usuario y password
		var login = document.getElementById("usuario").value,
			pass = document.getElementById("password").value,
			recordar = document.getElementById("recordar").checked,
			args = "usuario=" + login + "&password=" + pass;
			if(recordar === true) args += "&recordar="+recordar;

		args += "&v=" + (new Date()).getTime(); // Truco: evita utilizar la cache

		// Se establece la función (callback) a la que llamar cuando cambie el estado:
		obj.onreadystatechange = function() {
			'use strict';
			if(obj.readyState == 4){ // valor 4: respuesta recibida y lista para ser procesada
				if(obj.status == 200){ // El valor 200 significa "OK"
					// Comprobamos la respuesta
					var respuesta = obj.responseText;
					var zonaUsuario = document.getElementById('zona-usuario');

					// Intenta acceder pero hay error
					if(respuesta === "false") {
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
						var html = "<p>" +
										"<div class=\"inline-block\">" +
											"<a id=\"zona-info-usuario\" href=\"index.php?content=8\" title=\"Panel de control\"><?php echo $_SESSION['sesion'];?></a><br/>" +
											"<a id=\"zona-info-cerrar\" href=\"acceso.php?accion=salir\">Cerrar sesión</a>" +
										"</div>" +
										"<div class=\"inline-block\">" +
											"<a id=\"zona-info-usuario\" href=\"index.php?content=8\" title=\"Panel de control\"><img id=\"zona-info-foto\" src=\"\" alt=\"Panel de control\" /></a>" +
										"</div>" +
									"</p>";
						zonaUsuario.innerHTML = html;

						console.log(respuesta);
						var datos = parseJSON(respuesta);

						// Utilizamos la info obtenida en JSON: nombre de usuario y foto
						var campo = document.getElementById('zona-info-usuario');
						campo.innerHTML = datos.usuario;
						campo = document.getElementById('zona-info-foto');
						campo.src = "timthumb.php?src="+datos.foto+"&w=32&h=32";

						// Se agregan las nuevas opciones del menu principal
						var menu = document.getElementById('mainNavItemList');
						var items = "<li><a href=\"index.php?content=10\">Subir foto</a></li>"+
									"<li><a href=\"index.php?content=11\">Mis álbumes</a></li>"+
									"<li><a href=\"index.php?content=8\">Panel de control</a></li>";
						menu.innerHTML += items;

						// Ocultamos la info de registro
						document.getElementById('info-registro').style.display = "none";
					}
				} // ERROR
				else { console.log("Hubo un problema con los datos devueltos"); }
			}
		}

		obj.open("POST", url, true); // Se crea petición POST a url, asíncrona ("true")
		// Es necesario especificar la cabecera "Content-type" para peticiones POST
		obj.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		obj.send(args); // Se envía la petición
	}
}

/* ******************** */
/* 4. BUSQUEDA DE FOTOS */
/* ******************** */
function peticionAJAXBusqueda(url, row) {
	'use strict';
	obj = crearObjAjax();

	if(obj) {
		var seccion = document.getElementById('resultados');
		// Crear la seccion para el resultado de la busqueda
		if(seccion === null) {
			seccion = document.createElement('section');
			seccion.id = "resultados";
		}

		// Agregar a la pagina
		var content = document.getElementById('content');
		content.appendChild(seccion);

		if(row === 0)  { setLoader('resultados', true);  } // Busqueda nueva
		else            { setLoader('resultados', false); } // Nuevos resultados misma busqueda

		// Recoger la info del formulario
		var titulo = document.getElementById('titulo');
		var fechaIni = document.getElementById('fechaIni');
		var fechaFin = document.getElementById('fechaFin');
		var pais = document.getElementById('pais');

		// Argumentos
		var args =  "titulo=" + titulo.value +
					"&fechaIni=" + fechaIni.value +
					"&fechaFin=" + fechaFin.value +
					"&pais=" + pais.selectedIndex +
					"&row=" + row;

		// Truco: evita utilizar la cache
		args += "&v=" + (new Date()).getTime();

		// Se establece la función (callback) a la que llamar cuando cambie el estado:
		obj.onreadystatechange = procesarBusqueda; // función callback: procesarLogin

		obj.open("POST", url, true); // Se crea petición POST a url, asíncrona
		// Es necesario especificar la cabecera "Content-type" para peticiones POST
		obj.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		obj.send(args); // Se envía la petición
	}
}

function procesarBusqueda(){
	'use strict';
	if(obj.readyState == 4){
		if(obj.status == 200){
			// Recogemos los objetos JSON
			var respuesta = obj.responseText;

			// Donde vamos a escribir el resultado
			var padre = document.getElementById('resultados');
			removeLoader();

			if(respuesta === "false") {
				var p = document.createElement('p');
				setText(p, 'No has introducido ningún criterio de búsqueda o no se ha encontrado nada con esos criterios.');
				padre.appendChild(p);
			}
			// Se han encontrado fotos
			else {
				var datos = parseJSON(respuesta);
				// Los datos son una variable a true o numerica con la fila de
				// la BBDD de la ultima foto a mostrar...
				var last = datos['last'];
				// ... y un array de arrays de datos de cada foto a mostrar
				datos = datos[0];

				fotos = [];
				// Crear objetos fotos con los datos obtenidos
				for (var i = 0; i < datos.length; i++) {
					// Convertimos las fechas timestamp SQL a Date
					if(datos[i].fecha !== null) {
						datos[i].fecha = timestampSQLToDate(datos[i].fecha);
					}

					// Introducimos la nueva foto
					fotos.push(new Foto(parseInt(datos[i].id),
										datos[i].titulo,
										datos[i].fecha,
										datos[i].pais,
										datos[i].album,
										parseInt(datos[i].numVotos),
										parseInt(datos[i].puntuacionTotal),
										datos[i].fichero)
					);
				};

				// Formulario de ordenación
				var ordena = document.getElementById('form-ordena');
				if (ordena !== null && ordena.className === 'hidden') {
					ordena.className = 'form-page';
				};

				// Lista ul sobre las que vamos a imprimir los resultados
				var ul = document.getElementById('lista-resumen');
				// Crear la ul para el resultado de la busqueda
				if(ul === null) {
					ul = document.createElement('ul');
					ul.id = ul.className = "lista-resumen";
					padre.appendChild(ul); // seccion 'content'
				}

				var contenido = "";
				for (var i = 0; i < fotos.length; i++) {
					contenido += "<li class=\"resumen\">" +
									"<figure class=\"figure\">" +
										"<a href=\"index.php?content=7&amp;id="+fotos[i].getId()+"\">" +
											"<img class=\"miniature\" draggable=\"true\" src=\"timthumb.php?src="+RUTABASE+fotos[i].getFichero()+"&amp;w=300&amp;h=180\" alt=\""+fotos[i].getTitulo()+"\" />" +
										"</a>" +
										"<div class=\"edit-icon\"><a href=\"index.php?content=16&amp;id="+fotos[i].getId()+"\"></a></div>" +
										"<figcaption class=\"figcaption\">" +
											"<a href=\"index.php?content=7&amp;id="+fotos[i].getId()+"\">" +
												"<h4 class=\"titulo\">"+fotos[i].getTitulo()+"</h4>";
												if(fotos[i].getPais() !== null) {
													contenido += "<p><span class=\"pais\">"+fotos[i].getPais()+"</span></p>";
												}
												else {
													contenido += "<p>Sin país</p>";
												}
												if(fotos[i].getFecha() !== null) {
													contenido += "<p><time class=\"fecha\" datetime=\""+fotos[i].getFecha().toISOString()+"\">"+fotos[i].getFecha().getFechaFormateada()+"</time><br /></p>";
												}
												else {
													contenido += "<p>Sin fecha</p>";
												}
					contenido +=        "</a>" +
										"</figcaption>" +
									"</figure>" +
								"</li>";
				};
				ul.innerHTML += contenido; // Siempre se suma (si es necesario, se vacia antes de procesar)

				// Si no era la ultima foto, mostramos boton
				var boton = document.getElementById('mas-resultados');
				if(last !== true) {
					last++; // La primera de las siguientes a pedir
					if(!boton) {
						boton = document.createElement('a');
						boton.id = 'mas-resultados';
						boton.className = 'boton';
						boton.href = 'javascript:;'; // Desactiva el enlace
						setText(boton, 'Más resultados');
						padre.appendChild(boton);
					}
					// Modificamos el enlace del boton
					boton.onclick = function(){ peticionAJAXBusqueda('buscarFotos.php', last); };
				}
				else {
					var boton = document.getElementById('mas-resultados');
					if(boton) {
						var padre = boton.parentNode;
						padre.removeChild(boton);
					}
				}
			}

		}
		else { // Error
			console.log('Error obj.status: '+obj.status);
		}
	}
	else if(obj.readyState == 0) {
		console.log('Error readyState: '+obj.readyState);
	}
}

/* ************** */
/* 5. COMENTARIOS */
/* ************** */
function peticionAJAXComentarios(url, idFoto, pag, ref) {

	foto_actual = idFoto;
	pagina = pag;

	var args =  "idFoto=" + idFoto + "&pagina=" + pagina;
	if(ref > 0) { args +=  "&ref=" + ref; }
	args += "&v=" + (new Date()).getTime();

	$.ajax({
		type:"POST",
		url: url,
		data: args,
		async: true, // Asincrona
		dataType: "json", // Vamos a recibir un JSON
		success: function(datos){ // Función a ejecutar si todo OK.
			if(datos !== false) {
				if(datos['comentarios'].length > 0) {
					var last = datos['last'];
					datos = datos['comentarios'];

					// Crear objetos comentario con los datos obtenidos
					comentarios = [];
					for (var i = 0; i < datos.length; i++) {
						// Convertimos las fechas timestamp SQL a Date
						if(datos[i].fecha !== null) {
							datos[i].fecha = timestampSQLToDate(datos[i].fecha);
						}
						comentarios.push(new Comentario(parseInt(datos[i].id),
														datos[i].usuario,
														datos[i].foto,
														datos[i].texto,
														datos[i].fecha)
									);
					};

					var padre = document.getElementById('section-comentarios');
					var ul = document.getElementById('lista-comentarios');
					if(ul === null) {
						// Creamos la lista de los comentarios
						ul = document.createElement('ul');
						ul.id = ul.className = "lista-comentarios";
						// Insertamos la lista justo despues del ultimo hijo de la zona de comentario
						padre.appendChild(ul); // seccion 'content'
					}

					var contenido = "";

					for (var i = 0; i < comentarios.length; i++) {

						// El comentario a resaltar
						if(comentarios[i].getId() === ref) {
							contenido += "<li class=\"resaltado comentario\">";
						}
						else {
							contenido += "<li class=\"comentario\">";
						}

						contenido += "<p class=\"autor-comentario\">" +
										 "<span class=\"italic small\">Por <span class=\"bold\">"+comentarios[i].getUsuario() + "</span>" +
											 " el <time >" +
												 "<span class=\"bold\">"+comentarios[i].getFecha().getFechaFormateada() +
												 "</span> a las <span class=\"bold\">"+comentarios[i].getFecha().getShortTime() + "</span>" +
										 "</time></span>" +
									 "</p><br /><p class=\"content-comentario\">"+ comentarios[i].getTexto() +"</p></li>";
					};
					ul.innerHTML = contenido;

					// Comentarios alrededor del de referencia, "Mostrar todos los comentarios"
					if(ref > 0) {
						var paginacion = document.getElementById('paginacion');

						if(paginacion === null) {
							paginacion = document.createElement('div');
							paginacion.id = "paginacion";
							padre.appendChild(paginacion);
						}
						contenido = "<p class=\"bold\"><a href=\"#\" onclick=\"peticionAJAXComentarios('getComentarios.php', "+foto_actual+", 1, 0); return false;\">Mostrar todos los comentarios</a></p>";
						paginacion.innerHTML = contenido;
					}
					// Todos los comentarios, paginacion
					else {
						// Actualizamos la paginacion
						if((pagina === 1 && !last) || pagina !== 1) {
							var paginacion = document.getElementById('paginacion');

							if(paginacion === null) {
								paginacion = document.createElement('div');
								paginacion.id = "paginacion";
								padre.appendChild(paginacion);
							}

							contenido = "<p class=\"bold\">";
							// Anterior pagina
							if(pagina === 1) { contenido += "Anterior"; }
							else {
								contenido += "<a href=\"#\" onclick=\"peticionAJAXComentarios('getComentarios.php', "+foto_actual+", "+(pagina-1)+", 0); return false;\">Anterior</a>";
							}
							// Separador
							contenido += " | ";
							// Siguiente pagina
							if(last) { contenido += "Siguiente"; }
							else {
								contenido += "<a href=\"#\" onclick=\"peticionAJAXComentarios('getComentarios.php', "+foto_actual+", "+(pagina+1)+", 0); return false;\">Siguiente<a>";
							}
							contenido += "</p>";

							paginacion.innerHTML = contenido;
						}
						else {
							var paginacion = document.getElementById('paginacion');
							if(paginacion !== null) {
								padre.removeChild(paginacion);
							}
						}
					}
				}
			}
			else { console.log("No se han encontrado comentarios"); }
		},
		error: function(datos){ // Función a ejecutar si ERROR
			console.log("Ha habido un problema al realizar la peticion");
		}
	});
}

// b. ENVIAR COMENTARIO MEDIANTE AJAX
function peticionAJAXEnviarComentario(url, idFoto, usuario) {
	'use strict';
	obj = crearObjAjax();

	// Comprobamos que se ha introducido texto
	var texto = document.getElementById('comentario').value;
	if(texto.length > 0) {
		if(obj) {
			var args = "idFoto=" + idFoto + "&usuario=" + usuario + "&texto=" + texto;
			args += "&v=" + (new Date()).getTime();

			// Se establece la función (callback) a la que llamar cuando cambie el estado:
			obj.onreadystatechange = procesarEnviarComentario; // función callback: procesarLogin

			obj.open("POST", url, false); // Se crea petición POST a url, síncrona ("false")
			// Es necesario especificar la cabecera "Content-type" para peticiones POST
			obj.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			obj.send(args); // Se envía la petición
		}
	}
	else {
		var padre = document.getElementById('form-comentario');
		if(document.getElementById('span-error') === null) {
			var span = document.createElement('span');
			span.id = 'span-error';
			span.className = 'fError';
			span.innerHTML = 'Debes escribir algo para enviar un comentario.';
			padre.appendChild(span);
		}
	}
}

function procesarEnviarComentario(){
	'use strict';
	if(obj.readyState == 4){ // valor 4: respuesta recibida y lista para ser procesada
		if(obj.status == 200){ // El valor 200 significa "OK"
				var respuesta = obj.responseText;

				// La respuesta contiene la id de la foto
				if(respuesta !== 'false') {
					// Actualizamos los comentarios mediante AJAX
					peticionAJAXComentarios('getComentarios.php', respuesta, 1);
					// Limpiamos el formulario de envio
					document.getElementById('comentario').value = "";
				}
				// Ha habido un problema al insertar el comentario
				else {
					console.log("Hubo un problema insertando el nuevo comentario");
				}
			}
			else { // cualquier otra cosa significa error
				console.log("Hubo un problema con los datos devueltos");
		}
	}
}

/* ********************** */
/* 6. SISTEMA DE VOTACION */
/* ********************** */
function peticionAJAXVotar(url, idFoto) {
	'use strict';
	obj = crearObjAjax();

	if(obj) {
		var puntos = (document.getElementById("select-voto").selectedIndex)+1;

		var args = "idFoto=" + idFoto + "&puntos=" + puntos;
		args += "&v=" + (new Date()).getTime();

		// Se establece la función (callback) a la que llamar cuando cambie el estado:
		obj.onreadystatechange = procesarVotar; // función callback: procesarLogin

		obj.open("POST", url, false); // Se crea petición POST a url, síncrona ("false")
		// Es necesario especificar la cabecera "Content-type" para peticiones POST
		obj.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		obj.send(args); // Se envía la petición
	}
}

function procesarVotar(){
	'use strict';
	if(obj.readyState == 4){ // valor 4: respuesta recibida y lista para ser procesada
		if(obj.status == 200){ // El valor 200 significa "OK"
				var respuesta = obj.responseText;

				if(respuesta !== 'false') {
					// Todo OK. Actualizamos el meter
					var meter = document.getElementById('medidor-puntos');
					if(meter === null) { // Si no existe lo creamos
						meter = document.createElement('meter');
						meter.id = 'medidor-puntos';
						meter.setAttribute("min", 0);
						meter.setAttribute("max", 5);
						meter.setAttribute("value", respuesta);
						meter.setAttribute("low", 2);
						meter.setAttribute("high", 4);
						meter.setAttribute("optimum", 4);
						meter.setAttribute("title", "Puntuación de "+respuesta+" sobre 5");
						var padre = document.getElementById('puntuacion-foto');
						padre.innerHTML = "";
						padre.appendChild(meter);
					}
					else {
						meter.value = respuesta;
						meter.title = "Puntuación de "+respuesta+" sobre 5";
					}
					meter.innerHTML = respuesta;

					// Modificamos el formulario de voto por una confirmacion
					var form = document.getElementById('form-votar');
					var p = document.createElement('p');
					p.innerHTML = "¡Voto enviado!";
					padre = document.getElementById('detalle-info');
					padre.replaceChild(p, form);
				}
			}
			else { // cualquier otra cosa significa error
				console.log("Hubo un problema con los datos devueltos");
		}
	}
}

/* *************************** */
/* 7. PAGINACION ALBUMES/FOTOS */
/* *************************** */
function peticionAJAXAlbumes(url, usuario, row) {
	'use strict';
	obj = crearObjAjax();

	if(obj) {
		pagina = row;
		usuario_actual = usuario;

		if(row === 0)  { setLoader('content-data', true);  } // Acceso a mis albumes
		else           { setLoader('content-data', false); } // Carga segun se hace scroll

		// Argumentos
		var args =  "usuario=" + usuario + "&row=" + row + "&v=" + (new Date()).getTime();

		// Se establece la función (callback) a la que llamar cuando cambie el estado:
		obj.onreadystatechange = procesarAlbumes; // función callback: procesarLogin

		obj.open("POST", url, true); // Se crea petición POST a url, asíncrona
		// Es necesario especificar la cabecera "Content-type" para peticiones POST
		obj.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		obj.send(args); // Se envía la petición
	}
}

function procesarAlbumes(){
	'use strict';
	if(obj.readyState == 4){
		if(obj.status == 200){
			// Recogemos los objetos JSON
			var respuesta = obj.responseText;
			// Donde vamos a escribir el resultado
			var padre = document.getElementById('content-data');
			removeLoader();

			// No se han encontrado fotos
			if(respuesta === "false") {
				var p = document.createElement('p');
				p.innerHTML = "¡No se han encontrado álbumes!<br />" +
								  "<a href=\"index.php?content=9\">Crea un álbum.</a><";
				padre.appendChild(p);
			}
			// Se han encontrado fotos
			else {
				var datos = parseJSON(respuesta);
				// Los datos son una variable a true o numerica con la fila de
				// la BBDD de la ultima foto a mostrar...
				var last = datos['last'];
				// ... y un array de arrays de datos de cada album a mostrar
				datos = datos[0];

				albumes = [];
				// Crear objetos fotos con los datos obtenidos
				for (var i = 0; i < datos.length; i++) {
					// Convertimos las fechas timestamp SQL a Date
					if(datos[i].fecha !== null) {
						datos[i].fecha = timestampSQLToDate(datos[i].fecha);
					}
					// Introducimos el nuevo album
					albumes.push(new Album(parseInt(datos[i].id),
										datos[i].titulo,
										datos[i].descripcion,
										datos[i].portada,
										datos[i].fecha,
										datos[i].pais,
										datos[i].usuario)
					);
				};

				// Restaura el titulo de la seccion
				setText(document.getElementById('tituloSeccion'), 'Mis álbumes');

				// Lista ul sobre las que vamos a imprimir los resultados
				var ul = document.getElementById('lista-resumen');
				// Crear la ul para el resultado de la busqueda
				if(ul === null) {
					ul = document.createElement('ul');
					ul.id = ul.className = "lista-resumen";
					padre.innerHTML = '<a class="boton boton-nuevo-album" href="index.php?content=9">Crear álbum</a>';
					padre.appendChild(ul); // seccion 'content'
				}

				var contenido = "";
				for (var i = 0; i < albumes.length; i++) {
					contenido += "<li class=\"resumen\">" +
									"<figure class=\"figure\">" +
										"<a href=\"#\" onclick=\"peticionAJAXFotosAlbum('getFotosAlbum.php', "+albumes[i].getId()+", 0); return false;\">" +
											"<img class=\"miniature\" draggable=\"true\" src=\"timthumb.php?src="+RUTABASE+albumes[i].getPortada()+"&amp;w=300&amp;h=180\" alt=\""+albumes[i].getTitulo()+"\" />" +
										"</a>" +
										"<figcaption class=\"figcaption\">" +
											"<a href=\"#\" onclick=\"peticionAJAXFotosAlbum('getFotosAlbum.php', "+albumes[i].getId()+", 0); return false;\">" +
												"<h4 class=\"titulo\">"+albumes[i].getTitulo()+"</h4>";
												if(albumes[i].getPais() !== null) {
													contenido += "<p><span class=\"pais\">"+albumes[i].getPais()+"</span></p>";
												}
												else {
													contenido += "<p>Sin país</p>";
												}
												if(albumes[i].getFecha() !== null) {
													contenido += "<p><time class=\"fecha\" datetime=\""+albumes[i].getFecha().toISOString()+"\">"+albumes[i].getFecha().getFechaFormateada()+"</time><br /></p>";
												}
												else {
													contenido += "<p>Sin fecha</p>";
												}
					contenido +=        "</a>" +
										"</figcaption>" +
									"</figure>" +
								"</li>";
				};
				ul.innerHTML += contenido;

				// Si no era la ultima foto, mostramos boton
				var boton = document.getElementById('mas-resultados');
				if(last !== true) {
					last++; // La primera de las siguientes a pedir
					if(!boton) {
						boton = document.createElement('a');
						boton.id = 'mas-resultados';
						boton.className = 'boton';
						boton.href = 'javascript:;'; // Desactiva el enlace
						setText(boton, 'Más resultados');
						padre.appendChild(boton);
					}
					// Modificamos el enlace del boton
					boton.onclick = function() { peticionAJAXAlbumes('getAlbumes.php', datos[0].usuario, last); };
				}
				else {
					var boton = document.getElementById('mas-resultados');
					if(boton) {
						var padre = boton.parentNode;
						padre.removeChild(boton);
					}
				}
			}
		}
		else {
			console.log('Error obj.status: '+obj.status);
		}
	}
	else if(obj.readyState == 0) {
		console.log('Error readyState: '+obj.readyState);
	}
}

// Peticion AJAX de las fotos de un album
function peticionAJAXFotosAlbum(url, album, row) {
	'use strict';
	obj = crearObjAjax();

	if(obj) {
		if(row === 0)  { setLoader('content-data', true);  } // Acceso al album
		else           { setLoader('content-data', false); } // Carga segun se hace scroll

		// Argumentos
		var args = "album=" + album + "&row=" + row + "&v=" + (new Date()).getTime();
		// Se establece la función (callback) a la que llamar cuando cambie el estado:
		obj.onreadystatechange = function() { procesarFotosAlbum(album); } // función callback: procesarLogin

		obj.open("POST", url, true); // Se crea petición POST a url, asíncrona
		// Es necesario especificar la cabecera "Content-type" para peticiones POST
		obj.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		obj.send(args); // Se envía la petición
	}
}
// Muestra las fotos de un album segun la peticion ajax
function procesarFotosAlbum(album){
	'use strict';
	if(obj.readyState == 4){
		if(obj.status == 200){
			// Recogemos los objetos JSON
			var respuesta = obj.responseText;

			// Donde vamos a escribir el resultado
			var padre = document.getElementById('content-data');
			removeLoader();

			// No se han encontrado fotos
			if(respuesta === "false") {
				var p = document.createElement('p');
				p.innerHTML =   "¡No se han encontrado fotos en este álbum!<br />" +
								"<a href=\"index.php?content=10\">Sube una foto.</a>";
				padre.appendChild(p);
			}
			// Se han encontrado fotos
			else {
				var datos = parseJSON(respuesta);

				// Los datos son una variable a true o numerica con la fila de
				// la BBDD de la ultima foto a mostrar...
				var last = datos['last'];
				// ... y un array de arrays de datos de cada foto a mostrar
				datos = datos[0];

				fotos = [];
				// Crear objetos fotos con los datos obtenidos
				for (var i = 0; i < datos.length; i++) {
					// Convertimos las fechas timestamp SQL a Date
					if(datos[i].fecha !== null) {
						datos[i].fecha = timestampSQLToDate(datos[i].fecha);
					}
					// Introducimos la nueva foto
					fotos.push(new Foto(parseInt(datos[i].id),
										datos[i].titulo,
										datos[i].fecha,
										datos[i].pais,
										datos[i].album,
										parseInt(datos[i].numVotos),
										parseInt(datos[i].puntuacionTotal),
										datos[i].fichero)
					);
				};

				// Lista ul sobre las que vamos a imprimir los resultados
				var ul = document.getElementById('lista-resumen');
				// Crear la ul para el resultado de la busqueda
				if(ul === null) {
					ul = document.createElement('ul');
					ul.id = ul.className = "lista-resumen";
					padre.appendChild(ul); // seccion 'content'
				}

				document.getElementById('tituloSeccion').innerHTML = "<a href=\"#\" onclick=\"peticionAJAXAlbumes('getAlbumes.php', '"+usuario_actual+"', 0);\"> Mis álbumes</a> - " + fotos[0].getAlbum();;

				var contenido = "";
				for (var i = 0; i < fotos.length; i++) {
					contenido += "<li class=\"resumen\">" +
									"<figure class=\"figure\">" +
										"<a href=\"index.php?content=7&amp;id="+fotos[i].getId()+"\">" +
											"<img class=\"miniature\" draggable=\"true\" src=\"timthumb.php?src="+RUTABASE+fotos[i].getFichero()+"&amp;w=300&amp;h=180\" alt=\""+fotos[i].getTitulo()+"\" />" +
										"</a>" +
										"<div class=\"edit-icon\"><a href=\"index.php?content=16&amp;id="+fotos[i].getId()+"\"></a></div>" +
										"<figcaption class=\"figcaption\">" +
											"<a href=\"index.php?content=7&amp;id="+fotos[i].getId()+"\">" +
												"<h4 class=\"titulo\">"+fotos[i].getTitulo()+"</h4>";
												if(fotos[i].getPais() !== null) {
													contenido += "<p><span class=\"pais\">"+fotos[i].getPais()+"</span></p>";
												}
												else {
													contenido += "<p>Sin país</p>";
												}
												if(fotos[i].getFecha() !== null) {
													contenido += "<p><time class=\"fecha\" datetime=\""+fotos[i].getFecha().toISOString()+"\">"+fotos[i].getFecha().getFechaFormateada()+"</time><br /></p>";
												}
												else {
													contenido += "<p>Sin fecha</p>";
												}
					contenido +=        "</a>" +
										"</figcaption>" +
									"</figure>" +
								"</li>";
				};

				ul.innerHTML += contenido;

				// Si no era la ultima foto, mostramos boton
				var boton = document.getElementById('mas-resultados');
				if(last !== true) {
					last++; // La primera de las siguientes a pedir

					if(!boton) {
						boton = document.createElement('a');
						boton.id = 'mas-resultados';
						boton.className = 'boton';
						boton.href = 'javascript:;'; // Desactiva el enlace
						setText(boton, 'Más resultados');
						padre.appendChild(boton);
					}
					// Modificamos el enlace del boton
					boton.onclick = function(){ peticionAJAXFotosAlbum('getFotosAlbum.php', album, last); };
				}
				else {
					var boton = document.getElementById('mas-resultados');
					if(boton) {
						var padre = boton.parentNode;
						padre.removeChild(boton);
					}
				}
			}
		}
		else { // Error
			console.log('Error obj.status: '+obj.status);
		}
	}
	else if(obj.readyState == 0) {
		console.log('Error readyState: '+obj.readyState);
	}
}

/* ******************
   PRACTICA 3: JQUERY
 * ****************** */
function peticionAJAXLastComments() {
	'use strict';

	$.ajax({
		type:"POST",
		url: "getComentarios.php",
		async: true, // Asincrona
		dataType: "json", // Vamos a recibir un JSON
		success: function(datos){ // Función a ejecutar si todo OK.
			if(datos !== false) {
				$('#comentarios-recientes').empty().append('<h3>Comentarios recientes</h3>');

				datos = datos['comentarios'];

				// Crear objetos comentario con los datos obtenidos
				comentarios = [];
				for (var i = 0; i < datos.length; i++) {
					// Convertimos las fechas timestamp SQL a Date
					if(datos[i].fecha !== null) {
						datos[i].fecha = timestampSQLToDate(datos[i].fecha);
					}
					comentarios.push(new Comentario(parseInt(datos[i].id),
													datos[i].usuario,
													datos[i].foto,
													datos[i].texto,
													datos[i].fecha)
								);
				};

				var ul = document.getElementById('lista-comentarios');
				if(ul === null) {
					// Creamos la lista de los comentarios
					ul = document.createElement('ul');
					ul.id = ul.className = "lista-comentarios";
					// Insertamos la lista justo despues del ultimo hijo de la zona de comentario
					$('#comentarios-recientes').append(ul); // seccion 'content'
				}

				var contenido = "";
				for (var i = 0; i < comentarios.length; i++) {
					contenido += "<li class=\"comentario\">" +
								 "<p class=\"autor-comentario\">" +
								 "<a href=\"index.php?content=7&amp;id="+comentarios[i].getFoto()+"&amp;ref="+comentarios[i].getId()+"\">" +
									 "<span class=\"italic small\">Por <span class=\"bold\">"+comentarios[i].getUsuario() + "</span>" +
										 " el <time datetime=\""+comentarios[i].getFecha().toISOString()+"\">" +
											 "<span class=\"bold\">"+comentarios[i].getFecha().getFechaFormateada() +
											 "</span> a las <span class=\"bold\">"+comentarios[i].getFecha().getShortTime() + "</span>" +
									 "</time></span>" +
								 "<br /><br /></a></p><p class=\"content-comentario\">"+
												 "<a href=\"index.php?content=7&amp;id="+comentarios[i].getFoto()+"&amp;ref="+comentarios[i].getId()+"\">" +
													 comentarios[i].getTexto()+"</a></p></li>";
				};
				ul.innerHTML = contenido;
			}
		},
		error: function(){ // Función a ejecutar si ERROR
			console.log("Ha habido un problema al realizar la peticion");
		}
	});
}

/*  **************************************
	FUNCIONES DE VALIDACIÓN DE FORMULARIOS
	************************************** */

// Utiliza regex para encontrar una palabra dentro de un string
function findInString (palabra, cadena) {
	var exp = new RegExp("\\b"+palabra+"\\b");

	if(exp.test(cadena))    return true;
	else                    return false;
}

// Funcion que comprueba si el valor está vacío (devuelve true)
// trim() elimina los espacios en blanco
function checkBlank(valor) {
	'use strict';
	if(valor === null || valor.trim() === "")   return true;
	else                                        return false;
}

// Funcion que comprueba una expresión regular con el valor del campo
function checkRegex(valor, expresion) {
	'use strict';
	var expreg = new RegExp(expresion);

	if(expreg.test(valor))  return true;
	else                    return false;
}

// Comprueba si es correcta la fecha en formato "año-mes-dia"
function checkFecha(fecha) {
	fecha = fecha.split('-');

	if(fecha.length === 3) {
		// Vamos a trabajar con number
		fecha[0] = parseInt(fecha[0]);
		fecha[1] = parseInt(fecha[1])-1; // Date de 0 a 11
		fecha[2] = parseInt(fecha[2]);

		var fechaJS = new Date(fecha[0], fecha[1], fecha[2]);

		if( fechaJS.getFullYear() === fecha[0] &&
			fechaJS.getMonth() === fecha[1] &&
			fechaJS.getDate() === fecha[2]) {
			return true;
		}
		else { return false; }
	}
	else {
		return false;
	}
}

// Date usa 0-11 para los meses, por lo que no se le suma ya que asi
// se escoge el dia 0 del mes siguiente = ultimo dia del mes que buscamos
function daysInMonth(month, year) {
	'use strict';
	return new Date(year, month, 0).getDate();
}
// Actualiza los días disponibles según la seleccíón del año y mes
function updateDaysField(ref) {
	'use strict';

	var selectors,
		year,
		month,
		day,
		option;

	// Recogemos los campos select de año, mes y dia
	selectors = getElementsByClassName(ref.parentNode, 'date-selector');
	year  = selectors[0];
	month = selectors[1];
	day   = selectors[2];

	// Los value indican el año completo
	year = year.options[year.selectedIndex].value;
	// El indice nos indica el numero de mes (1-12)
	month = month.selectedIndex;
	// Reseteamos los dias y agregamos option por defecto
	day.options.length = 0;
	option = document.createElement('option');
	day.options.add(option);
	option.value = '0';
	option.selected = 'selected';
	setText(option, 'Día');
	day.onchange(); // Llamamos para actualizar el campo con la fecha final

	if(parseInt(year) > 0 && month > 0) {
		// Dias segun el mes y el año
		var totalDays = daysInMonth(month, year);
		// Creamos las option empezando en value = 1
		for (var i = 1; i <= totalDays; i++) {
			option = document.createElement('option');
			// Agregamos la nueva opcion antes de cambiar el texto (IE7/8/9)
			day.options.add(option);
			option.value = i;
			setText(option, i);
		}
	}
}

// Actualiza el campo de fecha con la seleccionada en los campos (select)
function updateDateField(idField) {
	var dateField = document.getElementById(idField),
		dateParent = dateField.parentNode,
		year,
		month,
		day;


	// Recogemos los campos select de año, mes y dia
	selectors = getElementsByClassName(dateField.parentNode, 'date-selector');
	year  = selectors[0];
	month = selectors[1];
	day   = selectors[2];

	// Los value indican el año completo
	year = year.options[year.selectedIndex].value;
	// El indice nos indica el numero de mes (1-12)
	month = month.selectedIndex;
	// Si hay año y mes, seleccionamos dia
	if(parseInt(year) > 0 && month > 0) {
		day = day.selectedIndex;
		// Si el dia está seleccionado, creamos la nueva fecha
		if(day > 0) {
			if(month < 10) month = '0'+month;
			if(day < 10)   day = '0'+day;
			dateField.value = year+'-'+month+'-'+day;
		}
		else { dateField.value = ''; } // En caso contrario borramos
	}
}

// Funcion que ejecuta el file picker para subir fotos
function triggerFilePicker(idPicker) {
	'use strict';
	document.getElementById(idPicker).click();
	return false; // Para evitar el submit
}
// Funcion que imprime al lado del boton el fichero seleccionado
function printSelectedFile(valor) {
	'use strict';
	var span = document.getElementById('filename');

	if(!span) {
		span = document.createElement('span');
		insertAfter(document.getElementById('buttonFile'), span);
		span.id = 'filename';
		span.className = 'filename';
	}

	// Extraemos la ultima parte de la url del fichero
	valor = valor.split("\\");
	setText(span, valor.last());
}

// Resetea un grupo de radio inputs basandose en su name
function resetRadioGroup(name) {
	'use strict';
	var radios = document.getElementsByName(name);
	for (var i = 0; i < radios.length; i++) { radios[i].checked = false; };
}

// Resetea el mensaje de error. Recibe el campo adyacente o el mismo id del error
function resetError(campo) {
	'use strict';
	var errorSpan;

	if(typeof(campo) === 'string') {
		errorSpan = campo;
	}
	else {
		/* Id del span que contiene el mensaje de error
		 * IMPORTANTE hacer esto antes que nada */
		errorSpan = "error_"+campo.name;
		/*  En los radio debemos movernos al padre
			ya que este tiene como hijo al span de error */
		if(campo.type === "radio") { campo = campo.parentNode; }

		$(campo).removeClass('input-error');
	}

	var span = document.getElementById(errorSpan);
	if(span !== null) {
		// Vamos al parentNode del error
		var parent = span.parentNode;
		// y eliminamos el span hijo con el id recogido
		$(span).fadeOut("normal", function() {
			parent.removeChild(span);
		});
	}
}

// Funcion que marca en rojo los campos incorrectos
// y muestra el mensaje de error
function setError(campo, mensaje) {
	'use strict';
	// Reseteamos el error para que borre el span (si lo hay)
	resetError(campo);

	campo.className += ' input-error';

	// Creamos un span de error
	var span = document.createElement('span');
	span.id = "error_"+campo.name;
	span.className = "fError";
	span.innerHTML = mensaje;

	// Nos movemos al div padre para imprimir el span de error al lado de todo el grupo
	if(campo.type === "radio") {
		campo = campo.parentNode;
	}
	// Insertamos animacion con JQuery para compatibilidad entre navegadores
	$(span).hide().insertAfter(campo).fadeIn("slow");
}

// Funcion principal, recorre los campos y comprueba que los requeridos
// esten correctamente
function validarCampos(formulario) {
	'use strict';

	// EXPRESIONES REGULARES
	var rgNombre    = "^[A-Za-z0-9]{3,15}$",
		rgPass      = "^(?=.*\\d)(?=.*[A-Z])(?=.*[a-z])\\w{6,15}$",
		rgEmail     = "^[\\w-\\+]+(\\.[\\w-]+)*@"
					+ "[A-Za-z0-9-]+(\\.[A-Za-z0-9]+)*(\\.[A-Za-z]{2,4})$",
	// MENSAJES DE ERROR
		err01 = "Este campo no puede estar vacío",
		err02 = "Nombre de usuario incorrecto",
		err03 = "Contraseña incorrecta",
		err04 = "Las contraseñas no coinciden",
		err05 = "Email incorrecto",
		err06 = "Debes escoger una opción",
		err07 = "Fecha incorrecta",
		err08 = "Debes introducir algún criterio de búsqueda",
	// OTROS
		valor,              // Valor de los campos
		valida = true,      // Se activa cuando encuentra algún error y al final se devuelve
		radCheck = false,   // Indica si hay algun elemento marcado (boolean)
		radNombre,          // Nombre del grupo de radios: inicialmente undefined
		radioArray,         // Array con todos los radios de un mismo nombre
		blanco = true,      // Indica si todos los campos estan en blanco
		i = 0, j = 0;

	// Recorremos los elementos del formulario
	for(i=0; i<formulario.elements.length; i++) {
		switch (formulario[i].type)    {
			// Inputs de tipo texto, password y file
			case "text":
			case "date":
			case "password":
			case "file":
				valor = formulario[i].value;
				// Comprueba si el campo tiene algo escrito
				if(!checkBlank(valor)) {
					blanco = false;
					// Comprueba las condiciones de cada campo
					switch(formulario[i].name) {
						case "nombre":
							if(!checkRegex(valor, rgNombre)) {
								setError(formulario[i], err02);
								valida=false;
							}
						break;
						case "pass":
							if(valor !== "default" && !checkRegex(valor, rgPass)) {
								setError(formulario[i], err03);
								valida = false;
							}
						break;
						case "cpass":
							if(valor !== formulario[i-1].value) {
								setError(formulario[i], err04);
								valida = false;
							}
						break;
						case "email":
							if(!checkRegex(valor, rgEmail)) {
								setError(formulario[i], err05);
								valida = false;
							}
						break;
						case "fechaIni":
						case "fechaFin":
						case "fechaNac":
						case "fecha":
							if(!checkFecha(valor)) {
								setError(formulario[i], err07);
								valida = false;
							}
						break;
					}
				}
				// Si esta en blanco y es obligatorio
				else if(findInString('required', formulario[i].className) === true) {
					setError(formulario[i], err01);
					valida = false;
				}
			break;
			// Para selects de tipo único
			case "select-one":
				if(formulario[i].selectedIndex !== 0) { blanco = false; }
				if(findInString('required', formulario[i].className) === true && formulario[i].selectedIndex === 0){
					setError(formulario[i], err06);
					valida = false;
				}
			break;
		}
	}
	// Para el formulario de búsqueda
	if(formulario.id === "form-busqueda" && blanco) {
		document.getElementById("error_buscar").innerHTML = err08;
		document.getElementById("error_buscar").style.display = "block";
		valida = false;
	}
	return valida;
}

/* ************** ONLOAD *************** */
// Al cargar la página, así mantenemos el HTML libre de javascript
window.onload = function () {
	'use strict';

	/* COMPATIBILIDAD */
	// IE7/8 getElementsByClassName
	if ( !document.getElementsByClassName ) {
		document.getElementsByClassName = function( className ) {
			var a = [];
			var re = new RegExp('(^| )'+className+'( |$)');
			var els = document.getElementsByTagName("*");
			for(var i=0,j=els.length; i<j; i++)
				if(re.test(els[i].className))a.push(els[i]);
			return a;
		};
	}
	// Agrega la funcion de last a los arrays
	if(!Array.prototype.last) {
		Array.prototype.last = function() {
			return this[this.length - 1];
		}
	}
	// Funcion toISOString para antiguos internet explorer
	if ( !Date.prototype.toISOString ) {
		(function() {
			function pad(number) {
				var r = String(number);
				if ( r.length === 1 ) { r = '0' + r; }
				return r;
			}
			Date.prototype.toISOString = function() {
				return this.getUTCFullYear()
					+ '-' + pad( this.getUTCMonth() + 1 )
					+ '-' + pad( this.getUTCDate() )
					+ 'T' + pad( this.getUTCHours() )
					+ ':' + pad( this.getUTCMinutes() )
					+ ':' + pad( this.getUTCSeconds() )
					+ '.' + String( (this.getUTCMilliseconds()/1000).toFixed(3) ).slice( 2, 5 )
					+ 'Z';
			};

		}());
	}

	// Cambia el formato de las fechas SQL de clase "fecha"
	// a un lenguaje mas natural
	formatDates();
}
