<?php
/**
 * Page where a user specifies the Mastodon instance to use. The choice
 * will be saved in a cookie for future visits.
 */
include(HTML.'inc_header.php');

echo '<form method="get" action="'.URL.'" id="instance" class="px-3">';
echo '<a href="'.URL.'" class="mb-3"><img alt="'.APP_NAME.'" src="'.URL.'s/img/mastodonshare160.png"/></a>';


echo '<div class="input-group">';
  echo '<input type="text" name="set_instance" class="form-control" placeholder="Your mastodon instance">';

  echo '<button class="btn btn-own" type="submit">';
  echo '<i class="fa fa-check-circle"></i>';
  echo '<span class="ms-2 d-none d-sm-inline">Save</span>';
  echo '</button>';
echo '</div>';


include('inc_about.php');

echo '</form>';

include(HTML.'inc_footer.php');