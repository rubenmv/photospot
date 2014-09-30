/*  *************************************
	FUNCIONES DE ORDENACIÓN DE RESULTADOS
	************************************* */

/* Funciones que implementan el algoritmo de ordenación por Inserción
 * http://www.the-art-of-web.com/javascript/insertionsort/ */

// GLOBALES
var resultados = null,
	vacio = '';

// Obtiene el valor o texto correspondiente al elemento a comparar
function getValor(i, clase) {
	'use strict';
	var nodo = getElementsByClassName(resultados[i], clase)[0]; // Solo habra 1 como mucho
	if(nodo !== undefined) {
		// Parece la unica manera de acceder a datetime
		if (clase === 'fecha') { return nodo.getAttribute('datetime'); }
		else { return nodo.innerHTML; }
	}
	return vacio;
}

// Funcion que compara dos valores y devuelve verdadero o falso
function compara(val1, val2, criterio, orden) {
	'use strict';
	if(criterio === 'fecha') {
		val1 = new Date(val1);
		val2 = new Date(val2);
	}
	// Ascendente
	if(orden) { return (val1 > val2); }
	// Descendente
	return (val1 < val2);
}

// Funcion que intercambia dos elementos con respecto a su padre
function intercambia(i, j) {
	'use strict';
	var padre = document.getElementById("lista-resumen");
	padre.insertBefore(resultados[i], resultados[j]);
}

function sortFotos() {
	'use strict';
	var j = 0, i = 0, n,
		criterio, orden;

	criterio = document.getElementById('criterio-ordenacion').value;
	orden = document.getElementById('orden').checked;

	// Nodo padre, contenedor de los resumenes
	var padre = document.getElementById("lista-resumen");
	// Recogemos todos los elementos div de tipo resumen en un NodeList (no es un Array)
	resultados = getElementsByClassName(padre, 'resumen');
	// Numero de elementos a ordenar
	n = resultados.length;
	// Insertion sort
	for (j = 1; j < n; j++) {
		for (i = j; i > 0 && compara(getValor(i, criterio), getValor(i - 1, criterio), criterio, orden); i--) {
			intercambia(i, i - 1);
		}
	}
}