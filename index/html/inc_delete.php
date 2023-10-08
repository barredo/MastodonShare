<?php

// include('inc_header.php');

echo '<div class="mb-3 mt-5"><div class="card mb-5 border border-blue bg-dark-blue text-light">';

echo '<div class="card-header fw-bold">Delete your MastoDeck data.</div>';
echo '<div class="card-body">';
echo '<p class="card-text">MastoDeck does not hold your Mastodon passwords or personal data. MastoDeck only holds an API key to make some actions on your behalf. We do not use external cookies. We do not track your activity.</p>';

echo '<p class="card-text">By completing this form, the API Key associated with your username + instance <b>('.actual_logged().')</b> at MastoDeck will be permanently deleted.</p>';
echo '<p class="card-text fw-bold text-danger">Your Mastodon account WILL NOT BE ALTERED in any way. No content will me removed.</p>';;
echo '<p class="card-text">This will just delete the API Key from MastoDeck.</p>';
echo '</div>';

echo '</div></div>';

echo '<div class="mt-5">';
  echo '<a href="'.URL.'delete?check='.actual_logged_hash().'" class="shadow btn btn-danger"><i class="fa fa-trash me-2" aria-hidden="true"></i> Delete my MastoDeck</a>';
echo '</div>';


// include('inc_spread.php');
// include('inc_footer.php');

?>