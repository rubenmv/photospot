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
			// Convertimos a sepia
			imData.data[(i * imData.width + j) * 4] = Math.min(r * 0.393 + g * 0.769 + b * 0.189, 255);
			imData.data[(i * imData.width + j) * 4 + 1] = Math.min(r * 0.349 + g * 0.686 + b * 0.168, 255);
			imData.data[(i * imData.width + j) * 4 + 2] = Math.min(r * 0.272 + g * 0.534 + b * 0.131, 255);
		};
	};

	self.postMessage(imData);
}