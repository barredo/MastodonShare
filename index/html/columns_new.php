<?php

echo '
<div id="column-add" class="column column-add d-none" name="ca">';

echo '<div class="cell header bg-dark-blue border-bottom border-right border-dark px-2">';
echo '<a class="text-muted" aria-hidden="true">';
echo '<span class="text-muted fa fa-plus-circle fs-6 me-2"></span>';
echo '<span class="text-light fw-bold fs-6 me-1">New Column</span>';
// echo '<span class="text-muted fs-7">'.actual_logged().'</span>';
echo '</a>';
echo '<a href="#" onclick="column_add_button_toggle()" class="text-blue fa fa-times" aria-hidden="true"></a>';
echo '</div>';

echo '<div class="cells m-2 rounded border border-light-blue border-bottom-0 d-flex flex-column justify-content-start" style="height:auto">';

$lists = column_types();

foreach($lists as $list) {
	echo '<a href="'.URL.'columnadd?type='.$list.'" class="text-light p-2 border-bottom border-light-blue bg-darkerblue">';
		echo '<i class="text-muted fa '.column_icon($list).' me-2 fs-5" style="width:1em;"/></i>';
		echo '<span class="">'.column_name($list).'</span>';
	echo '</a>';
}

echo '</div>';

echo '<div class="cells m-2 rounded border border-light-blue border-bottom-0 d-flex flex-column justify-content-start" style="height:auto">';
echo '<a href="'.URL.'manage" class="text-light p-2 border-bottom border-light-blue bg-darkerblue">';
	echo '<i class="text-muted fa fa-sliders me-2 fs-5" style="width:1em;"/></i>';
	echo '<span class="">Manage columns</span>';
echo '</a>';
echo '</div>';

echo '</div>';
?>