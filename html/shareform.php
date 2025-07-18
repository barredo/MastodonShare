<?php
/**
 * Similar to share.php but also shows any extra note text that was
 * captured.  Used when users must select an instance before sharing.
 */
include(HTML.'inc_header.php');

echo '<form method="get" action="'.URL.'" id="share" class="px-3">';
echo '<a href="'.URL.'" class="mb-3"><img alt="'.APP_NAME.'" src="'.URL.'s/img/mastodonshare160.png"/></a>';

echo '<div class="card mb-3">';
echo '<div class="card-header fw-bold">Sharing on Mastodon:</div>';
echo '<div class="card-body">';
echo '<p class="card-text">';
if(!empty($_SESSION['text'])) {
  echo nl2br(trim(urldecode($_SESSION['text'])));
  if(!empty($_SESSION['url'])) {
    echo " ";
  }
}
if(!empty($_SESSION['url'])) {
  echo "<br/><br/>".trim(urldecode($_SESSION['url']));
}
if(!empty($_SESSION['note'])) {
  echo "<br/><br/>".trim(urldecode($_SESSION['note']));
}
echo '</p>';
echo '</div></div>';
echo '<input type="hidden" name="text" class="form-control" value="'.trim(urldecode($_SESSION['text'])).'">';
echo '<input type="hidden" name="url" class="form-control" value="'.trim(urldecode($_SESSION['url'])).'">';

echo '<div class="input-group">';
  echo '<input type="text" name="set_instance" class="form-control" placeholder="Your mastodon instance">';

  echo '<button class="btn btn-own" type="submit">';
  echo '<i class="fa fa-check-circle"></i>';
  echo '<span class="ms-2 d-none d-sm-inline">Save</span>';
  echo '</button>';
echo '</div>';

echo '</form>';

include(HTML.'inc_footer.php');