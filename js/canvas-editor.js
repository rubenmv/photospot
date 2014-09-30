var im_original;
// Herramienta de dibujado
var tool = "line";
// Size del pincel para dibujar
var	color_brush;

function cargarImagen (src) {
	"use strict";
	var canvas = document.getElementById('canvas'),
		ctx = canvas.getContext('2d'),
		// Dimensiones maximas de la imagen
		im_width = canvas.width, im_height = canvas.height,
		// Posicion en el canvas
		x, y,
		img = new Image();

	img.onload = function() {
		// Reducimos la imagen al canvas manteniendo las proporciones
		while(img.width > canvas.width || img.height > canvas.height) {
			img.width *= 0.99;
			img.height *= 0.99;
		}

		im_width = img.width;
		im_height = img.height;

		// Posicion en el canvas
		x = canvas.width/2 - im_width/2;
		y = canvas.height/2 - im_height/2;

		ctx.drawImage(img, x, y, im_width, im_height);
	}

	im_original = src;
	img.src = im_original;
}

// Resetea la imagen con la imagen original cargada al principio
// Util para aplicar varios filtros de color sin que se solapen unos a otros
function resetImagen() {
	"use strict";
	var canvas = document.getElementById('canvas');
	var ctx = canvas.getContext('2d');

	// Limpiamos el canvas
	ctx.clearRect(0, 0, canvas.width, canvas.height);
	cargarImagen(im_original);
}

// Funcion que establece la herramienta de dibujado
function setTool(h) {
	"use strict";
	tool = h;
}
// Inicializa el editor de dibujado de formas
function initEditor() {
	"use strict";

	var canvas = document.getElementById('canvas'),
		ctx = canvas.getContext('2d'),
		imData = ctx.getImageData(0, 0, canvas.width, canvas.height);

	// Puntos de inicio y fin de dibujo
	var x1, y1, x2, y2, grosor, color, temp;
	// Offset de canvas con respecto a la pagina
	var offset = $('#canvas').offset();
	// Indica si se esta dibujando actualmente (para actualizar en tiempo real)
	var isStarted = false;

	function draw(e) {
		// Estilo del pincel
		grosor = document.getElementById('grosor').value;
		color = document.getElementById('color').value;
		// Posicion actual
		x2 = e.pageX - offset.left;
		y2 = e.pageY - offset.top;

		// Borramos antes de dibujar la forma definitiva
		ctx.clearRect(0, 0, canvas.width, canvas.height);
		// Volvemos a dibujar la imagen encima
		ctx.putImageData(imData, 0, 0);

		ctx.fillStyle = color;
		ctx.lineWidth = grosor;
		ctx.strokeStyle = 'black'; // Borde negro por defecto

		if(tool === 'line') {
			ctx.beginPath();
			ctx.moveTo(x1, y1);
			ctx.lineTo(x2, y2);
			ctx.strokeStyle = color; // La linea coge el color seleccionado
			ctx.stroke();
			ctx.closePath();
		}
		else if (tool === 'rectangle') {
			// Intercambiamos inicio y fin cuando el rectangulo se dibuja hacia la izquierda/arriba
			if(!isStarted) {
				if(x1 > x2) { temp = x1; x1 = x2; x2 = temp; }
				if(y1 > y2) { temp = y1; y1 = y2; y2 = temp; }
			}
			
			ctx.beginPath();
			ctx.rect(x1, y1, x2-x1, y2-y1);

			if(document.getElementById('bg_transp').checked === false)
				ctx.fill();
			else
				ctx.strokeStyle = color;
	
			ctx.stroke();
		}
		else if (tool === 'circle') {
			var radius = Math.abs(x2 - x1);

			ctx.beginPath();
			ctx.arc(x1, y1, radius, 0, 2 * Math.PI, false);

			if(document.getElementById('bg_transp').checked === false)
				ctx.fill();
			else
				ctx.strokeStyle = color;
			
			ctx.stroke();
		}
	}

	// Al hacer clic inicializamos el dibujado
	$('#canvas').mousedown(function (e) {
		// Posicion inicial
		x1 = e.pageX - offset.left;
		y1 = e.pageY - offset.top;
		
		// Guardamos la imagen actual para actualizar en tiempo real
		imData = ctx.getImageData(0, 0, canvas.width, canvas.height);

		isStarted = true; // Se ha comenzado a dibujar
		return false; // Desactiva el cursor de tipo selector de texto
	});

	// Con el movimiento del raton vamos actualizando la forma
	$('#canvas').mousemove(function (e) {
		if(isStarted) {
			draw(e);
		}
	});

	// En mouseup dibujamos la forma definitiva
	$('#canvas').mouseup(function (e) {
		draw(e);
		isStarted = false; // Se ha terminado de dibujar
	});
}

