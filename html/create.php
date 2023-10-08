<?php
include(HTML.'inc_header.php');

echo '<form method="get" action="'.URL.'" id="create" class="px-3">';
echo '<a href="'.URL.'" class="mb-3"><img alt="'.APP_NAME.'" src="'.URL.'s/img/mastodonshare160.png"/></a>';

// echo '<div class="input-group">';
  echo '<textarea name="text" rows="3" class="form-control" placeholder="Your text"></textarea>';
  echo '<input type="url" name="url" class="form-control" placeholder="https://">';

  echo '<button class="btn btn-own" type="submit">';
    echo '<i class="fa fa-plus-circle"></i>';
    echo '<span class="ms-2 d-none d-sm-inline">Share on ';
    echo empty($_COOKIE['instance'])?'your instance':$_COOKIE['instance'];
    echo '</span>';
  echo '</button>';
// echo '</div>';

echo '</form>';

include(HTML.'inc_footer.php');