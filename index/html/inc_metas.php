<?php

$title = APP_NAME.' &mdash; Mastodon Search Engine';
if(isset($_GET['q'])) {
    $title = clean_general($_GET['q']).' &mdash; '.APP_NAME;
}


echo '<title>'.$title.'</title>';
echo '<meta name="title" content="'.$title.'">';
echo '<meta name="description" content="Find for content across several Mastodon instances with Mastodon Search">';
?>

<meta name="robots" content="index, follow">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="language" content="English">