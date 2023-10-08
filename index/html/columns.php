<?php

include('inc_header_columns.php');
// include('columns_post.php');
include('modal_post.php');
include('columns_new.php');

// print_r($columns);
// exit;

foreach($columns as $column_key => $column) {
	// $column_data = $column['data'];

// p($column['data']);
// return;

// $column_data = !empty($column['data']) ? json_decode($column['data'],true) : [];

// p($column_data);
// exit;

echo '
<div class="column" ';
echo 'id="'.column_id($_SESSION['MASTODON_USER'],$_SESSION['instance'],$column['order']).'" ';
echo 'name="c'.$column['order'].'" ';
echo 'type="'.$column['type'].'" ';
echo 'refresh="'.column_refresh($column['type']).'" ';

if(count($column['data']) > 0) {
	foreach($column['data'] as $data_key => $data_value) {
		echo 'data-'.$data_key.'="'.$data_value.'" ';
	}
}
echo '>';

echo '<div class="cell header bg-dark-blue border-bottom border-right border-dark px-2">';
    // echo '<a class="text-muted" aria-hidden="true">';
	echo '<span class="text-muted fa '.column_icon($column['type']).' fs-6 me-2"></span>';
	echo '<img class="avatar-micro me-2" src="'.$_SESSION['MASTODON_AVATAR'].'"/>';
	echo '<span class="text-light fs-6 me-1"><span class="fw-bold">'.column_name($column['type']).'</span>';
	if(column_data($column)) {
		echo '<span class="d-block text-muted fs-7" style="margin-top:-0.5em;">'.column_data($column).'</span>';
	}
	echo '</span>';
	if(column_editable($column['type'])) {
		echo '<a target="_new" href="'.URL.'manage?order='.$column['order'].'" class="text-blue fa fa-cog" style="margin-left: auto;"></a>';
	} else {
		echo '<a style="margin-left: auto;"></a>';
	}
echo '</div>';

echo '<div class="cells">';
// echo '<div class="celld text-light p-3 fs-7">Loading<i class="fa fa-spinner fa-spin ms-2"></i></div>';
echo '<div class="celld text-light p-3 fs-7"></div>';

// for($i = 1; $i < rand(10,20); $i++) {
// 	echo '<div class="cell text-light fs-7 p-2 border-bottom border-dark">';
// 	echo '<div class="avatar">';
// 	echo '<img src="https://pbs.twimg.com/profile_images/1597626814465282048/wfsSG7Eq_bigger.jpg"/>';
// 	echo '</div>';
// 	echo '<div class="content">';
// 		echo '<div class="meta">';
// 			echo '<a class="text-light fw-bold">@pepesilvia</a>';
// 			echo '<a class="float-right fs-8">20m</a>';
// 		echo '</div>';
// 		echo '<p>Just do a GET request on loripsum.net/api, to get some placeholder text. You can add extra parameters to specify the output you</p>';
// 		echo '<div class="actions">';
// 			echo '<a class="fa fa-reply" aria-hidden="true"></a>';
// 			echo '<a class="fa fa-comment" aria-hidden="true"></a>';
// 			echo '<a class="fa fa-retweet" aria-hidden="true"></a>';
// 			echo '<a class="fa fa-star" aria-hidden="true"></a>';
// 			echo '<a class="fa fa-bookmark" aria-hidden="true"></a>';
// 			echo '<a class="fa fa-ellipsis-h" aria-hidden="true"></a>';
// 		echo '</div>';
// 	echo '</div>';
// 	echo '</div>';

// 	echo '<div class="cell text-light fs-7 p-2 border-bottom border-dark">';
// 	echo '<div class="avatar">';
// 	echo '<img src="https://pbs.twimg.com/profile_images/1508447393875906563/vHkC56Lj_bigger.jpg"/>';
// 	echo '</div>';
// 	echo '<div class="content">';
// 		echo '<div class="meta">';
// 			echo '<a class="text-light fw-bold">@mariovaquerizo</a>';
// 			echo '<a class="float-right fs-8">20m</a>';
// 		echo '</div>';
// 		echo '<p>Join us live from 10:00 CET to hear the latest #solar figures for the EU27 and an exclusive fireside chat with EU Energy Commissioner @KadriSimson </p>';
// 		echo '<div class="actions">';
// 			echo '<a class="fa fa-reply" aria-hidden="true"></a>';
// 			echo '<a class="fa fa-comment" aria-hidden="true"></a>';
// 			echo '<a class="fa fa-retweet" aria-hidden="true"></a>';
// 			echo '<a class="fa fa-star" aria-hidden="true"></a>';
// 			echo '<a class="fa fa-bookmark" aria-hidden="true"></a>';
// 			echo '<a class="fa fa-ellipsis-h" aria-hidden="true"></a>';
// 		echo '</div>';
// 	echo '</div>';
// 	echo '</div>';
// }

echo '</div>';
echo '</div>';
}


include('inc_footer_columns.php');
?>