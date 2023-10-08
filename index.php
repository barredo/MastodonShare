<?php
require_once 'm/config.php';

$instance = null;

if(!empty($_GET['set_instance'])) {
	$instance = only_instance($_GET['set_instance']);
	$_COOKIE['instance'] = $instance;
	setcookie(
		"instance",
		$instance,
		time()+60*60*24*365,
		"/",
		".".DOMAIN,
		true
	);
} elseif(!empty($_GET['del_instance'])) {
	$_COOKIE = false;
	setcookie(
		"instance",
		"",
		time()-1000,
		"/",
		".".DOMAIN,
		true
	);
	header("Location: ".URL);
	exit;
} elseif($_GET['s'] === 'about') {
	include(HTML.$_GET['s'].'.php');
	exit;
} elseif($_GET['s'] === 'share') {
	// p($_SERVER);
	// exit;	
	if(empty($_GET['url'])) {
		$_GET['url']  = $_SERVER['HTTP_REFERER'];
		// $_GET['s']  = $_SERVER['HTTP_REFERER'];
	}
}

if(!empty($_COOKIE['instance'])) {
	$instance = only_instance($_COOKIE['instance']);
}
if(!empty($_GET['instance'])) {
	$instance = only_instance($_GET['instance']);
}

if(empty($_GET['url']) && empty($_GET['text'])) {

	if(empty($instance)) {
		$_GET['s'] = 'instance';
	} else {
		$_GET['s'] = 'create';
	}

} else {

	session_start();
	$_SESSION['text'] = $_GET['text'];
	$_SESSION['url']  = $_GET['url'];

	if(empty($instance)) {
		$_GET['s'] = 'shareform';
	} else {
		$_GET['s'] = 'redirect';
	}

}

include(HTML.$_GET['s'].'.php');
exit;


// https://mastodon.social/share?text=Mastodon%20Share%20https%3A%2F%2Fmastodonshare.com%2F%3Fs%3Dresults%26url%3Dhttps%253A%252F%252Fcuonda.com%252Fquinto-nivel%252Ffeed

/*

usuario llega sin parametros =
	1. tiene instancia
		1. llevar a formulario para que los introduzca

	2. no tiene instancia
		1. pedir instancia
		2. guardar instancia en cookie
		3. llevar a formulario para que los introduzca

usuario llega con parámetros
	1. tiene instancia
		1. redirigir al compartir de su instancia con los parámetros
	1. no tiene instancia
		1. guardar parametros en sesion
		2. pedir instancia
		3. guardar instancia en cookie
		4. redirigir al compartir de su instancia con los parámetros


- se puede forzar instancia por _GET

*/

// switch($_GET['s']){

// 	case 'share':
// 		include(HTML.'share.php');
// 		exit;
// 	break;

// 	case 'instance':
// 		include(HTML.'instance.php');
// 		exit;
// 	break;

// 	case 'about':
// 		include(HTML.'about.php');
// 		exit;
// 	break;

// 	default:
// 		include(HTML.'landing.php');
// 	break;

// }