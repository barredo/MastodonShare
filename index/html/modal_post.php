<?php

echo '
<div id="modal-post" class="d-none">';

// echo '<div class="cell header bg-dark-blue border-bottom border-right border-dark px-2">';
//     echo '<a class="text-muted" aria-hidden="true">';
// 		echo '<span class="text-muted fa fa-comment fs-6 me-2"></span>';
// 		echo '<span class="text-light fw-bold fs-6 me-1">Post</span>';
// 		// echo '<span class="text-muted fs-7">'.actual_logged().'</span>';
// 	echo '</a>';
//     echo '<a id="column_post_hide" class="text-blue fa fa-times" aria-hidden="true"></a>';
// echo '</div>';

// <input type="hidden" name="exit" value="1"/>

echo '
<form method="post" action="'.URL.'send" class="bg-darkerblue rounded border border-light-blue" id="modal-post-form">

<div class="mb-3 px-3 py-2 border-bottom border-darker-blue bg-darkblue text-light d-flex justify-content-between">
	<a class="fa fa-close fs-4 text-muted" id="modal-close"></a>	
	<button type="submit" class="btn btn-blue fs-7 py-1 d-block">
		<i class="fa fa-comment me-1" aria-hidden="true"></i> Post
	</button>
</div>

<div class="mb-3 px-3 text-light fs-7 d-flex justify-content-between">
	<img style="width:2em;" class="rounded-circle" src="'.$_SESSION['MASTODON_AVATAR'].'"/>
	<span>'.actual_logged().'</span>
</div>

<input type="hidden" name="in_reply_to_id" id="in_reply_to_id"/>

<div class="d-none px-3 mb-3 cell fs-7 text-light bg-trans border border-dark rounded p-2" id="in_reply_to_field">
	<div class="" id="in_reply_to_preview"></div>
</div>

<div class="mb-2 px-3">
<textarea maxlength="500" id="modal-post-text" class="form-control px-2 fs-7" name="text" rows="5" placeholder=""></textarea>
</div>

<button type="submit" class="btn btn-blue fs-7 d-block m-3">
	<i class="fa fa-comment me-1" aria-hidden="true"></i> Post
</button>

</form>';


echo '</div>';
?>