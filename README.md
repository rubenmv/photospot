Photospot
=========

Sitio web donde publicar fotografías.
Photospot es un proyecto realizado para el Grado en Ingeniería Multimedia de la Universidad de Alicante como parte de las asignaturas de Programacion Hipermedia 1 y 2.

El objetivo del proyecto era crear un clon de Flickr para aprender sobre programación web y bases de datos. El proyecto se realizó entre Septiembre de 2012 y Junio de 2014.

Este sitio no está activo ni mantenido por nadie, se trata de un campo de pruebas y su único propósito es educativo.

En cualquier caso cabe comentar que ha sido realizado por un novato y probablemente contenga errores de diseño, inconsistencias y malas prácticas de programación, pero se ha intentado arreglar en parte para proporcionar una fuente de información para nuevos desarrolladores que quieran ver ejemplos de uso "real". Algunas de las características que se utilizan son:

* HTML5
* PHP
* LESS CSS
* Responsive Design
* AJAX
* JQuery
* Cookies
* localStorage y globalStorage
* Web Workers
* Canvas
* Boilerplate
* html5shiv
* TimbThumb para miniaturas

![screenshot](screenshot.jpg)

Instrucciones
-------------
Si se desea poner en marcha la web para comprobar su funcionamiento mi recomendación es utilizar XAMPP (http://www.apachefriends.org/en/xampp.html). Simplemente abría que mover la carpeta del proyecto al directorio 'htdocs' de la instalación de xampp y antes que nada acceder a PHPMyAdmin y crear la base de datos llamada "ps_bd" e importar su contenido mediante el fichero ps_bd.sql, de este manera se creará la estructura de datos para hacer funcionar la página con el usuario de prueba y las fotos de muestra incluidas.

Importante: el fichero includes/connectBD.inc debe ser configurado con el usuario y contraseña con permisos para acceder y modificar la base de datos.

Por defecto se agrega el prefijo "photospot_" a cada tabla para evitar conflictos en una misma base de datos con distintas aplicaciones. Si se desea cambiar esto o incluso borrarlo simplemente dejar en blanco el prefijo en el fichero de conexión. Si se van a importar los datos de demostración habría que sustituir el prefijo en también en "ps_bd.sql".

El proyecto está configurado para funcionar en la carpeta htdocs/photospot con el puerto por defecto para localhost (quedaría como http://localhost/photospot/), si se realiza algún cambio y algunas de las miniaturas dejan de funcionar, probablemente sea porque ha cambiado esa dirección del sitio y habría que modificar la RUTABASE al principio del fichero scripts-all.js para que corresponda con la ruta nueva. Esto es debido a que timbthumbs necesita la ruta completa y al cargar por ajax no se puede obtener, solo PHP puede.

Se ha incluido un usuario de prueba con la siguiente información:
	Nombre de usuario:		test
	Contraseña de usuario:	Test01


Licencia
--------
La licencia de las imágenes incluidas es de dominio público y han sido obtenidas en http://pixabay.com.

El resto de esta obra está licenciada bajo la Licencia Creative Commons Atribución-NoComercial-CompartirIgual 3.0 Unported. Para ver una copia de esta licencia, visita http://creativecommons.org/licenses/by-nc-sa/3.0/.
