// Cuando el worker recibe el mensaje...
self.onmessage  = function (e) {
	var imData = e.data;

	for (var i = 0; i < imData.width; i++) {
		for (var j = 0; j < imData.height; j++) {
			// Convertimos a sepia
			imData.data[(i * imData.width + j) * 4 + 1] = 0;
			imData.data[(i * imData.width + j) * 4 + 2] = 0;
		};
	};

	self.postMessage(imData);
}