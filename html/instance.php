<?php
include(HTML.'inc_header.php');

echo '<form method="get" action="'.URL.'" id="instance" class="px-3">';
echo '<a href="'.URL.'" class="mb-3"><img alt="'.APP_NAME.'" src="'.URL.'s/img/mastodonshare160.png"/></a>';

// echo '<div class="card mb-3">';
// echo '<div class="card-header fw-bold">Welcome to MastodonShare.com</div>';
// echo '<div class="card-body">';
// echo '<p class="card-text">Share content on any Mastodon with MastodonShare.</p>';
// echo '</div></div>';

echo '<div class="input-group">';
  echo '<input type="text" name="set_instance" class="form-control" placeholder="your mastodon instance">';

  echo '<button class="btn btn-own" type="submit">';
  echo '<i class="fa fa-check-circle"></i>';
  echo '<span class="ms-2 d-none d-sm-inline">Save</span>';
  echo '</button>';
echo '</div>';


include('inc_about.php');

echo '</form>';



include(HTML.'inc_footer.php');