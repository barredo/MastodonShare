<?php

echo '
<div id="column-post" class="column column-post d-none" name="cp">';

echo '<div class="cell header bg-dark-blue border-bottom border-right border-dark px-2">';
    echo '<a class="text-muted" aria-hidden="true">';
		echo '<span class="text-muted fa fa-comment fs-6 me-2"></span>';
		echo '<span class="text-light fw-bold fs-6 me-1">Post</span>';
		// echo '<span class="text-muted fs-7">'.actual_logged().'</span>';
	echo '</a>';
    echo '<a id="column_post_hide" class="text-blue fa fa-times" aria-hidden="true"></a>';
echo '</div>';


echo '
<form method="post" action="'.URL.'?a=send" class="m-3" id="column_post">

<div class="mb-3 text-light fs-7 d-flex justify-content-between">
	<img style="width:2em;" class="rounded-circle" src="'.$_SESSION['MASTODON_AVATAR'].'"/>
	<span>'.actual_logged().'</span>
</div>

<input type="hidden" name="exit" value="1"/>
<input type="hidden" name="in_reply_to_id" id="in_reply_to_id"/>

<div class="d-none mb-3 cell fs-7 text-light bg-trans border border-dark rounded p-2" id="in_reply_to_field">
	<div class="" id="in_reply_to_preview"></div>
</div>
<div class="mb-2">
<textarea id="column_post_text" class="form-control px-2 fs-7" name="text" aria-describedby="text" rows="5"></textarea>
</div>

<button type="submit" class="btn btn-blue fs-7 d-block pull-right">
	<i class="fa fa-comment me-1" aria-hidden="true"></i> Post
</button>

</form>';


echo '</div>';
?>