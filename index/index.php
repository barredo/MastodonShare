<?php
require_once 'm/config.php';
exit;

if(empty($_GET['s']) OR empty($_GET['q'])) {
	$_GET['s'] = 'landing';
}

if(!empty($_GET['q'])) {
	$_GET['s'] = 'results';
}
/*

- mostrar videos si hay youtube

- sort popular
- buscar personas/nicks

- indice de objetos
- busqueda mysql con objeto
- busqueda mysql en usernames
- operador: from
- operador: mention
- operador: last:7days?

- trends
+ indizador presente
- indizador desde alante

+ reindizar indexed = 1
+ mostrar imagenes

+ reindexar manteniendo el aspecto
+ reindexar pillando imágenes
+ reindexar con el numero de boosts y likes
+ paginacion
+ ordenar por fecha
+ operador: date_min
+ operador: date_max
+ busqueda mysql con operadores
+ operador: instance
+ operador: date
+ deteccion de objetos: hashtag
+ deteccion de objetos: user
+ deteccion de objetos: url
+ busqueda mysql real fulltext
+ stop words
+ resaltar con negrita en los resultados
*/

switch($_GET['s']){

	case 'results':
		include(HTML.'results.php');
		exit;
	break;

	case 'about':
		include(HTML.'about.php');
		exit;
	break;

	default:
		include(HTML.'landing.php');
	break;

}