// Guarda la imagen editada del canvas
function saveCanvas() {
	"use strict";
	var canvas = document.getElementById('canvas');
	var imagen = document.createElement('img');
	imagen.src = canvas.toDataURL();
	window.open(imagen.src);
}

// Rota el contenido del canvas los grados indicados
function rotate (angle) {
	"use strict";
	var canvas = document.getElementById('canvas'),
		ctx = canvas.getContext('2d');

	var image = new Image();
	image.src = canvas.toDataURL();

	ctx.clearRect(0,0,canvas.width, canvas.height);

	image.onload = function () {
		// Guardamos el sistema de coordenadas
		ctx.save();

		// Movemos el sistema de coordenadas al centro del canvas (tambien centro de la imagen)
		ctx.translate(canvas.width/2, canvas.height/2);
	 
		// Rotamos
		ctx.rotate(angle * Math.PI/180); // A radianes
	 	
		// Volvemos a dibujar la imagen
		ctx.drawImage(image, -(image.width/2), -(image.height/2));

		// Restauramos el sistema de coordenadas
		ctx.restore();
	}

}

// Escala la imagen en un factor dado en porcentaje
function scale() {
	"use strict";

	var canvas = document.getElementById('canvas'),
		ctx = canvas.getContext('2d'),
		imagen = new Image(),
		// Nuevo tamaño y posicion (para centrarla)
		x, y, width, height;

	// Recogemos la url de la imagen
	imagen.src = canvas.toDataURL();

	// El factor llega en porcentaje
	var f = parseInt(document.getElementById('factor_escala').value) / 100;

	imagen.onload = function() {
		ctx.clearRect(0, 0, canvas.width, canvas.height);
		// Calculamos el nuevo tamaño
		width = imagen.width * f;
		height = imagen.height * f;
		// Posicion en el canvas
		x = canvas.width/2 - width/2;
		y = canvas.height/2 - height/2;
		ctx.drawImage(imagen, x, y, width, height);
	}
}

/* WEBWORKERS */
function filtroColor(filtro, src) {
	"use strict";

	var canvas = document.getElementById('canvas'),
		ctx = canvas.getContext('2d'),
		imData = ctx.getImageData(0, 0, canvas.width*1.5, canvas.height*1.5),
		worker;

	if(filtro === 'R') { // Rojo
		worker = new Worker('js/webworkers/toRed_worker.js');
	}
	else if(filtro === 'G') { // Verde
		worker = new Worker('js/webworkers/toGreen_worker.js');
	}
	else if(filtro === 'B') { // Azul
		worker = new Worker('js/webworkers/toBlue_worker.js');
	}
	else if(filtro === 'GR') { // Blanco y negro
		worker = new Worker('js/webworkers/toGray_worker.js');
	}
	else if(filtro === 'S') { // Sepia
		worker = new Worker('js/webworkers/toSepia_worker.js');
	}

	// Cuando el worker recibe el mensaje...
	worker.onmessage = function  (e) {
		var imDataR = e.data;
		ctx.putImageData(imDataR, 0, 0);
	}

	// Enviamos el mensaje al webworker
	worker.postMessage(imData);

} 
