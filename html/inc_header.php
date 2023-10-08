<?php

echo '<!DOCTYPE html>';
echo '<html>';

echo '<head>';
echo '<meta charset="UTF-8">';
echo '<meta http-equiv="X-UA-Compatible" content="IE=edge">';
echo '<meta name="viewport" content="width=device-width, initial-scale=1">';
echo '<link href="'.URL.'s/bootstrap.min.css" rel="stylesheet">';
echo '<link href="'.URL.'s/font-awesome.min.css" rel="stylesheet">';
echo '<link rel="stylesheet" href="'.URL.'s/mastodonshare.css" >';
echo '<link rel="shortcut icon" href="'.URL.'s/img/favicon.ico" type="image/x-icon">';
include('inc_metas.php');
echo '</head>';

echo '<body>';

// echo '<nav id="nav" class="navbar navbar-normal border-bottom border-light-blue">
// <div class="container-sm">
//     <a class="navbar-brand" href="'.URL.'">
//       <img src="'.URL.'s/img/mastodeck.png" alt="MastoDeck" class="float-left"><span class="ms-1 d-inline-block fw-bold fs-3 text-light">MastoDeck</span>
//     </a>';

//     foreach($sections as $section => $url) {
//       echo '<a class="navbar-text'.(isset($_GET['s']) && $url===$_GET['s']?' text-white fw-bold':'').'" href="'.URL.$url.'">'.$section.'</a>';
//     }

// echo '
// </div>
// </nav>';

//   echo '<div id="global">';
//   echo '<div id="common" class="mt-3 pb-5 container-sm">';
//   echo '<div>';

//   if(!empty($_SESSION['message'])) {
//   echo '<div class="mb-3"><div class="card border bg-darkblue border-light-blue text-white"><div class="card-body fs-7 py-2"><i class="fa fa-check-circle text-blue me-2" aria-hidden="true"></i>';
//   echo $_SESSION['message'];
//   echo '</div></div></div>';
//   unset($_SESSION['message']);
// }

// if(!empty($_SESSION['warning'])) {
//   echo '<div class="mb-3"><div class="card border bg-danger border-dark text-white"><div class="card-body fs-7 py-2"><i class="fa fa-exclamation-circle me-2" aria-hidden="true"></i>';
//   echo $_SESSION['warning'];
//   echo '</div></div></div>';
//   unset($_SESSION['warning']);
// }


// if(!empty($_SESSION['error'])) {
//   echo '<div class="mb-3"><div class="card border bg-danger border-dark text-white"><div class="card-body fs-7 py-2"><i class="fa fa-exclamation-circle me-2" aria-hidden="true"></i>';
//   echo $_SESSION['error'];
//   echo '</div></div></div>';
//   unset($_SESSION['error']);
// }
