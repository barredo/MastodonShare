<?php

// p($_COOKIE);
// p($_SESSION);
// p($_GET);

$url = URL;

if(only_instance($_COOKIE['instance']) == $_COOKIE['instance']) {
  $url   = 'https://'.$_COOKIE['instance'];
  $share = '';

  if(!empty($_SESSION['text']) && !empty($_SESSION['url'])) {
    $share = trim($_SESSION['text'])."\n\n".trim($_SESSION['url']);
  } elseif(empty($_SESSION['text']) && !empty($_SESSION['url'])) {
    $share = trim($_SESSION['url']);
  } elseif(!empty($_SESSION['text']) && empty($_SESSION['url'])) {
    $share = trim($_SESSION['text']);
  } elseif(!empty($_GET['text']) && !empty($_GET['url'])) {
    $share = trim($_GET['text'])."\n\n".trim($_GET['url']);
  } elseif(empty($_GET['text']) && !empty($_GET['url'])) {
    $share = trim($_GET['url']);
  } elseif(!empty($_GET['text']) && empty($_GET['url'])) {
    $share = trim($_GET['text']);
  }

  if(!empty($share)) {
    $url = $url.'/share?text='.urlencode($share);
  }

  db::conectar();
  db::query("insert into `activity` (`activity`,`data`) values ('r','".addslashes($url)."');");

}

$_SESSION = false;
session_destroy();

header("Location: ".$url);
exit;