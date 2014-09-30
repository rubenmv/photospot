// Cuando el worker recibe el mensaje...
self.onmessage  = function (e) {
	var imData = e.data,
		r, g, b, gris;

	for (var i = 0; i < imData.width; i++) {
		for (var j = 0; j < imData.height; j++) {
			// Recogemos los tres colores actuales
			r = imData.data[(i * imData.width + j) * 4];
			g = imData.data[(i * imData.width + j) * 4 + 1];
			b = imData.data[(i * imData.width + j) * 4 + 2];
			// Convertimos a escala de gris
			gris = 0.2126*r + 0.7152*g + 0.0722*b;
			imData.data[(i * imData.width + j) * 4] = gris;
			imData.data[(i * imData.width + j) * 4 + 1] = gris;
			imData.data[(i * imData.width + j) * 4 + 2] = gris;
		};
	};

	self.postMessage(imData);
}